<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\App;

abstract class Queries_ {
    protected Conn $conn;
    public function __construct(protected App $app) {
        $this->conn = new Conn($app);
    }
}
