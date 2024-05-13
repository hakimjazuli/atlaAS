<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Vars\__Settings;

abstract class _Routes {
    public function __construct(public $is_real_route = true) {
    }
    public function route($return_as_string = false): string|array {
        $class_ref = \str_replace(
            [
                __Settings::$routes_class,
                '\\'
            ],
            [
                '',
                '/'
            ],
            $this::class
        );
        if ($return_as_string) {
            return $class_ref;
        }
        return \explode('/', $class_ref);
    }
}
