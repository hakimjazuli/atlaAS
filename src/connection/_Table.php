<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\Utils\hasAppRegex;

/**
 * @see
 * - extend this class for sql table templating;
 * - assign all property type as \HtmlFirst\atlaAS\Connection\_FieldType;
 * - eg. public _FieldType $field_name_alias;
 * - then on constructor assign it by calling $this->column(...$neccessary_args);
 * 
 * -   table helper
 *
 *```php
 *<?php
 *
 *namespace Backend\Tables;
 *
 *use HtmlFirst\atlaAS\Connection\_FieldType;
 *use HtmlFirst\atlaAS\Connection\_Table;
 *use PDO;
 *
 *class Test extends _Table {
 *    public _FieldType $id;
 *    public _FieldType $name;
 *    public function __construct() {
 *        $this->id = $this->column(PDO::PARAM_INT);
 *        $this->name = $this->column(PDO::PARAM_STR, $this->regex_alphanumeric_loose(1, 255));
 *    }
 *}
 *
 *```
 */
abstract class _Table {
    use hasAppRegex;
    protected function column(int $pdo_param_type, string|null $regex = null): _FieldType {
        return new _FieldType(
            $pdo_param_type,
            $regex,
            $regex ? $this->regex_php_to_html($regex) : null
        );
    }
}
