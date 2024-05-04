<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

abstract class GlobalChecker_ {
    protected string $global = 'global';

    protected function global(string $name, mixed $initial_value) {
        if (!\is_string($this->global)) {
            return null;
        }
        if (isset(App_::$app->global[$this->global][$name])) {
            return App_::$app->global[$this->global][$name];
        }
        return (App_::$app->global[$this->global][$name] = $initial_value);
    }
}
