<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\cli\CLI;
use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\AppEnv;

class App {
    public Request $request;
    public string $app_root;
    public string $public_url_root;
    public function __construct(public AppEnv $app_env, public AppSettings $app_settings) {
        $this->request = new Request;
        $this->app_root = \dirname($this->request->public_path);
        $this->public_url_root = $this->request->http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
    }
    public function run(CLI|false $cli = false) {
        if ($cli === false) {
            $fs_router = new FSRouter($this);
            $fs_router->run();
            return;
        }
        $cli->run();
    }
    public static function reroute(string $path) {
        \header("location: $path");
        exit(0);
    }
    public static function set_error_header(int $code = 404) {
        switch ($code) {
            case 403:
                \header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                \header("HTTP/1.0 404 Not Found");
                break;
            case 500:
                \header("HTTP/1.0 500 Internal Server Error");
                break;
        }
    }
    public function reroute_error(int $code = 404) {
        self::set_error_header($code);
        self::reroute($this->app_settings->routes_error_prefix . $code);
    }
}
