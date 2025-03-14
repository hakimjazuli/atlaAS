<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;


/**
 * @see
 * - derived from:
 * > - [HtmlFirst\atlaAS\Middlewares\_Middleware](#htmlfirst_atlaas_middlewares__middleware);
 * > - [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes);
 * > - [HtmlFirst\atlaAS\Router\_MapResources](#htmlfirst_atlaas_router__mapresources);
 */
class _RoutesWithMapResourcesAndMiddleware extends _RoutesWithMapResources {
    use hasMiddleware;
}
