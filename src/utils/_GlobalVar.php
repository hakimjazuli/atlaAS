<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;

/**
 * @see
 * - class helper to use `__atlaAS::$__->global` feature;
 * - lookup at [HtmlFirst\atlaAS\Utils\_Is](#htmlfirst_atlaas_utils__is) for example
 */
abstract class _GlobalVar {
    protected static string $global_namespace = 'global';

    protected static function global(string $key, mixed $initial_value): mixed {
        $atlaAS_global = __atlaAS::$__->global;
        if (!\is_string(static::$global_namespace)) {
            return null;
        }
        if (isset($atlaAS_global[static::$global_namespace][$key])) {
            return $atlaAS_global[static::$global_namespace][$key];
        }
        return ($atlaAS_global[static::$global_namespace][$key] = $initial_value);
    }
    protected static function set_global(string $key, mixed $new_value): void {
        $atlaAS_global[static::$global_namespace][$key] = $new_value;
    }
}
