<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Vars\__Settings;

abstract class FSMiddleware {
    public string $current_middleware;
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir(__Settings::$__->system_path($this->current_folder));
    }
    public function check_mw(): void {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return;
        };
        if (\method_exists(
            $mw,
            $method = __Settings::$__->middleware_name
        )) {
            (new $mw)->$method(__Request::$__->method);
        }
    }
}
