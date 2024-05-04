<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\Utils\hasProtectedApp_;

abstract class Middleware_ {
    use hasMiddleware;
    use hasProtectedApp_;
}
