<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;

/**
 * overwrite this
 * - protected static string $global = $new_value;
 * create public static method that returns:
 * - self::global(...$necessary_arguments);
 */
abstract class _GlobalVar {
    protected static string $global = 'global';

    protected static function global(string $name, mixed $initial_value) {
        $atlaAS_global = __atlaAS::$__->global;
        if (!\is_string(static::$global)) {
            return null;
        }
        if (isset($atlaAS_global[static::$global][$name])) {
            return $atlaAS_global[static::$global][$name];
        }
        return ($atlaAS_global[static::$global][$name] = $initial_value);
    }
    protected static function set_global(string $name, mixed $new_value): void {
        $atlaAS_global[static::$global][$name] = $new_value;
    }
}
