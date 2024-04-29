<?php

namespace HtmlFirst\atlaAS\cli;

abstract class ArgvInterpreter {
    public array $argv = [];
    public function __construct() {
        // $this->argv = [];
    }
}
