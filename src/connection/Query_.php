<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\App_;

abstract class Query_ {
    protected Conn $conn;
    public function __construct() {
        $this->conn = new Conn();
    }
}
