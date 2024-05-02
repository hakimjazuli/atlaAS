<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App_;

abstract class Routes_ {
    public function __construct(protected App_ $app) {
    }
}
