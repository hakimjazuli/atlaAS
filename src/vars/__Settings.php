<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\hasSetGlobal;

abstract class __Settings {
    public static string $_ENV_conn_name = '_CONN';
    public static string $client_reroute_key = 'reroute';
    public static function atlaAS_client_request_header() {
        return __Request::valid_request_header('atlaAS_client_from');
    }

    public static string $routes_path = 'routes';
    public static string $routes_class = 'Routes';
    public static string $routes_errors_prefix = '/errors/';

    private static string $middleware_name = 'mw';
    public static function middleware_name() {
        return self::$middleware_name;
    }

    public static int $chunk_sizes = 204_800;
    public static int $refresh_micro_second = 500_000;
    public static bool $load_file_with_php_require = false;
    public static array $system_file = ['php'];
    public static bool $use_stream = true;

    public static function server_ip() {
        return @$_SERVER['SERVER_ADDR'];
    }
    public static function system_path(string $path): string {
        return str_replace(['/', '\\',], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR,], $path);
    }
    public static function use_caching(): array {
        return [self::$__::if_in_production(true, false), 60/* days */];
    }
    public static function if_in_production(bool $in_production_value, bool $not_in_production_value): bool {
        return __Env::$is_in_production ? $in_production_value : $not_in_production_value;
    }

    use hasSetGlobal;
    protected static __Settings|null $__ = null;

    public function __construct() {
        if (static::$__ !== null) {
            return;
        }
        $this->set_as_global();
    }
}
