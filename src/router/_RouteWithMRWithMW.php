<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

class _RouteWithMRWithMW extends _RouteWithMR {
    use hasMiddleware;
}
