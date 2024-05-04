<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App_;
use HtmlFirst\atlaAS\Middlewares\FSMiddleware;
use HtmlFirst\atlaAS\Utils\FileServer;
use HtmlFirst\atlaAS\Utils\FunctionHelpers;

class FSRouter extends FSMiddleware {
    public function run() {
        $this->render();
    }
    private int $routes_length = 0;
    private string $current_route;
    private object|string|false $real_route = false;
    private int $request_length = 0;
    public function render() {
        $uri_array = App_::$app->request->uri_array;
        $this->request_length = \count($uri_array);
        $this->current_folder = App_::$app->app_root . \DIRECTORY_SEPARATOR . App_::$app->app_settings::$routes_path;
        $this->current_route = '\\' . App_::$app->app_settings::$routes_class;
        $routes_length = 0;
        foreach ($uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . App_::$app->app_settings::$middleware_name;
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
            App_::$app->reroute_error(404);
            return;
        }
        $this->run_real_route();
    }
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
            App_::$app->request->method
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
            $mw_method = App_::$app->app_settings::$middleware_name
        )) {
            return;
        };
        (new $middleware(App_::$app))->$mw_method(App_::$app->request->method);
    }
    private function run_method_with_input_logic(string $class_name): void {
        $num_params = FunctionHelpers::url_input_length(
            $class_name,
            $method = App_::$app->request->method
        );
        if ($num_params !== $this->request_length - $this->routes_length) {
            App_::$app->reroute_error(404);
            return;
        }
        $url_inputs = \array_slice(App_::$app->request->uri_array, -$num_params);
        $route_ = new $class_name(App_::$app);
        $route_->$method(...$url_inputs);
    }
    private function check_method_with_spread_input_logic(string $class_name): bool {
        if ($this->is_map_resource($class_name)) {
            $url_inputs = \array_slice(App_::$app->request->uri_array, $this->routes_length);
            (new $class_name(App_::$app))->get(...$url_inputs);
            $handler = new FileServer(App_::$app);
            $handler->map_resource($url_inputs, App_::$app->app_root . $class_name);
            return true;
        };
        return false;
    }
    private function is_map_resource(string $class_name) {
        if (App_::$app->request->method !== 'get') {
            return false;
        }
        return FunctionHelpers::is_first_parameter_spread($class_name, App_::$app->request->method);
    }
    public function follow_up_params(
        array|callable $fallback,
        array $conditionals,
        array $query_parameter = [],
    ): void {
        $match = true;
        foreach ($conditionals as $data) {
            [$conditional, $if_meet_merge] = $data;
            if (!$conditional) {
                $query_parameter = \array_merge($query_parameter, $if_meet_merge);
                $match = false;
            }
        }
        if (!$match) {
            if (\is_array($fallback)) {
                App_::$app->render_get($fallback, $query_parameter);
            } elseif (\is_callable($fallback)) {
                $fallback($query_parameter);
            }
            exit(0);
        }
    }
}
