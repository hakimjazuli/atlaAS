<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\App;
use HtmlFirst\atlaAS\Middleware\FSMiddleware;

class FSRouter extends FSMiddleware {
    public function __construct(private App $app) {
        new parent($app);
    }
    public function run() {
    }
    private string $current_route;
    private string $real_route;
    private function check_route(): void {
        $this->current_class = $this->current_route;
        if (!$this->is_class_exist()) {
            return;
        }
        $this->real_route = $this->current_route;
    }
    private function check_common_middleware_exist_in_route(): void {
        if ($this->is_method_exist($this->app->app_settings->middleware_name)) {
            $this->current_class;
        };
    }
    private function check_method_middleware_exist_in_class(): void {
        $this->is_method_exist($this->app->request->method);
    }
}
