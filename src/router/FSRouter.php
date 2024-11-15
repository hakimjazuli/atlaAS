<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Middlewares\FSMiddleware;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\_FileServer;
use HtmlFirst\atlaAS\Utils\_FunctionHelpers;
use HtmlFirst\atlaAS\Vars\__Settings;

final class FSRouter extends FSMiddleware {
    public function run() {
        $this->render();
    }
    public static array|null  $form_s_input_param = null;
    private int $routes_length = 0;
    private string $current_route;
    private object|string|false $real_route = false;
    private int $request_length = 0;
    public function render($is_real_route = true) {
        $uri_array = __Request::$uri_array;
        $this->request_length = \count($uri_array);
        $this->current_folder = __atlaAS::$__->app_root . \DIRECTORY_SEPARATOR . __Settings::$__->routes_path;
        $this->current_route = '\\' . __Settings::$__->routes_class;
        $routes_length = 0;
        $middleware_name = __Settings::$__->middleware_name();
        foreach ($uri_array as $uri) {
            $this->current_folder .= \DIRECTORY_SEPARATOR . $uri;
            $this->current_middleware = $this->current_route . '\\' . $middleware_name;
            if (
                !($allowed = FSMiddleware::is_mw_allowed($this->current_middleware)) &&
                !$this->check_mw()
            ) {
                return;
            }
            FSMiddleware::allow_mw($allowed, $this->current_middleware);
            $routes_length++;
            $this->current_route .= '\\' . $uri;
            if ($this->check_route()) {
                $this->routes_length = $routes_length;
            } elseif (!$this->is_folder_exist()) {
                break;
            }
        }
        if (!$this->real_route) {
            __atlaAS::$__->reroute_error(404);
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
        $route_instance = new $route($is_real_route);
        if ($route_instance instanceof _Routes) {
            __atlaAS::$__->assign_query_param_to_class_property($route_instance);
            if (
                !($allowed = FSMiddleware::is_mw_allowed($route::class)) &&
                $route_instance instanceof _RoutesWithMiddleware &&
                !$route_instance->mw(__Request::$method)
            ) {
                return;
            }
            FSMiddleware::allow_mw($allowed, $route::class);
            if ($this->check_is_map_resources($route, $route_instance)) {
                return;
            }
            $this->run_method_with_input_logic($route, $route_instance);
        }
    }
    private function check_is_map_resources(string $class_name, _Routes $route_instance): bool {
        if ($route_instance instanceof _MapResources && __Request::$method === 'get') {
            $url_inputs = \array_slice(__Request::$uri_array, $this->routes_length);
            if (\count($url_inputs) === 0) {
                if ($route_instance instanceof _RouteWithMapResourcesAndMiddleware) {
                    if (
                        !($allowed = FSMiddleware::is_mw_allowed($class_name)) &&
                        !$route_instance->mw('get')
                    ) {
                        /**
                         * to stop from checking any further route function check;
                         */
                        return true;
                    };
                    FSMiddleware::allow_mw($allowed, $class_name);
                    $route_instance->get();
                } elseif ($route_instance instanceof _RouteWithMapResources) {
                    $route_instance->get();
                }
            } else {
                if (\method_exists($route_instance, $mw_name = __Settings::$__->middleware_name())) {
                    if (!$route_instance->$mw_name('get')) {
                        /**
                         * to stop from checking any further route function check;
                         */
                        return true;
                    };
                }
                $route_instance->map_resources(...$url_inputs);
                _FileServer::serves($url_inputs, $class_name);
            }
            return true;
        };
        return false;
    }
    private function run_method_with_input_logic(string $class_name, object $route_instance): void {
        $num_params = _FunctionHelpers::url_input_length(
            $class_name,
        );
        if ($num_params !== $this->request_length - $this->routes_length) {
            __atlaAS::$__->reroute_error(404);
            return;
        }
        $method = __Request::$method;
        $url_inputs = \array_slice(__Request::$uri_array, -$num_params);
        if (!\method_exists($route_instance, $method)) {
            __atlaAS::$__->reroute_error(404);
            return;
        }
        $route_instance->$method(...$url_inputs);
    }
}
