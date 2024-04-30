<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasRegex {
    public static function regex_php_to_html(string $php_regex) {
        return trim($php_regex, "/");
    }

    public static $url_request = '/(?<=\/)[a-zA-Z0-9_-]+?(?=\.html)/';
    public static $single_line = '/\/\*[\s\S]*?\*\/|<!--[\s\S]*?-->|\r|\n|\t|\s{2,}|\/\/ .*$/m';
    public static $excesive_spacing = '/\s{2,}/m';
    public static $html_tag_spacing = '/\s*(?=[><\/])/mU';
    public static $excesive_html_br = '/(<br>){2,}/';
    public static $no_indents = '/^[\s]+| {2,}/m';
    public static $html_standard_output = '/^[\s]+|<!-- Start: scrambled content -->|<!-- End: scrambled content -->| {2,}/m';
    public static function tag(string $tag_name) {
        return "/<$tag_name(.*?)>|<\/$tag_name>/m";
    }
    public static function alphanumeric_loose(int $min, int $max) {
        return '/^(?=.*[a-zA-Z0-9]).{' . $min . ',' . $max . '}$/';
    }
    public static function alpha_loose(int $min, int $max) {
        return '/^(?=.*[a-zA-Z]).{' . $min . ',' . $max . '}$/';
    }
    public static function numeric(int $min, int $max) {
        return '/^[0-9]{' . $min . ',' . $max . '}$/';
    }
    public static $float = '/^[-|\.|0-9]{1,}$/';
    public static $replace_non_alphanumeric = '/[^a-zA-Z0-9_]+/';
    public static function enum(...$allowed_values): string {
        $escaped_values = array_map('preg_quote', $allowed_values);
        $pattern = '/^(' . implode('|', $escaped_values) . ')$/';
        return $pattern;
    }
}
