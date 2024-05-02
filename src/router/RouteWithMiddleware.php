<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

abstract class RouteWithMiddleware extends Routes_ {
    use hasMiddleware;
}
