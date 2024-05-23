<?php

use HtmlFirst\atlaAS\Router\_MapResources;

abstract class _IndexRoute extends _MapResources {
    public function index() {
    }
    public function get(string ...$map) {
        if (\count($map) === 0) {
            $this->index();
            exit(0);
        }
    }
}
