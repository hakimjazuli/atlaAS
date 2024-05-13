<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

abstract class _RoutesWithMiddleware extends _Routes {
    use hasMiddleware;
}
