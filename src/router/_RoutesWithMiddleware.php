<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

/**
 * @see
 * - derived from:
 * > - [HtmlFirst\atlaAS\Middlewares\_Middleware](#htmlfirst_atlaas_middlewares__middleware);
 * > - [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes);
 */
abstract class _RoutesWithMiddleware extends _Routes {
    use hasMiddleware;
}
