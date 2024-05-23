<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

class _RouteWithMapResourcesAndMiddleware extends _RouteWithMapResources {
    use hasMiddleware;
}
