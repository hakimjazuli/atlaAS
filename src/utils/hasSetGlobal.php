<?php

namespace HtmlFirst\atlaAS\Utils;

use ReflectionClass;

trait hasSetGlobal {
    public function set_as_global() {
        $instance = 'instance';
        if (\property_exists($this, $instance)) {
            $reflection_class = new ReflectionClass($this::class);
            $static_roperties = $reflection_class->getStaticProperties();
            if (array_key_exists('staticProperty', $static_roperties)) {
                static::$$instance = $this;
            }
        }
    }
}
