<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppEnv_ {
    public static bool $is_in_production = false;

    public static string $app_key = 'APP_@#$@%#$%#$%$%_KEY';
    public static string $default_connection = 'site';
    public static array $conn = [
        'http' => [
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
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => 'atlaas_htmx',
                'type' => 'mysql',
                'file_name' => '',
                'encoding' => '',
            ]
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
    public static $api = [
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
