<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\Router\_Routes;
use HtmlFirst\atlaAS\Utils\_GlobalVar;
use HtmlFirst\atlaAS\Vars\__Settings;

class _Is extends _GlobalVar {
    protected static string $global = 'is';
    public static function atlaAS_client_request(_Routes $_routes): false|string {
        if (!$_routes->is_real_route) {
            return false;
        }
        $atlaAS_client_request_header = __Settings::$__->atlaAS_client_request_header();
        if (isset($_SERVER[$atlaAS_client_request_header])) {
            return self::global($atlaAS_client_request_header, $_SERVER[$atlaAS_client_request_header]);
        }
        return false;
    }
}
