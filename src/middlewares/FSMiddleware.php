<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Vars\__Settings;

abstract class FSMiddleware {
    public string $current_middleware;
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir(__Settings::system_path($this->current_folder));
    }
    public function check_mw(): bool {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return true;
        };
        $mw_ref = new $mw;
        if ($mw_ref instanceof _Middleware) {
            __atlaAS::assign_query_param_to_class_property($mw_ref);
            return $mw_ref->mw(__Request::$method);
        }
        return true;
    }
}
