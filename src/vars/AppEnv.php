<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppEnv {
    public static $is_in_production = false;

    public static $app_key = '4arrW44EN4T5YIYTytcyrbuAERbrIb5e48Y7Ro8y9WYEY8b69';
    public static $default_connection = 'site';
    public static $conn = [
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
    public static $api = [
        'key' => 'c9b87345734563vb7UAER86bTEx46wIOu65Ebgvdf4tvy487',
        'check' => [
            'c9b87345734563vb7UAER86bTEx46wIOu65Ebgvdf4tvy487' => [
                'status' => 'active',
                'assign_to' => 'app_main_key'
            ],
        ]
    ];
}
