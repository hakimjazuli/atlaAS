<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Middlewares\hasMiddleware;

abstract class _WithMiddleware {
    use hasMiddleware;
}
