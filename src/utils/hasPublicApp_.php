<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

trait hasPublicApp_ {
    public function __construct(public App_ $app) {
    }
}
