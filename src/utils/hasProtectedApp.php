<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

trait hasProtectedApp {
    public function __construct(protected App $app) {
    }
}
