<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppEnv {
    public static $is_in_production = false;

    public static $app_key = '4arrW44EN4T5YIYTytcyrbuAERbrIb5e48Y7Ro8y9WYEY8b69';

    public static string $default_connection = 'site';
    public static array $conn = [
        'http' => [
            'site' =>
            [
                'host' => 'localhost',
                'username' => 'root',
                'password' => '',
                'db' => '',
                'type' => 'mysql',
                'file_name' => '',
                'encoding' => '',
            ]
        ],
        'https' => [
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
}
