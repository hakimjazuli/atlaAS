<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasSetGlobal {
    protected function set_as_global() {
        $instance = '__';
        if (\property_exists($this, $instance)) {
            static::$$instance = $this;
        }
    }
}
