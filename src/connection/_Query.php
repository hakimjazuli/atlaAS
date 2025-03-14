<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\connection\_Binder;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\__Response;
use HtmlFirst\atlaAS\Utils\_Hasher;
use HtmlFirst\atlaAS\Vars\__Env;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;
use PDOStatement;

/**
 * @see
 *-   query helper
 *
 *```php
 *<?php
 *
 *namespace Backend\Queries;
 *
 *use Backend\Tables\Test as TablesTest;
 *use HtmlFirst\atlaAS\Connection\_atlaASQuery;
 *use HtmlFirst\atlaAS\Connection\_Query;
 *
 *class Test extends _Query {
 *    public static function test_name_like(string $test_name): _atlaASQuery {
 *        $test = new TablesTest;
 *        return self::sql_query('/sql/views/test.sql', bind: [
 *            ... new HtmlFirst\atlaAS\connection\_Binder(...$args),
 *        ]);
 *    }
 *}
 *```
 *
 *-   setting up `/sql/views/test.sql`
 *
 *```sql
 *SELECT
 *	`id`,
 *    test.test_name
 *FROM
 *    test
 *WHERE
 *    `test_name` LIKE :test_name;
 *```
 */
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
     * - this query helper doesn't have built-in race condition prevention, you may add it in your query file;
     * @param string $sql_path
     * starts with '/';
     * @param string|null $csrf_key -descriptive
     * @param string|null $connection
     * - string: chose from env;
     * - null: default from env;
     * @param array|null $bind
     * - null: do nothing;
     * - [ ...HtmlFirst\atlaAS\connection\_Binder instance]: bind the values to query;
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
            $real_query_job = \file_get_contents($sql_path);
            $stmt = $pdo->prepare($real_query_job);
            if ($bind) {
                foreach ($bind as $binder) {
                    if (!($binder instanceof _Binder)) {
                        throw ['$bind' => 'is not array of instanceof _Binder'];
                    }
                    $field_name = $binder->incoming_parameter_name;
                    $pdo_param_type = $binder->pdo_param_type;
                    $value = match ($binder->value) {
                        null => $METHOD[$field_name],
                        default => $binder->value,
                    };
                    $stmt->bindValue("$binder_character$field_name", $value, $pdo_param_type);
                }
            }
            $stmt->execute();
            $result = self::normalize_query_return($stmt);
            $stmt->closeCursor();
            Conn::connection_close($connection);
            return $result;
        } catch (\Throwable $error) {
            return new class($error) extends _atlaASQuery {
                public $data = [];
                public $count = 0;
                public function __construct(\Throwable $error) {
                    $this->data = ['error' => $error];
                }
            };
        }
    }
}
