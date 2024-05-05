<?php

namespace HtmlFirst\atlaAS\Utils;


class __Request {
    use hasSetGlobal;
    public static __Request $__;

    public bool $is_https;
    public string $http_mode;
    public string $uri;
    public array $uri_array;
    public string|null $query_params = null;
    public array|null $query_params_arrray = null;
    public string $public_path;
    public string $method;
    public string $base;
    public function __construct() {
        if ($this->is_https = $this->assign_http()) {
            $this->http_mode = 'https';
        } else {
            $this->http_mode = 'http';
        }
        $request_uri = \explode('?', $_SERVER['REQUEST_URI']);
        $this->uri = \trim($request_uri[0], '/');
        $this->uri_array = $this->get_uri();
        if (\count($request_uri) > 1) {
            $this->query_params = $request_uri[1];
            $this->generate_query_param();
        }
        $this->method = \strtolower($_SERVER['REQUEST_METHOD']);
        $this->public_path = $_SERVER['DOCUMENT_ROOT'];
        $this->set_as_global();
    }
    private function assign_http(): bool {
        if (isset($_SERVER['REQUEST_SCHEME']) && !empty($_SERVER['REQUEST_SCHEME'])) {
            $https = ($_SERVER['REQUEST_SCHEME'] == 'https' ? true : false);
        }
        if (!$https) {
            if (isset($_SERVER['HTTPS']) &&  $_SERVER['HTTPS'] === 'on') {
                return true;
            } elseif (isset($_SERVER['SERVER_PORT']) &&  $_SERVER['SERVER_PORT'] == '443') {
                return true;
            } elseif (
                isset($_SERVER['HTTP_X_FORWARDED_PROTO'])
                &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https'
            ) {
                return true;
            } else {
                return false;
            }
        }
    }
    public function generate_query_param(): array {;
        $this->query_params_arrray = $val = $_GET;
        return $val;
    }
    private function get_uri(): array {
        $uri = \explode('/', $this->uri);
        if ($uri[0] === '') {
            $uri[0] = 'index';
        }
        return $uri;
    }
    public static function method_params(string $method): array {
        switch ($method) {
            case 'get':
            case 'post':
                return $GLOBALS['_' . strtoupper($method)];
            default:
                return self::parse_other_method();
        }
    }
    private static function parse_other_method() {
        $data = file_get_contents('php://input');
        $boundary = "--" . explode("boundary=", $_SERVER["CONTENT_TYPE"])[1];
        $form_data = explode($boundary, $data);
        foreach ($form_data as $item) {
            if (strpos($item, 'Content-Disposition') !== false) {
                preg_match('/name="(.*?)".*[\r\n].*?(\S.*?)[\r\n]/s', $item, $matches);
                if (count($matches) === 3) {
                    $data_[$matches[1]] = $matches[2];
                }
            }
        }
        if (\count($data_) !== 0) {
            return $data_;
        }
    }
}
