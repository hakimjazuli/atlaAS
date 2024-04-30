<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Middleware\FSMiddleware;

class FSRouter extends FSMiddleware {
    public function __construct(private App $app) {
        new parent($app);
    }
    public function run() {
        $this->current_folder = $this->app->app_root . \DIRECTORY_SEPARATOR . $this->app->app_settings->routes_path;
        $this->current_route = $this->app->app_settings->routes_class;
        foreach ($this->app->request->uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . $this->app->app_settings->middleware_name;
            $this->check_mw();
            $this->current_route .= '\\' . $uri;
            if ($this->check_route()) {
            } elseif (!$this->is_folder_exist()) {
                break;
            }
        }
        if (!$this->real_route) {
            return;
        }
        $this->run_real_route();
    }
    private string $current_route;
    private object|string|false $real_route = false;
    private function check_route(): bool {
        if (!\class_exists($this->current_route)) {
            return false;
        }
        $this->real_route = $this->current_route;
        return true;
    }
    private function run_real_route() {
        $this->check_common_middleware_exist_in_route();
        $this->check_method_middleware_exist_in_route();
        if (\method_exists(
            $class = $this->real_route,
            $method = $this->app->request->method
        )) {
            $class = new $class();
            $class->$method();
        }
    }
    private function check_common_middleware_exist_in_route(): void {
        if (\method_exists(
            $class = $this->real_route,
            $mw = $this->app->app_settings->middleware_name
        )) {
            $class = new $class();
            $class->$mw();
        };
    }
    private function check_method_middleware_exist_in_route(): void {
        if (\method_exists(
            $class = $this->real_route,
            $mw = $this->app->app_settings->middleware_name . '_' . $this->app->request->method
        )) {
            $class = new $class();
            $class->$mw();
        };
    }
}
