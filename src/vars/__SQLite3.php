<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\__Response;
use HtmlFirst\atlaAS\Utils\_FileServer;
use HtmlFirst\atlaAS\Vars\__Settings;
use HtmlFirst\atlaAS\Utils\hasSetGlobal;
use PDO;

/**
 * @see
 * - this class is a [global singelton](#globals);
 * - this class is a [setting class]($setting_class);
 */
abstract class __SQLite3 {
    use hasSetGlobal;

    /**
     * overwrite this value in the extended class
     */
    protected string $db_path = 'backend/db/atlaas-internals.sqlite3';
    protected int $session_timeout = 1;
    protected int $log_db_valid_length = 1;
    /**
     * overwrite this value in the extended class
     */

    public static __SQLite3|null $__ = null;

    public function __construct() {
        if (self::$__ !== null) {
            return;
        }
        $this->set_as_global();
    }

    private string|null $db_file = null;
    private string|null $dir_name = null;

    private function db(): PDO {
        if (!$this->db_file) {
            $this->db_file =  __Settings::$__->system_path(__atlaAS::$__->app_root . \DIRECTORY_SEPARATOR . $this->db_path);
        }
        if (!$this->dir_name) {
            $this->dir_name = \dirname($this->db_file);
        }
        if (!\is_dir($this->dir_name)) {
            \mkdir($this->dir_name, 0755, true);
        }
        $dsn = "sqlite:" . $this->db_file;
        try {
            return new PDO($dsn);
        } catch (\Throwable $e) {
            $message = [
                "message" => "Error opening database",
                $e,
            ];
            \print_r(\json_encode($message));
            _FileServer::log('error opening db',  $message);
            die;
        }
    }

    private function sql_file(string $sql_name): string {
        return \file_get_contents(
            __DIR__ . "/../../sqls/" . $sql_name
            /**
         * intentional left out the extention,
         * so no mental overhead of what file the strin is passed
         */
        );
    }

    public function log(string $context, array $message) {
        __SQLite3::check_valid_call('log');
        $pdo = $this->db();
        $succeed = $pdo->exec($this->sql_file('log_tbl_create.sql'));
        if ($succeed === false) {
            _FileServer::log('unable to create tbl', [
                'message' => 'unable to exec',
                'file' => 'log_tbl_create.sql',
            ]);
            die;
        }
        $succeed = $pdo->exec($this->sql_file('log_invalid_delete.sql'));
        if ($succeed === false) {
            _FileServer::log('unable to delete records', [
                'message' => 'unable to exec',
                'file' => 'log_invalid_delete.sql',
            ]);
            die;
        }
        $stmt = $pdo->prepare($this->sql_file('log_insert.sql'));
        $current_time = time();
        $succeed = $stmt->execute([
            ':context' => $context,
            ':time_stamp' => $current_time,
            ':valid_until' =>  $current_time + $this->log_db_valid_length,
            ':message' => \json_encode($message),
        ]);
        if (!$succeed) {
            _FileServer::log('unable to insert records', [
                'message' => 'unable to exec',
                'file' => 'log_insert.sql',
            ]);
            die;
        }
    }

    public function archive(string $db_path = 'default', bool $delete_then_recreate = false) {
        __SQLite3::check_valid_call('archive');
        $pdo = $this->db();
        if ($db_path === 'default') {
            $db_path = $this->db_path;
        }
        $leading = __atlaAS::$__->app_root;
        if (!str_starts_with($db_path, $leading)) {
            $db_path = $leading . \DIRECTORY_SEPARATOR . $db_path;
        }
        $stmt = $pdo->prepare($this->sql_file('archive.sql'));
        $succeed = $stmt->execute([
            ':archived_path' => $db_path,
        ]);
        if (!$succeed) {
            __atlaAS::print_and_log('unable to archive', [
                'message' => 'unable to archive',
                'file' => 'archive.sql'
            ]);
            $pdo = null;
            die;
        }
        $pdo = null;
        if (!$delete_then_recreate) {
            return;
        }
        $succeed = \unlink($this->db_file);
        if (!$succeed) {
            _FileServer::log('unable to delete sqlite database', [
                'message' => 'unable to delete sqlite database',
                'file_path' => $this->db_file,
            ]);
            die;
        }
        $pdo = $this->db();
        $pdo = null;
    }

    /**
     * @param  string $dbPath
     * @param  float $rate_limit
     * @param  float $time_window : in seconds
     * @return void
     */
    public function limit(float $rate_limit = 100, float $time_window = 60, string|null $client_id = null): void {
        __SQLite3::check_valid_call('limit');
        $pdo = $this->db();
        $client_id = $client_id ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
        $succeed = $pdo->exec($this->sql_file('rate_limits_tbl_create.sql'));
        if ($succeed === false) {
            __atlaAS::print_and_log(
                '$pdo->exec($this->atlaAS_sql_file("rate_limits_tbl_create.sql"));',
                [
                    'message' => 'unable to run query',
                    'file name' => 'rate_limits_tbl_create.sql',
                ]
            );
            $pdo = null;
            die;
        }
        $currentTime = time();
        $windowStart = intval($currentTime / $time_window) * $time_window;
        $stmt = $pdo->prepare($this->sql_file('rate_limites_row_get.sql'));
        $succeed = $stmt->execute([
            ':client_id' => $client_id,
            ':window_start' => $windowStart,
        ]);
        if (!$succeed) {
            __atlaAS::print_and_log('error to read db', [
                'message' => 'errror to execute sql',
                'file' => 'rate_limites_row_get.sql',
            ]);
            $pdo = null;
            die;
        }
        $row_rate_limit = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row_rate_limit) {
            $stmt = $pdo->prepare($this->sql_file('rate_limits_insert.sql'));
            $succeed = $stmt->execute([
                ':client_id' => $client_id,
                ':window_start' => $windowStart
            ]);
            if (!$succeed) {
                __atlaAS::print_and_log(
                    'error_to_execute_db',
                    [
                        'message' => 'errror to execute statement',
                        'file' => 'rate_limits_insert.sql',
                    ]
                );
                $pdo = null;
                die;
            }
            return;
        }
        if ($row_rate_limit['request_count'] >= $rate_limit) {
            http_response_code(429);
            __Response::echo_json([
                'code' => 429,
                'status' => 'not enough resource',
                'message' => 'try again later'
            ]);
            $pdo = null;
            die;
        }
        $stmt = $pdo->prepare($this->sql_file('rate_limits_delete_client_id.sql'));
        $succeed = $stmt->execute([
            ':client_id' => $client_id,
            ':window_start' => $windowStart
        ]);
        if (!$succeed) {
            __atlaAS::print_and_log(
                'error_to_execute_db',
                [
                    'message' => 'errror to execute statement',
                    'file' => 'rate_limits_delete_client_id.sql',
                ]
            );
            $pdo = null;
            die;
        }
        $stmt = $pdo->prepare($this->sql_file('rate_limites_updates.sql'));
        $succeed = $stmt->execute([
            ':client_id' => $client_id,
            ':window_start' => $windowStart
        ]);
        if (!$succeed) {
            __atlaAS::print_and_log(
                'error execute stmt',
                [
                    'message' => 'errror to execute statement',
                    'file' => 'rate_limites_updates.sql',
                ]
            );
            $pdo = null;
            die;
        }
    }
    private static function check_valid_call($context) {
        if (__SQLite3::$__) {
            return;
        }
        __atlaAS::$__::print_and_log('db-call-invalid', [
            'message' => 'invalid call to __SQLite3 instance method, while not instantiated',
            'context' => $context,
        ]);
        die;
    }
}
