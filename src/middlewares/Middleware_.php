<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Middleware_ {
    use hasMiddleware;
    use hasProtectedApp;
}
