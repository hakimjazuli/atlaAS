<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

trait hasPrivateApp {
    public function __construct(private App $app) {
    }
}
