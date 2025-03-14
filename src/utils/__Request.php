<?php

namespace HtmlFirst\atlaAS\Utils;

/**
 * @see
 * - this class is [global singelton](#globals) 
 * - altough this class are global singleton all methods and properties are public static;
 * - this class contains several values that contains incoming request variables;
 */
final class __Request {
    use hasSetGlobal;
    private static __Request|null $__ = null;

    public static bool $is_https;
    public static string $http_mode;
    public static string $uri;
    public static array $uri_array;
    public static string|null $query_params = null;

    /**
     * atlaAS best practice is to add $query_name to the route class;
     * so it can be accessed using $this->$$query_parameter_name;
     */
    public static array|null $query_params_array = null;

    public static function valid_request_header(string $request_header): string {
        return \strtoupper('HTTP_' . $request_header);
    }
    public static string $public_path;
    public static string $method;
    public static string $base;
    public function __construct() {
        if (static::$__ !== null) {
            return;
        }
        if ($this::$is_https = $this::assign_http()) {
            $this::$http_mode = 'https';
        } else {
            $this::$http_mode = 'http';
        }
        $this::$public_path = $_SERVER['DOCUMENT_ROOT'];
        $this::$method = \strtolower($_SERVER['REQUEST_METHOD']);
        $request_uri = \explode('?', $_SERVER['REQUEST_URI']);
        $this::$uri = \trim($request_uri[0], '/');
        $this->set_uri();
        if (\count($request_uri) > 1) {
            $this::$query_params = $request_uri[1];
        }
        $this::$query_params_array = $_GET;
        $this->set_as_global();
    }
    private static function assign_http(): bool {
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
        return false;
    }
    private static function set_uri() {
        $uri = \explode('/', self::$uri);
        if (\count($uri) !== 1) {
            $uri = array_map('htmlspecialchars', $uri);
            self::$uri_array = $uri;
            return;
        }
        if ($uri[0] === '') {
            $uri[0] = 'index';
        } elseif (\str_contains($uri[0], '.')) {
            $uri[1] = $uri[0];
            $uri[0] = 'index';
        }
        $uri = array_map('htmlspecialchars', $uri);
        self::$uri_array = $uri;
    }
    public static function method_params(): array {
        return match ($method = self::$__::$method) {
            'get', 'post' => $GLOBALS['_' . strtoupper($method)],
            default => self::$__::parse_other_method(),
        };
    }
    private static function parse_other_method(): array {
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
        return [];
    }
}
