<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

trait hasPublicApp {
    public function __construct(public App_ $app) {
    }
}
