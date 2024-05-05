<?php

namespace HtmlFirst\atlaAS\Connection;

abstract class _Query {
    protected Conn $conn;
    public function __construct() {
        $this->conn = new Conn();
    }
}
