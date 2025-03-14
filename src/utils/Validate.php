<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\Connection\_Table;

/**
 * @see
 * - internal class helper;
 */
class Validate {
    /**
     * @param _Table $table_instance
     * @param string $field_name
     * @param array $if_false_merge
     * - array to merge if $conditional returns true;
     */
    public function __construct(_Table $table_instance, string $field_name, public array $if_false_merge) {
        $this->conditional = \preg_match($table_instance->$field_name->regex, $table_instance->$field_name->value());
    }
    public bool $conditional;
}
