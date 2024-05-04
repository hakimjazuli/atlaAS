<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\App_;
use HtmlFirst\atlaAS\Utils\Request_;
use HtmlFirst\atlaAS\Vars\AppSettings_;

abstract class FSMiddleware {
    public string $current_middleware;
    public App_ $app;
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir(AppSettings_::$instance::system_path($this->current_folder));
    }
    public function check_mw(): void {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return;
        };
        if (\method_exists(
            $mw,
            $method = AppSettings_::$instance::$middleware_name
        )) {
            (new $mw)->$method(Request_::$instance->method);
        }
    }
}
