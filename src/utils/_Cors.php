<?php

namespace HtmlFirst\atlaAS\Utils;

/**
 * @see
 * - contains static method(s) to handle cors policy;
 */
class _Cors {
    /**
     * __construct
     * @param array $allowed_origins 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param array $allowed_methods 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param array  $allowed_headers 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param float  $max_age
     * - in days
     * @return void
     */
    public static function allow(
        array $allowed_origins = [],
        array $allowed_methods = [],
        array $allowed_headers = [],
        float $max_age = 1,
    ) {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if (in_array('*', $allowed_origins)) {
            header("Access-Control-Allow-Origin: *");
        } elseif (in_array($origin, $allowed_origins)) {
            header("Access-Control-Allow-Origin: " . $origin);
        }
        if (in_array('*', $allowed_methods)) {
            header("Access-Control-Allow-Methods: *");
        } elseif (!empty($allowed_methods)) {
            header("Access-Control-Allow-Methods: " . implode(", ", $allowed_methods));
        }
        if (in_array('*', $allowed_headers)) {
            header("Access-Control-Allow-Headers: *");
        } elseif (!empty($allowed_headers)) {
            header("Access-Control-Allow-Headers: " . implode(", ", $allowed_headers));
        }
        if (__Request::$method == 'options') {
            if (in_array('*', $allowed_methods)) {
                header("Access-Control-Allow-Methods: *");
            } elseif (!empty($allowed_methods)) {
                header("Access-Control-Allow-Methods: " . implode(", ", $allowed_methods));
            }
            if (in_array('*', $allowed_headers)) {
                header("Access-Control-Allow-Headers: *");
            } elseif (!empty($allowed_headers)) {
                header("Access-Control-Allow-Headers: " . implode(", ", $allowed_headers));
            }
            header('Access-Control-Max-Age: ' . $max_age * 86400);
            exit(0);
        }
    }
}
