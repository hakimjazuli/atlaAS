<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Middlewares\FSMiddleware;
use HtmlFirst\atlaAS\Utils\FunctionHelpers;
use HtmlFirst\atlaAS\Utils\ResourcesHandler;

class FSRouter extends FSMiddleware {
    public function __construct(public App $app) {
    }
    public function run() {
        $this->current_folder = $this->app->app_root . \DIRECTORY_SEPARATOR . $this->app->app_settings->routes_path;
        $this->current_route = $this->app->app_settings->routes_class;
        $this->routes_from_uri_array();
        if (!$this->real_route) {
            return;
        }
        $this->run_real_route();
    }
    private int $routes_length = 0;
    private function routes_from_uri_array() {
        $this->request_length = \count($this->app->request->uri_array);
        $routes_length = 0;
        foreach ($this->app->request->uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . $this->app->app_settings->middleware_name;
            $this->check_mw();
            $routes_length++;
            $this->current_route .= '\\' . $uri;
            if ($this->check_route()) {
                $this->routes_length = $routes_length;
            } elseif (!$this->is_folder_exist()) {
                break;
            }
        }
    }
    private string $current_route;
    private object|string|false $real_route = false;
    private int $request_length = 0;
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
        if (!\method_exists(
            $route = $this->real_route,
            $this->app->request->method
        )) {
            return;
        }
        $route_ = new $route($this->app);
        $this->check_method_with_spread_input_logic($route_);
        $this->run_method_with_input_logic($route, $route_);
    }
    private function check_common_middleware_exist_in_route(): void {
        if (!\method_exists(
            $middleware = $this->real_route,
            $mw_method = $this->app->app_settings->middleware_name
        )) {
            return;
        };
        $middleware = new $middleware($this->app);
        $middleware->$mw_method();
    }
    private function check_method_middleware_exist_in_route(): void {
        if (!\method_exists(
            $middleware = $this->real_route,
            $mw_method = $this->app->app_settings->middleware_name . '_' . $this->app->request->method
        )) {
            return;
        };
        $middleware = new $middleware($this->app);
        $middleware->$mw_method();
    }
    private function run_method_with_input_logic(string $class_ref, Routes_ $class_instance, array $url_inputs = []): void {
        $num_params = FunctionHelpers::url_input_legth(
            $class_ref,
            $method = $this->app->request->method
        );
        if (
            \count($url_inputs) === 0 &&
            $num_params !== $this->request_length - $this->routes_length
        ) {
            $this->app->reroute_error(404);
            return;
        }
        $url_inputs = \count($url_inputs) === 0 ?
            \array_slice($this->app->request->uri_array, -$num_params) :
            $url_inputs;
        $class_instance->{$method}(...$url_inputs);
    }
    private function check_method_with_spread_input_logic($class_ref) {
        if ($this->is_map_resource($class_ref)) {
            $url_inputs = \array_slice($this->app->request->uri_array, $this->routes_length);
            $handler = new ResourcesHandler($this->app);
            $handler->map_resource($url_inputs, $this->app->app_root . $class_ref);
            exit(0);
        };
    }
    private function is_map_resource(Routes_ $class_ref) {
        if ($this->app->request->method !== 'get') {
            return false;
        }
        return FunctionHelpers::is_first_parameter_spread($class_ref, $this->app->request->method);
    }
}
