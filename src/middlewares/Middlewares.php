<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Middlewares {
    use hasMiddleware;
    use hasProtectedApp;
}
