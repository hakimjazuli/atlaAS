<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__App;
use HtmlFirst\atlaAS\Vars\__Env;

class Hasher {
    public function password_generate(string $value): string {
        return password_hash(__Env::$app_key . $value, PASSWORD_DEFAULT);
    }
    public function password_check(string $value, string $dbpassword): bool {
        return password_verify(__Env::$app_key . $value, $dbpassword);
    }
    public function generate_token(): string {
        $token_handler = __Env::$app_key . \random_bytes(32);
        $token_handler = \unpack('H*', $token_handler)[1];
        $token_handler = \str_shuffle($token_handler);
        return \substr($token_handler, 0, 75);
    }
    public static function add_csrf_attribute(string $key) {
        $token = self::generate_csrf_token($key);
        return "a_token_name=\"$key\" a_token_val=\"$token\"";
    }
    public static function generate_csrf_token(string $key) {
        if (isset($_SESSION["csrf_$key"])) {
            return $_SESSION["csrf_$key"];
        } else {
            return $_SESSION["csrf_$key"] = self::generate_token();
        }
    }
    public function csrf_check(string $key): void {
        if (!self::csrf_compare($key)) {
            __App::$__->reroute_error(403);
        }
    }
    private function csrf_compare(string $key) {
        $csrf = "csrf_$key";
        if (($stored_token = isset($_SESSION[$csrf]) ? $_SESSION[$csrf] : null) === null) {
            return false;
        }
        $METHOD = __Request::$__->generate_query_param();
        if (($submitted_token = isset($METHOD[$csrf]) ? $METHOD[$csrf] : null) === null) {
            return false;
        }
        $is_equal = hash_equals($stored_token, $submitted_token);
        unset($_SESSION[$csrf]);
        return $is_equal;
    }
}
