<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App;

abstract class Middlewares {
    use hasMidlleware;
    public function __construct(protected App $app) {
    }
}
