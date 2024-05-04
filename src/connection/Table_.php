<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\hasAppRegex;

/**
 * assign all parameter type as \HtmlFirst\atlaAS\Connection\FieldType;
 * eg. public FieldType user_name;
 * then on constructor assign it by calling $this->column(...$neccessary_args);
 */
abstract class Table_ {
    use hasAppRegex;
    protected function column(int $pdo_param_type, string|null $regex = null): FieldType {
        return new FieldType(
            $pdo_param_type,
            $regex,
            $regex !== null ? $this->regex_php_to_html($regex) : null
        );
    }
}