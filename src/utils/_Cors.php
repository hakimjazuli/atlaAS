<?php

namespace HtmlFirst\atlaAS\Utils;

class _Cors {
    /**
     * __construct
     * @param array $allowed_origins 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param array $allowed_methods 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param array  $allowed_headers 
     * - assoc arrays;
     * - if have '*' it will allows all;
     * @param float  $max_age
     * - in days
     * @return void
     */
    public function __construct(
        private array $allowed_origins = [],
        private array $allowed_methods = [],
        private array  $allowed_headers = [],
        private float $max_age = 1,
    ) {
        $this->handle();
    }
    private function handle() {
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        if (in_array('*', $this->allowed_origins)) {
            header("Access-Control-Allow-Origin: *");
        } elseif (in_array($origin, $this->allowed_origins)) {
            header("Access-Control-Allow-Origin: " . $origin);
        }
        if (in_array('*', $this->allowed_methods)) {
            header("Access-Control-Allow-Methods: *");
        } elseif (!empty($this->allowed_methods)) {
            header("Access-Control-Allow-Methods: " . implode(", ", $this->allowed_methods));
        }
        if (in_array('*', $this->allowed_headers)) {
            header("Access-Control-Allow-Headers: *");
        } elseif (!empty($this->allowed_headers)) {
            header("Access-Control-Allow-Headers: " . implode(", ", $this->allowed_headers));
        }
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            if (in_array('*', $this->allowed_methods)) {
                header("Access-Control-Allow-Methods: *");
            } elseif (!empty($this->allowed_methods)) {
                header("Access-Control-Allow-Methods: " . implode(", ", $this->allowed_methods));
            }
            if (in_array('*', $this->allowed_headers)) {
                header("Access-Control-Allow-Headers: *");
            } elseif (!empty($this->allowed_headers)) {
                header("Access-Control-Allow-Headers: " . implode(", ", $this->allowed_headers));
            }
            header('Access-Control-Max-Age: ' . $this->max_age * 86400);
            exit(0);
        }
    }
}
