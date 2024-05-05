<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\hasAppRegex;

/**
 * assign all parameter type as \HtmlFirst\atlaAS\Connection\_FieldType;
 * eg. public _FieldType $field_name_alias;
 * then on constructor assign it by calling $this->column(...$neccessary_args);
 */
abstract class _Table {
    use hasAppRegex;
    protected static function column(int $pdo_param_type, string|null $regex = null): _FieldType {
        return new _FieldType(
            $pdo_param_type,
            $regex,
            $regex ? self::regex_php_to_html($regex) : null
        );
    }
}
