<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App;

abstract class Middlewares {
    use hasMiddleware;
    public function __construct(protected App $app) {
    }
}
