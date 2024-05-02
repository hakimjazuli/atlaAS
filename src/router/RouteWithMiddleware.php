<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

abstract class RouteWithMiddleware extends Route_ {
    use hasMiddleware;
}
