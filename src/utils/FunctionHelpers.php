<?php

namespace HtmlFirst\atlaAS\Utils;

use ReflectionMethod;

abstract class FunctionHelpers {
    public static function is_first_parameter_spread(string $class_name, string $method_name) {
        $reflection = new ReflectionMethod($class_name, $method_name);
        $parameters = $reflection->getParameters();
        if (!empty($parameters) && $parameters[0]->isVariadic()) {
            return true;
        }
        return false;
    }
    public static function url_input_length(string $class_name, string $method_name) {
        $reflection = new ReflectionMethod($class_name, $method_name);
        return $reflection->getNumberOfParameters();
    }
    public static function run_array_functions(callable ...$functions) {
        foreach ($functions as $function) {
            $function();
        }
    }
}
