<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Middlewares\_Middleware;
use HtmlFirst\atlaAS\Middlewares\FSMiddleware;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\_FileServer;
use HtmlFirst\atlaAS\Utils\_FunctionHelpers;
use HtmlFirst\atlaAS\Vars\__Settings;

class FSRouter extends FSMiddleware {
    public function run() {
        $this->render();
    }
    private int $routes_length = 0;
    private string $current_route;
    private object|string|false $real_route = false;
    private int $request_length = 0;
    public function render($is_real_route = true) {
        $uri_array = __Request::$uri_array;
        $this->request_length = \count($uri_array);
        $this->current_folder = __atlaAS::$app_root . \DIRECTORY_SEPARATOR . __Settings::$routes_path;
        $this->current_route = '\\' . __Settings::$routes_class;
        $routes_length = 0;
        foreach ($uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . __Settings::$middleware_name;
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
            __atlaAS::reroute_error(404);
            return;
        }
        $this->run_real_route($is_real_route);
    }
    private function check_route(): bool {
        if (!\class_exists($this->current_route)) {
            return false;
        }
        $this->real_route = $this->current_route;
        return true;
    }
    private function run_real_route($is_real_route) {
        $route = $this->real_route;
        $route_ref = new $route($is_real_route);
        __atlaAS::assign_query_param_to_class_property($route_ref);
        if ($route_ref instanceof _RoutesWithMiddleware) {
            $route_ref->mw(__Request::$method);
        }
        if ($route_ref instanceof _Routes) {
            if ($this->check_is_map_resources($route, $route_ref)) {
                return;
            }
            $this->run_method_with_input_logic($route, $route_ref);
        }
    }
    private function check_is_map_resources(string $class_name, _Routes $route_ref): bool {
        if ($route_ref instanceof _MapResources) {
            $url_inputs = \array_slice(__Request::$uri_array, $this->routes_length);
            $route_ref->get(...$url_inputs);
            _FileServer::map_resource($url_inputs, __atlaAS::$app_root . $class_name);
            return true;
        };
        return false;
    }
    private function run_method_with_input_logic(string $class_name, object $route_ref): void {
        $num_params = _FunctionHelpers::url_input_length(
            $class_name,
            $method = __Request::$method
        );
        if ($num_params !== $this->request_length - $this->routes_length) {
            __atlaAS::reroute_error(404);
            return;
        }
        $url_inputs = \array_slice(__Request::$uri_array, -$num_params);
        $route_ref->$method(...$url_inputs);
    }
    public static function follow_up_params(
        array|callable $fallback,
        array $conditionals,
        array $query_parameter = [],
        bool $inherit_query_parameter = true
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
                __atlaAS::render_get($fallback, $query_parameter, $inherit_query_parameter);
            } else {
                $fallback($query_parameter);
            }
            exit(0);
        }
    }
}
