<?php

namespace HtmlFirst\atlaAS\Connection;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Utils\hasAppRegex;
use HtmlFirst\atlaAS\Utils\Validate;

/**
 * @see
 * - internal class helper for _Query;
 */
final class _FieldType {
    use hasAppRegex;
    public string|null $regex_html = null;
    public function __construct(public string $field_name, public int $pdo_param_type, private _Table $table_instance, public string|null $regex = null) {
        $this->regex_html = $this->regex_php_to_html($regex) ?? null;
    }
    public function value(string|null $input_name = null): mixed {
        if ($input_name) {
            return __atlaAS::$__->fs_router::get_form_s_input_param()[$input_name];
        }
        return __atlaAS::$__->fs_router::get_form_s_input_param()[$this->field_name];
    }
    /**
     * validate
     *
     * @param  array $if_false_merge
     * @param  string $input_name
     * - default: uses $this->field_name;
     * - filled: fill it when the query parameter name is not the same with $this->field_name;
     * @return Validate
     */
    public function validate(array $if_false_merge, string|null $input_name = null): Validate {
        if ($input_name === null) {
            $input_name = $this->field_name;
        }
        return new Validate($this->table_instance, $input_name, $if_false_merge);
    }
}
