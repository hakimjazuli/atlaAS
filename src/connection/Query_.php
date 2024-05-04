<?php

namespace HtmlFirst\atlaAS\Connection;

abstract class Query_ {
    protected Conn $conn;
    public function __construct() {
        $this->conn = new Conn();
    }
}
