<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Vars\__Env;

/**
 * @see
 * collection of static methods for hashing purposes;
 * - html_csrf_element: for generating string element of csrf;
 * 
 */
abstract class _Hasher {
    public static function generate_hash(string $value): string {
        return password_hash(__Env::$__->app_key . $value, PASSWORD_DEFAULT);
    }
    public static function check_hash(string $value, string $dbpassword): bool {
        return password_verify(__Env::$__->app_key . $value, $dbpassword);
    }
    public static function generate_token(): string {
        $token_handler = __Env::$__->app_key . \random_bytes(32);
        $token_handler = \unpack('H*', $token_handler)[1];
        $token_handler = \str_shuffle($token_handler);
        return \substr($token_handler, 0, 75);
    }
    public static function html_csrf_element(string $key) {
        $token = self::generate_csrf_token($key);
        return "<input type=\"hidden\" name=\"csrf_$key\" value=\"$token\">";
    }
    private static function generate_csrf_token(string $key) {
        if (isset($_SESSION["csrf_$key"])) {
            return $_SESSION["csrf_$key"];
        }
        return $_SESSION["csrf_$key"] = self::generate_token();
    }
    public static function csrf_check(string $key): void {
        if (self::csrf_compare($key)) {
            return;
        }
        __atlaAS::$__->reroute_error(403);
    }
    private static function csrf_compare(string $key): bool {
        $csrf = "csrf_$key";
        if (($stored_token = isset($_SESSION[$csrf]) ? $_SESSION[$csrf] : null) === null) {
            return false;
        }
        $METHOD = __Request::method_params();
        if (($submitted_token = isset($METHOD[$csrf]) ? $METHOD[$csrf] : null) === null) {
            return false;
        }
        $is_equal = hash_equals($stored_token, $submitted_token);
        unset($_SESSION[$csrf]);
        return $is_equal;
    }
}
