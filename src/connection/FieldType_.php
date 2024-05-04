<?php

namespace HtmlFirst\atlaAS\Connection;

class FieldType_ {
    public function __construct(public int $type, public string|null $regex = null, public string|null $regex_html = null) {
    }
}
