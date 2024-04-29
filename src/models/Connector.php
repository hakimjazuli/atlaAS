<?php

namespace HtmlFirst\atlaAS\Models;

abstract class Connector {
    public function __construct(protected string $table_name) {
    }
}
