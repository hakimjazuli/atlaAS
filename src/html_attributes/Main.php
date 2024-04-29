<?php

namespace HtmlFirst\atlaAS\html_attributes;

use HtmlFirst\atlaAS\Utils\String_;

abstract class Main {
    public function __construct() {
    }
    private array $html_attributes = [];
    protected function attr() {
        return String_::join_html_attributes($this->html_attributes);
    }
    protected function set_attr(string $name, $value) {
        $this->html_attributes[$name] = String_::to_string($value);
        return $this;
    }
}
