<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Vars\__Env;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;
use PDOException;

abstract class Conn {
    public static function connection_start(string $mode) {
        if (!isset($_ENV[$conn = __Settings::$_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = self::connect($mode);
        }
    }
    public static function connection_close(string $mode) {
        if (isset($_ENV[$conn = __Settings::$_ENV_conn_name][$mode])) {
            return $_ENV[$conn][$mode] = null;
        }
    }
    public static function connect(string $mode) {
        $conn_ = __Env::$conn;
        $httpmode = __Request::$http_mode;
        $host = $conn_[$httpmode][$mode]['host'];
        $username = $conn_[$httpmode][$mode]['username'];
        $password = $conn_[$httpmode][$mode]['password'];
        $db = $conn_[$httpmode][$mode]['db'];
        $type = $conn_[$httpmode][$mode]['type'];
        $filename = $conn_[$httpmode][$mode]['file_name'];
        $encoding = $conn_[$httpmode][$mode]['encoding'];
        try {
            $filename = __Settings::system_path(__atlaAS::$app_root . '/' . $filename);
            $conn = match ($type) {
                'mdb', 'accdb' => new PDO('odbc:DRIVER={Microsoft Access Driver (*.mdb, *.accdb)};charset=' . $encoding . '; DBQ=' . $filename . '; Uid=' . $username . '; Pwd=' . $password . ';'),
                'sqlite' => new PDO($type . ':' . $filename),
                default => new PDO($type . ':host=' . $host . ';dbname=' . $db, $username, $password),
            };
            $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, true);
        } catch (PDOException $e) {
            exit("Connection failed!: " . $e->getMessage() . "<br/>");
        }
        return $conn;
    }
}
