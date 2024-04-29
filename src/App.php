<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\Env;

class App {
    public function __construct(private Env $env, private AppSettings $app_settings) {
    }
    public function run() {
    }
}
