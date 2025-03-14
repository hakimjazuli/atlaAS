<?php

namespace HtmlFirst\atlaAS\Utils;

/**
 * @see
 * - this class is [global singelton](#globals) 
 * - altough this class are global singleton all methods and properties are public static;
 * - this class contains several common methods to handle response to client;
 */
final class __Response {
    use hasSetGlobal;
    private static __Response|null $__ = null;

    use hasAppRegex;
    public function __construct() {
        if (static::$__ !== null) {
            return;
        }
        $this->set_as_global();
    }
    static bool $already_json_header = false;
    private static function preprocess(callable $html_function, bool $html_document = true) {
        if ($html_document) {
            \header('Content-Type: text/html; charset=UTF-8');
        }
        \ob_start();
        $html_function();
        return \ob_get_clean();
    }
    /**
     * html_no_indent
     * - average looking output;
     * 
     * @param  callable $html_function
     * - returning to html tag using "?>HTML CODE<?php" inside function block;
     * @return void
     */
    public static function html_no_indent(callable $html_function) {
        $output = self::$__::preprocess($html_function);
        echo \preg_replace(self::$__::$regex_no_indents, '', $output);
    }
    /**
     * html_pretify
     * - most human readable output;
     *
     * @param  callable $html_function
     * - returning to html tag using "?>HTML CODE<?php" inside function block;
     * @return void
     */
    public static function html_pretify(callable $html_function) {
        $output = self::$__::preprocess($html_function);
        $html = preg_replace('/^\s*\n/', '', $output);
        /** Find the first non-whitespace character's indentation */
        if (preg_match('/^(\s*)\S/m', $html, $matches)) {
            $indent = $matches[1];
            /** Capture leading spaces/tabs */
            $html = preg_replace('/^' . preg_quote($indent, '/') . '/m', '', $html);
        }
        echo $html;
    }
    /**
     * html_single_line
     * - best for transfer size;
     * 
     * @param  callable $html_function
     * - returning to html tag using "?>HTML CODE<?php" inside function block;
     * @return void
     */
    public static function html_single_line(callable $html_function) {
        $output = self::$__::preprocess($html_function);
        echo trim(\preg_replace(
            [self::$__::$regex_single_line, self::$__::$regex_excessive_spacing, '/> /', '/ </'],
            [' ', ' ', '>', '<'],
            $output
        ), ' ');
    }
    public static function echo_json(array|object $array): void {
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
        if (!self::$already_json_header) {
            \header('Content-Type: application/json');
            self::$already_json_header = true;
        }
    }
}
