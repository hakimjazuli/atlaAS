<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;

abstract class _GlobalVar {
    protected static string $global = 'global';

    protected static function global(string $name, mixed $initial_value) {
        if (!\is_string(self::$global)) {
            return null;
        }
        if (isset(__atlaAS::$__->global[self::$global][$name])) {
            return __atlaAS::$__->global[self::$global][$name];
        }
        return (__atlaAS::$__->global[self::$global][$name] = $initial_value);
    }
}
