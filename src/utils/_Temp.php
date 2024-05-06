<?php

namespace HtmlFirst\atlaAS\Utils;

class _Temp {
    /**
     * var
     *
     * @param  mixed &$var_reference
     * - temporary change this variable;
     * - argument as reference;
     * @param  mixed $temp_value
     * - temporary assign $temp_value to $var_reference;
     * @return callable $reset_function
     * - call this returned function to reset the value;
     */
    public static function var(mixed &$var_reference, mixed $temp_value): callable {
        $temp_placeholder = $var_reference;
        $var_reference = $temp_value ?? $var_reference;
        return function () use (&$var_reference, $temp_placeholder) {
            $var_reference = $temp_placeholder;
        };
    }
}
