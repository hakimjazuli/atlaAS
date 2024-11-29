<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\Router\_Routes;
use HtmlFirst\atlaAS\Utils\_GlobalVar;

/**
 * @see
 * - an example to use [HtmlFirst\atlaAS\Utils\_GlobalVar](#htmlfirst_atlaas_utils__globalvar):
 * ```php
 * <[blank]?php
 * class _Is extends _GlobalVar {
 *
 *    protected static string $global_namespace = 'is';
 *
 *    public static function atlaAS_client_request(_Routes $_routes): false|string {
 *        if (!$_routes->is_real_route) {
 *            return false;
 *        }
 *        $atlaAS_client_request_header = __Request::valid_request_header('atlaAS_client_form'); // 1
 *        if (isset($_SERVER[$atlaAS_client_request_header])) {
 *            return self::global($atlaAS_client_request_header, $_SERVER[$atlaAS_client_request_header]); // 2
 *        }
 *        return false;
 *    }
 *}
 *
 * ```
 * - 1  generate valid http request header for `atlaAS_client_from` in this case `HTTP_ATLAAS_CLIENT_FORM`;
 * - 2  can be used to access (and assign at the same time) __atlaAS::$__::$global associative array, which then be used down the line of current request;
 */
class _Is extends _GlobalVar {

    protected static string $global_namespace = 'is';

    public static function atlaAS_client_request(_Routes $_routes): false|string {
        if (!$_routes->is_real_route) {
            return false;
        }
        $atlaAS_client_request_header = __Request::valid_request_header('atlaAS_client_from');
        if (isset($_SERVER[$atlaAS_client_request_header])) {
            return self::global($atlaAS_client_request_header, $_SERVER[$atlaAS_client_request_header]);
        }
        return false;
    }
}
