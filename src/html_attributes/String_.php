<?php

namespace HtmlFirst\atlaAS\html_attributes;

trait String_ {
    public static function attr(array $attributes): string {
        return \join(' ', $attributes);
    }
    public static function to_string(mixed $value): string {
        return is_string($value) ? $value : \serialize($value);
    }
}
