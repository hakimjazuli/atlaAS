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
    public static array $api = [
        'key' => '67T76R5o84Dc868767frgO74d5EVRiu653',
        'check' => [
            '67T76R5o84Dc868767frgO74d5EVRiu653' => [
                'status' => 'active',
                'assign_to' => 'app_main_key'
            ],
            '56srt0AETLKB94I09ATUBR4L6brABT9687' => [
                'status' => 'pending',
                'assign_to' => 'app_test_1'
            ],
            '27e54KJBFKJCEs4fn124q32adf5E456f47' => [
                'status' => 'suspended',
                'assign_to' => 'app_test_2'
            ],
        ]
    ];
}
