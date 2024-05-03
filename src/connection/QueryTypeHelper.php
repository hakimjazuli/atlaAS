<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\hasAppRegex;

abstract class QueryTypeHelper {
    use hasAppRegex;
    protected function column(int $pdo_param_type, string|null $regex = null): FieldTableType {
        return new FieldTableType(
            $pdo_param_type,
            $regex,
            $regex ? $this->regex_php_to_html($regex) : null
        );
    }
}
