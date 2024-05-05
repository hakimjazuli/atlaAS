<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__App;

abstract class _GlobalVar {
    protected string $global = 'global';

    protected function global(string $name, mixed $initial_value) {
        if (!\is_string($this->global)) {
            return null;
        }
        if (isset(__App::$__->global[$this->global][$name])) {
            return __App::$__->global[$this->global][$name];
        }
        return (__App::$__->global[$this->global][$name] = $initial_value);
    }
}
