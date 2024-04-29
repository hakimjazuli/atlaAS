<?php

namespace HtmlFirst\atlaAS\Models;

abstract class Models extends Connector {
    public function __construct(protected string $table_name) {
        new parent($table_name);
    }
}
