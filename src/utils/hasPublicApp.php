<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

trait hasPublicApp {
    public function __construct(public App $app) {
    }
}
