<?php

namespace HtmlFirst\atlaAS\Utils;

final class __Response {
    use hasSetGlobal;
    protected static __Response $__;

    use hasAppRegex;
    public function __construct() {
        $this->set_as_global();
    }

    private static function preprocess(callable $html_function, bool $html_document = true) {
        if ($html_document) {
            \header('Content-Type: text/html; charset=UTF-8');
        }
        \ob_start();
        $html_function();
        return \ob_get_clean();
    }
    public static function html_no_indent(callable $html_function, bool $html_document = true) {
        $output = self::$__::preprocess($html_function, $html_document);
        echo \preg_replace(self::$__::$regex_no_indents, '', $output);
    }
    public static function html_single_line(callable $html_function, bool $html_document = true) {
        $output = self::$__::preprocess($html_function, $html_document);
        echo trim(\preg_replace(
            [self::$__::$regex_single_line, self::$__::$regex_excesive_spacing, '/> /', '/ </'],
            [' ', ' ', '>', '<'],
            $output
        ), ' ');
    }
    public static function echo_json_api(array|object $array): void {
        if ($json = \json_encode($array)) {
            self::$__::header_json();
            echo $json;
        } else {
            \header("HTTP/1.0 500 Internal Server Error");
            \print_r([
                'status' => 'error',
                'code' => '500',
                'message' => 'failed to encode to json'
            ]);
        }
        exit(0);
    }
    public static function header_json() {
        \header('Content-Type: application/json');
    }
}
