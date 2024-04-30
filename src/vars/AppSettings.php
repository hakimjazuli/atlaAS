<?php

namespace HtmlFirst\atlaAS\Vars;

abstract class AppSettings {
    public string $middleware_name = 'mw';
    public string $routes_path = 'routes';
    public string $routes_class = '\\Routes';
}
