<?php

namespace HtmlFirst\atlaAS\Utils;

use ReflectionMethod;

abstract class FunctionHelpers {
    public static function is_first_parameter_spread(string $class_name, string $method_name): bool {
        $reflection = new ReflectionMethod($class_name, $method_name);
        $parameters = $reflection->getParameters();
        if (!empty($parameters) && $parameters[0]->isVariadic()) {
            return true;
        }
        return false;
    }
    public static function url_input_length(string $class_name, string $method_name): int {
        $reflection = new ReflectionMethod($class_name, $method_name);
        return $reflection->getNumberOfParameters();
    }
    public static function run_array_functions(callable ...$functions): void {
        foreach ($functions as $function) {
            $function();
        }
    }
    public static function callable_collections(callable ...$functions): callable {
        return fn () => self::run_array_functions(...$functions);
    }
}
