<?php

namespace HtmlFirst\atlaAS\Middleware;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Utils\hasFSValidator;

class FSMiddleware {
    public string $current_middleware;
    use hasFSValidator;
    public function __construct(private App $app) {
    }
    public function check_mw(): void {
        $mw = $this->current_middleware;
        if (\class_exists($mw)) {
            return;
        };
        $mw = new $mw($this->app);
        if (!$this->is_method_exist('run')) {
            return;
        }
        $mw->run();
    }
}
