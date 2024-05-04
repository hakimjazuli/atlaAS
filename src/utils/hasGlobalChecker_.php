<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasGlobalChecker_ {
    use hasProtectedApp_;
    protected function global(string $identifier, string $prop, mixed $overwrite_value) {
        if (isset($this->app->global[$identifier][$prop])) {
            return $this->app->global[$identifier][$prop];
        }
        return ($this->app->global[$identifier][$prop] = $overwrite_value);
    }
}
