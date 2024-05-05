<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\__App;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\__Response;
use HtmlFirst\atlaAS\Utils\Hasher;
use HtmlFirst\atlaAS\Vars\__Env;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;
use PDOException;
use PDOStatement;

class Conn {
    private static function normalize_array(PDOStatement $stmts): atlaASQuery_ {
        return new class($stmts) extends atlaASQuery_ {
            public $data;
            public $count;
            public function __construct(PDOStatement $stmts) {
                $this->data = $stmts->fetchAll(PDO::FETCH_OBJ);
                $this->count = \count($this->data);
            }
        };
    }
    private function connection_start(string $mode) {
        if (!isset($_ENV[$conn = __Settings::$_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = self::connect($mode);
        }
    }
    private function connection_close(string $mode) {
        if (isset($_ENV[$conn = __Settings::$_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = null;
        }
    }
    private function connect(string $mode) {
        $conn_ = __Env::$conn;
        $httpmode = __Request::$__->http_mode;
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
    private function get_api_key($METHOD) {
        if ($_SERVER['REMOTE_ADDR'] === __Settings::server_ip()) {
            return __App::$__->get_api_key();
        }
        return $METHOD['api_key'];
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
     *    $field_name => [?PDO::PARAM_type, ?$value],
     *  ]
     * >- in case of key <string> $field_name starts with 'hash_':
     * >>-the value will be hashed before being executed;
     * >- to save the param type and regex for client and server validation:
     * >>- consider extending our \HtmlFirst\atlaAS\Connection\Table_ for each table you have;
     * @param bool $check_csrf = false
     * @return atlaASQuery_
     */
    public function sql_query(
        string $sql_relative_path,
        string|null $csrf_key = null,
        string|null $connection = null,
        array|null $bind = null,
        bool $check_csrf = true
    ): atlaASQuery_ {
        if (!\is_file($sql_relative_path = __Settings::system_path(
            __App::$__->app_root . '/' . __Settings::$sqls_path . '/' . $sql_relative_path
        ))) {
            __App::$__->set_error_header(500);
            __Response::header_json();
            return new class() extends atlaASQuery_ {
                public $data = [
                    ['sql_file' => 'not found']
                ];
                public $count = 0;
            };
        }
        $method = __Request::$__->method;
        $METHOD = __Request::$__->method_params($method);
        $_api = __Env::$api;
        $api_key = $this->get_api_key($METHOD);
        if (!$_api['check'][$api_key]) {
            __App::$__->set_error_header(403);
            __Response::header_json();
            return new class() extends atlaASQuery_ {
                public $data = [
                    ['api_key' => 'wrong key']
                ];
                public $count = 0;
            };
        } elseif (isset($_api['check'][$api_key]) && $_api['check'][$api_key]['status'] != 'active') {
            __App::$__->set_error_header(403);
            __Response::header_json();
            return new class() extends atlaASQuery_ {
                public $data = [
                    ['api_key' => 'key status is not active']
                ];
                public $count = 0;
            };
        }
        $hasher = new Hasher();
        if (($method !== 'get' || $csrf_key) && $check_csrf) {
            $hasher->csrf_check($csrf_key);
        }
        $connection = $connection ?? __Env::$connections[0];
        $pdo = self::connection_start($connection);
        $stmt = $pdo->prepare(
            \file_get_contents($sql_relative_path)
        );
        if ($bind) {
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
