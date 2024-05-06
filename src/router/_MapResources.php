<?php

namespace HtmlFirst\atlaAS\Router;

/**
 * special routes to map the resources of the same name folder:
 * - route file: $routename.php;
 * - resource path: "./$routename/*";
 */
abstract class _MapResources {
    /**
     * overwrite this get method to use it as this route middleware;
     */
    public function get(string ...$uri_array) {
    }
}