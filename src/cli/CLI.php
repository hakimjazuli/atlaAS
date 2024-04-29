<?php

namespace HtmlFirst\atlaAS\cli;

abstract class CLI {
    public function run() {
        $args = new ArgvInterpreter;
    }
}
