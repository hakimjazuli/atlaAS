<?php

namespace HtmlFirst\atlaAS\csr;

abstract class Regex {
    static string $js_script = '/<script\b[^>]*>(.*?)<\/script>/is';
}
