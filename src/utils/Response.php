<?php

namespace HtmlFirst\atlaAS\Utils;

class Response {
    use hasAppRegex;
    public function echo_no_indent(callable $html_function) {
        \header("Content-Type: text/html; charset=UTF-8");
        \ob_start();
        $html_function();
        $output = \ob_get_clean();
        echo \preg_replace(self::$no_indents, '', $output);
    }
    public function print_json(array $array, bool $as_json_api = true): void {
        if ($as_json_api) {
            \header('Content-Type: application/json');
        }
        \print_r(\json_encode($array));
    }
}
