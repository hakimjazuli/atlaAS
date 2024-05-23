<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

class _IndexRouteWithMiddleware extends _IndexRoute {
    use hasMiddleware;
    public function get() {
    }
    public function map_resources(string ...$map) {
    }
}
