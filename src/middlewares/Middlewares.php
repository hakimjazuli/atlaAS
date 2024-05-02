<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App_;

abstract class Middlewares {
    public function __construct(protected App_ $app) {
    }
}
