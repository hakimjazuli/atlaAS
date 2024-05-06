<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\hasSetGlobal;

abstract class __Env {
    use hasSetGlobal;
    public static __Env $__;

    public function __construct() {
        $this->set_as_global();
    }

    public bool $is_in_production = false;

    public string $app_key = 'APP_@#$@%#$%#$%$%_KEY';
    public array $connections = [
        'app',
        'site'
    ];
    public array $conn = [
        'http' => [
            'app' =>
            [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => 'atlaas_htmx',
                'type' => 'mysql',
                'file_name' => '',
                'encoding' => '',
            ],
            'site' =>
            [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => '',
                'type' => 'mysql',
                'file_name' => '',
                'encoding' => '',
            ],
        ],
        'https' => [
            'app' =>
            [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => '',
                'type' => 'mysql',
                'file_name' => '',
                'encoding' => '',
            ],
            'site' =>
            [
                'host' => '',
                'username' => '',
                'password' => '',
                'db' => '',
                'type' => '',
                'file_name' => '',
                'encoding' => '',
            ]
        ]
    ];
    public $api = [
        /**
         * array key of the [0] index of 'check' is your api_key;
         * make sure the 'status' is 'active';
         */
        'check' => [
            'your#$@#@$%#$%KEY' => [
                'status' => 'active',
            ],
        ]
    ];
}
