<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\Utils\_GlobalVar;
use HtmlFirst\atlaAS\Vars\__Settings;

class _Is extends _GlobalVar {
    protected static string $global = 'is';
    public static function atlaAS_client_request(): false|string {
        $atlaAS_client_request_header = \strtoupper('HTTP_' . __Settings::$atlaAS_client_request_header);
        if (isset($_SERVER[$atlaAS_client_request_header])) {
            return self::global($atlaAS_client_request_header, $_SERVER[$atlaAS_client_request_header]);
        }
        return false;
    }
}
