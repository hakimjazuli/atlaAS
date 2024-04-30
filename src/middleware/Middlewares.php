<?php

namespace HtmlFirst\atlaAS\Middleware;

use HtmlFirst\atlaAS\App;

abstract class Middlewares {
    public function __construct(protected App $app) {
    }
}
