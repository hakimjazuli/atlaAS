<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App;

abstract class FSMiddleware {
    public string $current_middleware;
    public App $app;
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir($this->current_folder);
    }
    public function check_mw(): void {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return;
        };
        if (\method_exists(
            $mw,
            $method = $this->app->app_settings->middleware_name
        )) {
            (new $mw($this->app))->$method($this->app->request->method);
        }
    }
}
