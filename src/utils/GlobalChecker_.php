<?php

namespace HtmlFirst\atlaAS\Utils;

abstract class GlobalChecker_ {
    protected string|null $global = null;

    use hasProtectedApp_;
    protected function global(string $name, mixed $initial_value) {
        if (!\is_string($this->global)) {
            return null;
        }
        if (isset($this->app->global[$this->global][$name])) {
            return $this->app->global[$this->global][$name];
        }
        return ($this->app->global[$this->global][$name] = $initial_value);
    }
}
