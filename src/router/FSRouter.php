<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Utils\hasValidator;

class FSRouter {
    use hasValidator;
    public function __construct(private App $app) {
    }
    public function run() {
    }
}
