<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App;

abstract class Middlewares implements hasMidlleware {
    public function __construct(protected App $app) {
    }
}
