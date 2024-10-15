<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Utils\_FunctionHelpers;
use HtmlFirst\atlaAS\Vars\__Settings;

abstract class _Routes {
    public function __construct(public $is_real_route = true) {
    }
    public static function route($return_as_array = false): string|array {
        $route_array = _FunctionHelpers::class_name_as_array(static::class, [__Settings::$__->routes_class]);
        if ($return_as_array) {
            return $route_array;
        }
        return '/' . \join('/', $route_array);
    }
}
