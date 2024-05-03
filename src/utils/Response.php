<?php

namespace HtmlFirst\atlaAS\Utils;

class Response {
    use hasAppRegex;
    private static function preprocess(callable $html_function, bool $html_document = true) {
        if ($html_document) {
            \header("Content-Type: text/html; charset=UTF-8");
        }
        \ob_start();
        $html_function();
        return \ob_get_clean();
    }
    public static function echo_no_indent(callable $html_function, bool $html_document = true) {
        $output = self::preprocess($html_function, $html_document);
        echo \preg_replace(self::$regex_no_indents, '', $output);
    }
    public static function echo_single_line(callable $html_function, bool $html_document = true) {
        $output = self::preprocess($html_function, $html_document);
        echo trim(\preg_replace(
            [self::$regex_single_line, self::$regex_excesive_spacing, '/> /', '/ </'],
            [' ', ' ', '>', '<'],
            $output
        ), ' ');
    }
    public static function echo_json_api(array|object $array): void {
        if ($json = \json_encode($array)) {
            \header('Content-Type: application/json');
            echo $json;
            return;
        }
        \header("HTTP/1.0 500 Internal Server Error");
        \print_r([
            'status' => 'error',
            'message' => 'failed to encode to json'
        ]);
        exit(0);
    }
}
