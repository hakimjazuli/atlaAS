<?php

namespace HtmlFirst\atlaAS\Utils;

class Response {
    use hasAppRegex;
    private function echo_preprocess(callable $html_function) {
        \header("Content-Type: text/html; charset=UTF-8");
        \ob_start();
        $html_function();
        return \ob_get_clean();
    }
    public function echo_no_indent(callable $html_function) {
        $output = $this->echo_preprocess($html_function);
        echo \preg_replace(self::$no_indents, '', $output);
    }
    public function echo_single_line(callable $html_function) {
        $output = $this->echo_preprocess($html_function);
        echo \preg_replace(self::$single_line, '', $output);
    }
    public function print_json(array $array, bool $as_json_api = true): void {
        if ($as_json_api) {
            \header('Content-Type: application/json');
        }
        \print_r(\json_encode($array));
    }
}
