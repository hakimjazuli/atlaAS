<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\_FunctionHelpers;
use HtmlFirst\atlaAS\Utils\_Hasher;
use HtmlFirst\atlaAS\Vars\__Settings;

/**
 * @see
 *- using extended \_\_Settings class you can change
 * > - folder: _\_\_Settings class property \$routes_path_
 * > - namespace: _\_\_Settings class property \$routes_class_
 *- routes naming:
 * > - have to be the same with the class name(case-sensitve), preferably lowercase
 * > - method are public function http-method(lower case) with parameters of the dynamic uri's;
 * > - bellow are available on _'/example/test/**my_name**[blank]/**my_num**'_ url, will result in
 * > echoing "my_name, my_num"
 *
 *```php
 *<?php
 *
 *namespace Routes\example;
 *
 *use HtmlFirst\atlaAS\Router\_Routes;
 *
 *class test extends _Routes {
 *    public function get(string $name, string $num) {
 *        echo "$name, $num";
 *    }
 *}
 *```
 *
 *- routes naming:
 * > - you have to extend it from
 * > > - "_HtmlFirst\atlaAS\Router\\\_Routes;_"
 * > > - "_HtmlFirst\atlaAS\Router\\\_RoutesWithMiddleware;_"
 */
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
