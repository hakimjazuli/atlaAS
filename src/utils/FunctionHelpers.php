<?php

namespace HtmlFirst\atlaAS\Utils;

use ReflectionMethod;

class FunctionHelpers {
    public static function is_first_parameter_spread($class_name, $method_name) {
        $reflection = new ReflectionMethod($class_name, $method_name);
        $parameters = $reflection->getParameters();
        if (!empty($parameters) && $parameters[0]->isVariadic()) {
            return true;
        }
        return false;
    }
    public static function url_input_legth($class_name, $method_name) {
        $reflection = new ReflectionMethod($class_name, $method_name);
        return $reflection->getNumberOfParameters();
    }
}
