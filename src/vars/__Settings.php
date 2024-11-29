<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\hasSetGlobal;

/**
 * @see
 * - this class is a [global singelton](#globals);
 * - this class is a [setting class]($setting_class);
 */
abstract class __Settings {
    public string $_ENV_conn_name = '_CONN';
    public string $client_reroute_key = 'reroute';

    public string $routes_path = 'routes';
    public string $routes_class = 'Routes';
    public string $routes_errors_prefix = '/errors/';

    private  string $middleware_name = 'mw';
    public function middleware_name() {
        return $this->middleware_name;
    }

    public int $chunk_sizes = 204_800;
    public string $app_log = 'app_log';
    public int $refresh_micro_second = 500_000;
    public bool $load_file_with_php_require = false;
    public array $system_file = ['php'];
    public bool $use_stream = true;

    public function server_ip() {
        return @$_SERVER['SERVER_ADDR'];
    }
    public function system_path(string $path): string {
        return str_replace(['/', '\\',], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR,], $path);
    }
    public function use_caching(): array {
        return [$this->if_in_production(true, false), 60/* days */];
    }
    public function if_in_production(bool $in_production_value, bool $not_in_production_value): bool {
        return __Env::$__->is_in_production ? $in_production_value : $not_in_production_value;
    }

    use hasSetGlobal;
    public static __Settings|null $__ = null;

    public function __construct() {
        if (self::$__ !== null) {
            return;
        }
        $this->set_as_global();
    }
}
