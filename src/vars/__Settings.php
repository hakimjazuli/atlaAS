<?php

namespace HtmlFirst\atlaAS\Vars;

use HtmlFirst\atlaAS\Utils\hasSetGlobal;

abstract class __Settings {
    use hasSetGlobal;
    public static __Settings $__;

    public function __construct() {
        $this->set_as_global();
    }

    public string $_ENV_conn_name = '_CONN';

    public string $routes_path = 'routes';
    public string $sqls_path = 'sqls';
    public string $middleware_name = 'mw';
    public string $routes_class = 'Routes';
    public string $routes_errors_prefix = '/errors/';

    public int $chunk_sizes = 204_800;
    public int $refresh_micro_second = 500_000;
    public bool $load_file_with_php_require = false;
    public string $system_file = 'php';
    public bool $use_stream = true;

    public function server_ip() {
        return @$_SERVER['SERVER_ADDR'];
    }
    public function system_path(string $path): string {
        return str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);
    }
    public function use_caching(): array {
        return [$this->if_in_production(true, false), 60/* days */];
    }
    public function if_in_production(bool $in_production_value, bool $not_in_production_value): bool {
        return __Env::$__->is_in_production ? $in_production_value : $not_in_production_value;
    }
}
