<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Routes_ {
    use hasProtectedApp;
    protected function with_query_param(array $query_param) {
        $this->app->request->overwite_param = $query_param;
        return $this;
    }
}
