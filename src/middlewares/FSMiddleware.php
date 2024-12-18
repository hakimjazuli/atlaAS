<?php

namespace HtmlFirst\atlaAS\Middlewares;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Vars\__Settings;

/**
 * @see
 * - [internal class](#internals)
 */
abstract class FSMiddleware {
    private array $allow_cache = [];
    public function is_mw_allowed(string $identifier, callable $callback): bool {
        if (isset($this->allow_cache[$identifier]) && $this->allow_cache[$identifier] === true) {
            return true;
        }
        $new_bool = $callback();
        $this->allow_cache[$identifier] = $new_bool;
        return $new_bool;
    }
    public string $current_middleware;
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir(__Settings::$__->system_path($this->current_folder));
    }
    public function check_mw(): bool {
        $mw = $this->current_middleware;
        if (!\class_exists($mw)) {
            return true;
        };
        $mw_instance = new $mw;
        if ($mw_instance instanceof _Middleware) {
            __atlaAS::$__->assign_query_param_to_class_property($mw_instance);
            return $mw_instance->mw(__Request::$method);
        }
        return true;
    }
}
