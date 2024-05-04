<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasSetGlobal {
    public function set_as_global() {
        $instance = 'instance';
        if (\method_exists($this, $instance)) {
            static::$$instance = $this;
        }
    }
}
