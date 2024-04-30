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
        $this->current_route = "\\Routes";
        foreach ($this->app->request->uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . $this->app->app_settings->middleware_name;
            $this->check_mw();
            $this->current_route .= '\\' . $uri;
            if ($this->check_route()) {
            } elseif ($this->is_folder_exist()) {
            } else {
                break;
            }
        }
    }
    private string $current_route;
    private string $real_route;
    private function check_route(): bool {
        if (!\class_exists($this->current_route)) {
            return false;
        }
        $this->real_route = $this->current_route;
        return true;
    }
    private function run_real_route() {
    }
    // private function check_common_middleware_exist_in_route(): void {
    //     if ($this->is_method_exist($this->app->app_settings->middleware_name)) {
    //         $this->current_class;
    //     };
    // }
    // private function check_method_middleware_exist_in_class(): void {
    //     $this->is_method_exist($this->app->request->method);
    // }
}
