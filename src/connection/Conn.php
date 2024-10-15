<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Vars\__Env;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;
use PDOException;

abstract class Conn {
    public static function connection_start(string $mode) {
        if (!isset($_ENV[$conn = __Settings::$__->_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = self::connect($mode);
        }
    }
    public static function connection_close(string $mode) {
        if (isset($_ENV[$conn = __Settings::$__->_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = null;
        }
    }
    private static function connect(string $mode) {
        try {
            $conn = __Env::$__->pdo(__Request::$is_https, $mode);
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
            return $conn;
        } catch (PDOException $e) {
            exit("Connection failed!: " . $e->getMessage() . "<br/>");
        }
    }
}
