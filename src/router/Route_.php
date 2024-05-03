<?php

namespace HtmlFirst\atlaAS\Router;

use HtmlFirst\atlaAS\Utils\hasProtectedApp;

abstract class Route_ {
    use hasProtectedApp;
    /**
     * use this only for calling it's own get method, by chaining it ->get(...$uri);
     */
    protected function self_with_query_param(array $query_param) {
        $this->app->request->generate_query_param($query_param, $this);
        return $this;
    }
}
