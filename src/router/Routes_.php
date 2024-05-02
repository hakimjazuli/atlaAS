<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App;

abstract class Routes_ {
    public function __construct(protected App $app) {
    }
    public function mw(string $method) {
    }
}
