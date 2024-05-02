<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Utils\atlaASQuery;
use HtmlFirst\atlaAS\Utils\Hasher;
use PDO;
use PDOException;
use PDOStatement;

class Conn {
    public function __construct(private App $app) {
    }
    private static function normalize_array(PDOStatement $stmts): atlaASQuery {
        return new class($stmts) extends atlaASQuery {
            public $data;
            public $count;
            public function __construct(PDOStatement $stmts) {
                $this->data = $stmts->fetchAll(PDO::FETCH_OBJ);
                $this->count = \count($this->data);
            }
        };
    }
    private static function connection_start(string $mode) {
        if (!isset($_ENV['_CONN'][$mode])) {
            return $_ENV['_CONN'][$mode] = self::connect($mode);
        }
    }
    private static function connection_close(string $mode) {
        if (isset($_ENV['_CONN'][$mode])) {
            return $_ENV['_CONN'][$mode] = null;
        }
    }
    private function connect(string $mode) {
        $conn_ = $this->app->app_env::$conn;
        $httpmode = $this->app->request->http_mode;
        $host = $conn_[$httpmode][$mode]['host'];
        $username = $conn_[$httpmode][$mode]['username'];
        $password = $conn_[$httpmode][$mode]['password'];
        $db = $conn_[$httpmode][$mode]['db'];
        $type = $conn_[$httpmode][$mode]['type'];
        $filename = $conn_[$httpmode][$mode]['file_name'];
        $encoding = $conn_[$httpmode][$mode]['encoding'];
        try {
            switch ($type) {
                case 'mdb':
                case 'accdb':
                    $conn = new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};charset=' . $encoding . '; DBQ=' . $filename . '; Uid=' . $username . '; Pwd=' . $password . ';');
                    break;
                case 'sqlite':
                    $conn = new PDO($type . ':' . $filename);
                    break;
                default:
                    $conn = new PDO($type . ':host=' . $host . ';dbname=' . $db, $username, $password);
                    break;
            }
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
        } catch (PDOException $e) {
            print "Connection failed!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $conn;
    }
    /**
     * Perform an SQL request.
     * @param string $sql_query The SQL query to execute.
     * @param string|null $csrf_key
     * @param string|null $connection
     * @param array|null $bind
     *[
     *    ...
     *    'field_name'=>[?PDO::PARAM_type, ?$value_to_bind],
     *]
     * @param bool $enable_csrf_check
     * - true: use default checking;
     * - false: disable csrf checking --only do this when checking;
     */
    public function sql_query(
        string $sql_query,
        string|null $csrf_key = null,
        string|null $connection = null,
        array|null $bind = null,
        bool $enable_csrf_check = true
    ): atlaASQuery {
        $connection = $connection ?? $this->app->app_env::$default_connection;
        $method = $this->app->request->get_query_param();
        $hasher = new Hasher($this->app);
        if (($method !== 'get' || $csrf_key !== null) && $enable_csrf_check) {
            $hasher->csrf_check($csrf_key);
        }
        $pdo = self::connection_start($connection);
        $_api = $this->app->app_env::$api;
        $METHOD = $this->app->request->get_query_param();
        if ($_SERVER['REMOTE_ADDR'] === $this->app->app_settings::server_ip()) {
            $api_key = $_api['key'];
        } else {
            $api_key = $METHOD['api_key'];
        }
        if (!$_api['check'][$api_key]) {
            return new class() extends atlaASQuery {
                public $data = ['api_key' => 'wrong key'];
                public $count = 0;
            };
        } elseif (isset($_api['check'][$api_key]) && $_api['check'][$api_key]['status'] != 'active') {
            return new class() extends atlaASQuery {
                public $data = ['api_key' => 'key status is not active'];
                public $count = 0;
            };
        }
        $stmt = $pdo->prepare($sql_query);
        if ($bind !== null) {
            foreach ($bind as $parameter => $data_s) {
                if (isset($data_s[0])) {
                    $pdo_param_type = $data_s[0];
                } else {
                    $pdo_param_type = PDO::PARAM_STR;
                }
                if (isset($data_s[1])) {
                    $value = $data_s[1];
                } else {
                    $value = $METHOD[$parameter];
                }
                if (\str_starts_with($parameter, 'hash_')) {
                    $hashed =  $hasher->password_generate($value);
                    $stmt->bindValue(":$parameter", $hashed, $pdo_param_type);
                } else {
                    $stmt->bindValue(":$parameter", $value, $pdo_param_type);
                }
            }
        }
        $stmt->execute();
        $result = self::normalize_array($stmt);
        $stmt->closeCursor();
        self::connection_close($connection);
        return $result;
    }
}
