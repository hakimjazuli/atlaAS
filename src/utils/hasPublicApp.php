<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

trait hasPublicApp {
    public function __construct(private App $app) {
    }
}
