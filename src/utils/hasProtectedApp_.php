<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App_;

trait hasProtectedApp_ {
    public function __construct(protected App_ $app) {
    }
}
