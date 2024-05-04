<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

trait hasPrivateApp_ {
    public function __construct(private App_ $app) {
    }
}
