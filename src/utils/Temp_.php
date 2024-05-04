<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\Utils\hasPrivateApp_;

class Temp_ {
    use hasPrivateApp_;
    /**
     * shuffle
     *
     * @param  mixed &$variable
     * - temporary change this varibal;
     * - argument as reference;
     * @param  mixed $temp_value
     * @return callable
     * - call this returned function to reset the value;
     */
    public static function shuffle(mixed &$variable, mixed $temp_value): callable {
        $temp_placeholder = $variable;
        $variable = $temp_value;
        return function () use (&$variable, $temp_placeholder) {
            $variable = $temp_placeholder;
        };
    }
    public static function reseter(callable ...$shufflers) {
        return fn () => FunctionHelpers::run_array_functions($shufflers);
    }
}
