<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\Hasher;
use HtmlFirst\atlaAS\Utils\hasPrivateApp;
use PDO;
use PDOException;
use PDOStatement;

class Conn {
    use hasPrivateApp;
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
    private function connection_start(string $mode) {
        if (!isset($_ENV['_CONN'][$mode])) {
            return $_ENV['_CONN'][$mode] = self::connect($mode);
        }
    }
    private function connection_close(string $mode) {
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
     * sql_query
     * 
     * @param string $sql_relative_path
     * @param string|null $csrf_key -descriptive
     * @param string|null $connection
     * - string: chose from env;
     * - null: default from env;
     * @param array|null $bind
     * - null : do nothing;
     * - [
     *    ...
     *    'field_name'=>[?PDO::PARAM_type, ?$value],
     *]
     * @param bool $override_csrf = false
     * @return atlaASQuery
     */
    public function sql_query(
        string $sql_relative_path,
        string|null $csrf_key = null,
        string|null $connection = null,
        array|null $bind = null,
        bool $override_csrf = false
    ): atlaASQuery {
        if (!\is_file($sql_relative_path =
            $this->app->app_root . \DIRECTORY_SEPARATOR .
            $this->app->app_settings->sqls_path . \DIRECTORY_SEPARATOR .
            $sql_relative_path)) {
            $this->app->set_error_header(500);
            \header('Content-Type: application/json');
            \print_r(new class() extends atlaASQuery {
                public $data = ['sql_file' => 'not found'];
                public $count = 0;
            });
        }
        $method = $this->app->request->method;
        $METHOD = $this->app->request->method_params($method);
        $_api = $this->app->app_env::$api;
        if ($_SERVER['REMOTE_ADDR'] === $this->app->app_settings::server_ip()) {
            $api_key = $_api['key'];
        } else {
            $api_key = $METHOD['api_key'];
        }
        if (!$_api['check'][$api_key]) {
            $this->app->set_error_header(403);
            \header('Content-Type: application/json');
            \print_r(new class() extends atlaASQuery {
                public $data = ['api_key' => 'wrong key'];
                public $count = 0;
            });
        } elseif (isset($_api['check'][$api_key]) && $_api['check'][$api_key]['status'] != 'active') {
            $this->app->set_error_header(403);
            \header('Content-Type: application/json');
            \print_r(new class() extends atlaASQuery {
                public $data = ['api_key' => 'key status is not active'];
                public $count = 0;
            });
        }
        $hasher = new Hasher($this->app);
        if (($method !== 'get' || $csrf_key !== null) && !$override_csrf) {
            $hasher->csrf_check($csrf_key);
        }
        $connection = $connection ?? $this->app->app_env::$default_connection;
        $pdo = self::connection_start($connection);
        $stmt = $pdo->prepare(
            \file_get_contents($sql_relative_path)
        );
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
                    $hashed = $hasher->password_generate($value);
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
