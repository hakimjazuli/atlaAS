<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

class Hasher {
    public function password_generate(string $value): string {
        return password_hash(App_::$app->app_env::$app_key . $value, PASSWORD_DEFAULT);
    }
    public function password_check(string $value, string $dbpassword): bool {
        return password_verify(App_::$app->app_env::$app_key . $value, $dbpassword);
    }
    public function generate_token(): string {
        $token_handler = App_::$app->app_env::$app_key . \random_bytes(32);
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
            App_::$app->reroute_error(403);
        }
    }
    private function csrf_compare(string $key) {
        $csrf = "csrf_$key";
        if (($stored_token = isset($_SESSION[$csrf]) ? $_SESSION[$csrf] : null) === null) {
            return false;
        }
        $METHOD = App_::$app->request->generate_query_param();
        if (($submitted_token = isset($METHOD[$csrf]) ? $METHOD[$csrf] : null) === null) {
            return false;
        }
        $is_equal = hash_equals($stored_token, $submitted_token);
        unset($_SESSION[$csrf]);
        return $is_equal;
    }
}
