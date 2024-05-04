<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasGlobalChecker_ {
    protected string $name;

    use hasProtectedApp_;
    protected function global(string $name, mixed $initial_value) {
        if (!\is_string($this->name)) {
            return null;
        }
        if (isset($this->app->global[$this->identifier][$name])) {
            return $this->app->global[$this->identifier][$name];
        }
        return ($this->app->global[$this->identifier][$name] = $initial_value);
    }
}
