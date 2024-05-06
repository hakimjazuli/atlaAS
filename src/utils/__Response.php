<?php

namespace HtmlFirst\atlaAS\Utils;

class __Response {
    use hasSetGlobal;
    public static __Response $__;

    use hasAppRegex;
    public function __construct() {
        $this->set_as_global();
    }

    private function preprocess(callable $html_function, bool $html_document = true) {
        if ($html_document) {
            \header('Content-Type: text/html; charset=UTF-8');
        }
        \ob_start();
        $html_function();
        return \ob_get_clean();
    }
    public function echo_no_indent(callable $html_function, bool $html_document = true) {
        $output = $this->preprocess($html_function, $html_document);
        echo \preg_replace($this::$regex_no_indents, '', $output);
    }
    public function echo_single_line(callable $html_function, bool $html_document = true) {
        $output = $this->preprocess($html_function, $html_document);
        echo trim(\preg_replace(
            [$this::$regex_single_line, $this::$regex_excesive_spacing, '/> /', '/ </'],
            [' ', ' ', '>', '<'],
            $output
        ), ' ');
    }
    public function echo_json_api(array|object $array): void {
        if ($json = \json_encode($array)) {
            $this->header_json();
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
    public function header_json() {
        \header('Content-Type: application/json');
    }
}
