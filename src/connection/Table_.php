<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\hasAppRegex;

/**
 * assign all parameter type as \HtmlFirst\atlaAS\Connection\FieldType_;
 * eg. public FieldType_ $field_name_alias;
 * then on constructor assign it by calling $this->column(...$neccessary_args);
 */
abstract class Table_ {
    use hasAppRegex;
    protected function column(int $pdo_param_type, string|null $regex = null): FieldType_ {
        return new FieldType_(
            $pdo_param_type,
            $regex,
            $regex !== null ? $this->regex_php_to_html($regex) : null
        );
    }
}
