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
    public static function connect(string $mode) {
        $conn_ = __Env::$__->conn;
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
}
