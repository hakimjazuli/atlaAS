<?php

namespace HtmlFirst\atlaAS\Connection;

class FieldType {
    public function __construct(public int $type, public string $regex, public string $regex_html) {
    }
}
