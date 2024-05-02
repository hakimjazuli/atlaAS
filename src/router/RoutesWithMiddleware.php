<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

abstract class RoutesWithMiddleware extends Routes_ {
    use hasMiddleware;
}
