<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;

abstract class _GlobalVar {
    protected string $global = 'global';

    protected function global(string $name, mixed $initial_value) {
        if (!\is_string($this->global)) {
            return null;
        }
        if (isset(__atlaAS::$__->global[$this->global][$name])) {
            return __atlaAS::$__->global[$this->global][$name];
        }
        return (__atlaAS::$__->global[$this->global][$name] = $initial_value);
    }
}
