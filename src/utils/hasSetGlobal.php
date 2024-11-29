<?php

namespace HtmlFirst\atlaAS\Utils;

/**
 * @see
 * - singleton helper, use this class in your class, and add $__ as static property
 * ```php
 * <[blank]?php
 *  use hasSetGlobal;
 *  private static __MyClass|null $__ = null;
 *    public function __construct(...) {
 *        if (self::$__ = null) {
 *            return;
 *        }
 *        ...
 *        $this>->set_as_global();
 *    }
 *  
 * ```
 */
trait hasSetGlobal {
    protected function set_as_global() {
        $instance = '__';
        if (\property_exists($this, $instance)) {
            static::$$instance = $this;
        }
    }
}
