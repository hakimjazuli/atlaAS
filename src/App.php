<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\cli\CLI;
use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Utils\Response;
use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\AppEnv;

class App {
    public Request $request;
    public string $app_root;
    public string $public_url_root;
    public Response $response;
    public function __construct(public AppEnv $app_env, public AppSettings $app_settings) {
        $this->request = new Request;
        $this->app_root = \dirname($this->request->public_path);
        $this->public_url_root = $this->request->http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
        $this->response = new Response;
    }
    public function run(CLI|false $cli = false) {
        if ($cli === false) {
            $fs_router = new FSRouter($this);
            $fs_router->run();
            return;
        }
        $cli->run();
    }
}
