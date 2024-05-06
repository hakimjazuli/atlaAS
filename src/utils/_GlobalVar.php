<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;

/**
 * overwrite this
 * - protected static string $global = $new_value;
 * create public static method that returns:
 * - self::golbal(...$necessary_arguments);
 */
abstract class _GlobalVar {
    protected static string $global = 'global';

    protected static function global(string $name, mixed $initial_value) {
        $atlaAS = __atlaAS::$__;
        if (!\is_string(self::$global)) {
            return null;
        }
        if (isset($atlaAS->global[self::$global][$name])) {
            return $atlaAS->global[self::$global][$name];
        }
        return ($atlaAS->global[self::$global][$name] = $initial_value);
    }
}
