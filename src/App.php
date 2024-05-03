<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\AppEnv;

class App {
    public Request $request;
    public string $app_root;
    public string $public_url_root;
    public AppEnv $app_env;
    public AppSettings $app_settings;
    public function __construct(string $app_env_ref, string $app_settings_ref) {
        $this->app_env = new $app_env_ref;
        $this->app_settings = new $app_settings_ref($this->app_env);
        $this->request = new Request;
        $this->app_root = \dirname($this->request->public_path);
        $this->public_url_root = $this->request->http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
    }
    public function render_get(null|array $url = null, null|array $query_parameter = null) {
        $url = $url ?? $this->request->uri_array;
        if ($query_parameter !== null) {
            $this->request->generate_query_param(
                $query_parameter,
                $this->fs_router->route_from_path(\join($url))
            );
        }
        $this->fs_router->request_length = \count($url);
        $routes_length = 0;
        foreach ($url as $uri) {
            $this->fs_router->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->fs_router->current_middleware = $this->fs_router->current_route . '\\' . $this->app_settings->middleware_name;
            $this->fs_router->check_mw();
            $routes_length++;
            $this->fs_router->current_route .= '\\' . $uri;
            if ($this->fs_router->check_route()) {
                $this->fs_router->routes_length = $routes_length;
            } elseif (!$this->fs_router->is_folder_exist()) {
                break;
            }
        }
    }
    private FSRouter $fs_router;
    public function run(): void {
        $this->fs_router = new FSRouter($this);
        $this->fs_router->run();
    }
    public static function reroute(string $path): void {
        \header("location: $path");
        exit(0);
    }
    public static function set_error_header(int $code = 404): void {
        switch ($code) {
            case 403:
                \header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                \header("HTTP/1.0 404 Not Found");
                break;
            case 500:
                \header("HTTP/1.0 500 Internal Server Error");
                break;
        }
    }
    public function reroute_error(int $code = 404): void {
        switch ($code) {
            case 403:
            case 404:
            case 500:
                break;
            default:
                $code = 404;
                break;
        }
        self::set_error_header($code);
        self::reroute($this->app_settings->routes_errors_prefix . $code);
    }
}
