<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\FSMiddleware;
use HtmlFirst\atlaAS\Utils\FileServer;
use HtmlFirst\atlaAS\Utils\FunctionHelpers;
use HtmlFirst\atlaAS\Utils\hasPublicApp;

class FSRouter extends FSMiddleware {
    use hasPublicApp;
    public function run() {
        $this->render_get();
    }
    private function route_from_path($public_uri): Route_ {
        $route_ = '\\' . $this->app->app_settings->routes_class . '\\' . \str_replace('/', '\\', $public_uri);
        return new $route_($this->app);
    }
    private int $routes_length = 0;
    public function render_get(null|array $url = null, null|array $query_parameter = null) {
        $this->current_folder = $this->app->app_root . \DIRECTORY_SEPARATOR . $this->app->app_settings->routes_path;
        $this->current_route = '\\' . $this->app->app_settings->routes_class;
        $url = $url ?? $this->app->request->uri_array;
        if ($query_parameter !== null) {
            $this->app->request->generate_query_param(
                $query_parameter,
                $this->route_from_path(\join($url))
            );
        }
        $this->request_length = \count($url);
        $routes_length = 0;
        foreach ($url as $uri) {
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
        if (!$this->real_route) {
            $this->app->reroute_error(404);
            return;
        }
        $this->run_real_route();
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
        $this->check_middleware_exist_in_route();
        if (!\method_exists(
            $route = $this->real_route,
            $this->app->request->method
        )) {
            return;
        }
        if ($this->check_method_with_spread_input_logic($route)) {
            return;
        }
        $this->run_method_with_input_logic($route);
    }
    private function check_middleware_exist_in_route(): void {
        if (!\method_exists(
            $middleware = $this->real_route,
            $mw_method = $this->app->app_settings->middleware_name
        )) {
            return;
        };
        (new $middleware($this->app))->$mw_method($this->app->request->method);
    }
    private function run_method_with_input_logic(string $class_name): void {
        $num_params = FunctionHelpers::url_input_legth(
            $class_name,
            $method = $this->app->request->method
        );
        if ($num_params !== $this->request_length - $this->routes_length) {
            $this->app->reroute_error(404);
            return;
        }
        $url_inputs = \array_slice($this->app->request->uri_array, -$num_params);
        (new $class_name($this->app))->$method(...$url_inputs);
    }
    private function check_method_with_spread_input_logic(string $class_name): bool {
        if ($this->is_map_resource($class_name)) {
            $url_inputs = \array_slice($this->app->request->uri_array, $this->routes_length);
            (new $class_name($this->app))->get(...$url_inputs);
            $handler = new FileServer($this->app);
            $handler->map_resource($url_inputs, $this->app->app_root . $class_name);
            return true;
        };
        return false;
    }
    private function is_map_resource(string $class_name) {
        if ($this->app->request->method !== 'get') {
            return false;
        }
        return FunctionHelpers::is_first_parameter_spread($class_name, $this->app->request->method);
    }
}
