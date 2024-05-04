<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\App_;

abstract class Queries_ {
    protected Conn $conn;
    public function __construct(protected App_ $app) {
        $this->conn = new Conn($app);
    }
}
