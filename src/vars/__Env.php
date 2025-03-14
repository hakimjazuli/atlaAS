<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\hasSetGlobal;
use PDO;

/**
 * @see
 * - this class is a [global singelton](#globals);
 * - this class is a [setting class]($setting_class);
 * - overwrite this  `public function pdo`;
 */
abstract class __Env {
    /**
     * - overwirite this method;
     * - both params are helpers to determine which pdo connection to chose;
     * - you need to add public method on the extended class that returns PDO instances;
     * @param bool $is_https
     * @param string $connection_mode
     */
    public function pdo(bool $is_https, string $connection_mode): PDO {
        try {
            exit("Connection failed!: overwrite '__Env->pdo' with documented type");
        } catch (\Throwable $e) {
            exit("Connection failed!: " . $e->getMessage() . "<br/>");
        }
    }

    public int $cors_cache = 86400;
    public bool $is_in_production = false;

    public string $app_key = 'APP_@#$@%#$%#$%$%_KEY';
    public string $preffered_connection = 'app';
    public $api = [
        /**
         * array key of the [0] index of 'check' is your api_key;
         * make sure it is 'active';
         */
        'check' => [
            'your#$@#@$%#$%KEY' => 'active',
        ]
    ];

    use hasSetGlobal;
    public static __Env|null $__ = null;

    public string $public_path;
    public function __construct() {
        if (static::$__ !== null) {
            return;
        }
        $this->set_as_global();
    }
}
