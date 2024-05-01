<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

abstract class Models {
    public function __construct(protected App $app) {
    }
}
