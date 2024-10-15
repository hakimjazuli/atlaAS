<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\__Response;
use HtmlFirst\atlaAS\Utils\_Hasher;
use HtmlFirst\atlaAS\Vars\__Env;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;
use PDOStatement;

abstract class _Query {
    private static function get_api_key($METHOD) {
        if ($_SERVER['REMOTE_ADDR'] === __Settings::$__->server_ip()) {
            return __atlaAS::$__->get_api_key();
        }
        return $METHOD['api_key'];
    }
    private static function normalize_query_return(PDOStatement $stmts): _atlaASQuery {
        return new class($stmts) extends _atlaASQuery {
            public $data;
            public $count;
            public function __construct(PDOStatement $stmts) {
                $this->data = $stmts->fetchAll(PDO::FETCH_OBJ);
                $this->count = \count($this->data);
            }
        };
    }
    /**
     * sql_query
     * 
     * @param string $sql_path
     * starts with '/';
     * @param string|null $csrf_key -descriptive
     * @param string|null $connection
     * - string: chose from env;
     * - null: default from env;
     * @param array|null $bind
     * - null: do nothing;
     * - [
     *    ...
     *    $field_name => [?PDO::PARAM_type, ?$value],
     *  ]
     * >- in case of key <string> $field_name starts with 'hash_':
     * >>-the value will be hashed before being executed;
     * >- to save the param type and regex for client and server validation:
     * >>- consider extending our \HtmlFirst\atlaAS\Connection\Table_ for each table you have;
     * @param bool $check_csrf
     * @param string $binder_character
     * - bind query string that are start with the $binder_character;
     * @return _atlaASQuery
     */
    protected static function sql_query(
        string $sql_path,
        string|null $csrf_key = null,
        string|null $connection = null,
        array|null $bind = null,
        bool $check_csrf = true,
        string $binder_character = ':'
    ): _atlaASQuery {
        if (!\is_file($sql_path = __Settings::$__->system_path(
            __atlaAS::$__->app_root . $sql_path
        ))) {
            __atlaAS::$__->set_error_header(500);
            __Response::header_json();
            return new class() extends _atlaASQuery {
                public $data = [
                    ['sql_file' => 'not found']
                ];
                public $count = 0;
            };
        }
        $METHOD = __Request::method_params();
        $_api = __Env::$__->api;
        $api_key = self::get_api_key($METHOD);
        if (!$_api['check'][$api_key]) {
            __atlaAS::$__->set_error_header(403);
            __Response::header_json();
            return new class() extends _atlaASQuery {
                public $data = [
                    ['api_key' => 'wrong key']
                ];
                public $count = 0;
            };
        } elseif (isset($_api['check'][$api_key]) && $_api['check'][$api_key] != 'active') {
            __atlaAS::$__->set_error_header(403);
            __Response::header_json();
            return new class() extends _atlaASQuery {
                public $data = [
                    ['api_key' => 'key status is not active']
                ];
                public $count = 0;
            };
        }
        if ((__Request::$method !== 'get' || $csrf_key) && $check_csrf) {
            _Hasher::csrf_check($csrf_key);
        }
        $connection ??= __Env::$__->preffered_connection;
        $pdo = Conn::connection_start($connection);
        try {
            $stmt = $pdo->prepare(
                \file_get_contents($sql_path)
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
                        $hashed = _Hasher::password_generate($value);
                        $stmt->bindValue("$binder_character$parameter", $hashed, $pdo_param_type);
                    } else {
                        $stmt->bindValue("$binder_character$parameter", $value, $pdo_param_type);
                    }
                }
            }
            $stmt->execute();
        } catch (\Throwable $e) {
            if ($e->getCode() == 23000) {
                return new class extends _atlaASQuery {
                    public $data = [
                        ['error' => 'unique field have duplicate(s)']
                    ];
                    public $count = 0;
                };
            }
            throw $e;
        }
        $result = self::normalize_query_return($stmt);
        $stmt->closeCursor();
        Conn::connection_close($connection);
        return $result;
    }
}
