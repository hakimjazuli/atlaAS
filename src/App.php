<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\cli\CLI;
use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Utils\Response;
use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\Env;

class App {
    public Request $request;
    public Response $response;
    public function __construct(public Env $env, public AppSettings $app_settings) {
        $this->request = new Request;
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
