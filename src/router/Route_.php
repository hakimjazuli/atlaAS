<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Route_ {
    use hasProtectedApp;
    /**
     * use this only for calling it's own get method;
     */
    protected function with_query_param(array $query_param) {
        $this->app->request->overwite_param = $query_param;
        return $this;
    }
}
