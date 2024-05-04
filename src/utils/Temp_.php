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
    public static function temp_var(callable $function, ...$variables_n_temp_value) {
        $reset_to_oris = [];
        foreach ($variables_n_temp_value as &$pair) {
            $reset_to_oris[] = self::shuffle(...$pair);
        }
        $function();
        foreach ($reset_to_oris as $reset_to_ori) {
            $reset_to_ori();
        }
    }
}
