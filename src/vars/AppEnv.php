<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppEnv {
    public static $is_in_production = false;

    public static $db_driver = 'mysql';
    public static $db_host = 'localhost';
    public static $db_database = 'db_name';
    public static $db_username = 'root';
    public static $db_password = '';
}
