<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasAppRegex {
    protected static function regex_php_to_html(string $php_regex) {
        return trim($php_regex, "/");
    }
    protected static $regex_single_line = '/\/\*[\s\S]*?\*\/|<!--[\s\S]*?-->|\r|\n|\t|\s{2,}|\/\/ .*$/m';
    protected static $regex_excesive_spacing = '/\s{2,}/m';
    protected static $regex_html_tag_spacing = '/\s*(?=[><\/])/mU';
    protected static $regex_excesive_html_br = '/(<br>){2,}/';
    protected static $regex_no_indents = '/^[\s]+| {2,}/m';
    protected static function tag(string $tag_name) {
        return "/<$tag_name(.*?)>|<\/$tag_name>/m";
    }
    protected static function regex_alphanumeric_loose(int $min, int $max) {
        return '/^(?=.*[a-zA-Z0-9]).{' . $min . ',' . $max . '}$/';
    }
    protected static function regex_alpha_loose(int $min, int $max) {
        return '/^(?=.*[a-zA-Z]).{' . $min . ',' . $max . '}$/';
    }
    protected static function regex_numeric_loose(int $min, int $max) {
        return '/^[0-9]{' . $min . ',' . $max . '}$/';
    }
    protected static $regex_float = '/^[-|\.|0-9]{1,}$/';
    protected static $regex_non_alphanumeric = '/[^a-zA-Z0-9_]+/';
    protected static function enum(...$allowed_values): string {
        $escaped_values = array_map('preg_quote', $allowed_values);
        $pattern = '/^(' . implode('|', $escaped_values) . ')$/';
        return $pattern;
    }
}
