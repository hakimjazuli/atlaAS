<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Utils\hasFSValidator;

abstract class FSMiddleware {
    public string $current_middleware;
    public App $app;
    use hasFSValidator;
    public function check_mw(): void {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return;
        };
        $mw = new $mw($this->app);
        if (\method_exists(
            $mw,
            $method = $this->app->app_settings->middleware_name
        )) {
            $mw->$method();
        }
        if (\method_exists(
            $mw,
            $method = $this->app->request->method
        )) {
            $mw->$method();
        }
    }
}
