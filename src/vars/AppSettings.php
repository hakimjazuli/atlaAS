<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppSettings {
    public function __construct(private AppEnv $app_env) {
    }

    public string $middleware_name = 'mw';
    public string $routes_path = 'routes';
    public string $routes_class = 'Routes';
    public string $routes_errors_prefix = '/errors/';

    public static int $chunk_sizes = 204_800;
    public static int $refresh_micro_second = 500_000;
    public static bool $load_file_with_php_require = false;
    public static string $system_file = 'php';
    public static bool $use_stream = true;

    public static function server_ip() {
        return @$_SERVER['SERVER_ADDR'];
    }
    public static function system_path(string $path): string {
        return str_replace(['/', '\\'], [\DIRECTORY_SEPARATOR, \DIRECTORY_SEPARATOR], $path);
    }
    public function use_caching() {
        return [$this->if_in_production(true, false), 60/* days */];
    }
    public function if_in_production(bool $in_production_value, bool $not_in_production_value): bool {
        return $this->app_env->is_in_production ? $in_production_value : $not_in_production_value;
    }
}
