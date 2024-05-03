<?php

namespace HtmlFirst\atlaAS\Connection;

class FieldTableType {
    public function __construct(public int $type, public string $regex, public string $regex_html) {
    }
}
