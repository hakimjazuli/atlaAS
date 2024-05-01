<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\App;

abstract class Hasher {
    public function __construct(protected App $app) {
    }
}
