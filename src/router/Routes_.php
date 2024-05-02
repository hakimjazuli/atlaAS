<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Routes_ {
    use hasProtectedApp;
    public function __call($method, $args) {
        if ($method === $this->app->request->method) {
            $this->self_mw_check();
        }
    }
    private function self_mw_check() {
        if (\method_exists(
            $this,
            $mw = $this->app->app_settings->middleware_name
        )) {
            $mw($this->app->request->method);
        }
    }
    private function recursive_mw_call() {
    }
}
