<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\hasSetGlobal;
use PDO;

abstract class __Env {
    public static bool $is_in_production = false;

    public static string $app_key = 'APP_@#$@%#$%#$%$%_KEY';
    public static string $preffered_connection = 'app';
    /**
     * both params are helpers to determine which pdo connection to chose
     * @param bool $is_https
     * @param string $connection_mode
     */
    public static function pdo(bool $is_https, string $connection_mode): PDO {
        try {
            return static::$__::pdo($is_https, $connection_mode);
        } catch (\Throwable $e) {
            exit("Connection failed!: " . $e->getMessage() . "<br/>");
        }
    }
    public static $api = [
        /**
         * array key of the [0] index of 'check' is your api_key;
         * make sure it is 'active';
         */
        'check' => [
            'your#$@#@$%#$%KEY' => 'active',
        ]
    ];

    use hasSetGlobal;
    protected static __Env|null $__ = null;

    public function __construct() {
        if (static::$__ !== null) {
            return $this;
        }
        $this->set_as_global();
    }
}
