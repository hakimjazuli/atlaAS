<?php

namespace HtmlFirst\atlaAS\connection;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Connection\_FieldType;
use HtmlFirst\atlaAS\Utils\_Hasher;
use PDO;

final class _Binder {
  /**
   * @param _FieldType $field_type;
   * @param string|null $input_name;
   * @param int $pdo_param_type The PDO parameter type (`PDO::PARAM_*` constant).
   *   - Default: `PDO::PARAM_STR`
   * @param mixed $hash_this_value
   * - default false;
   * - true: value will be hashed;
   */
  public function __construct(
    public _FieldType $field_type,
    public string|null $incoming_parameter_name = null,
    public int $pdo_param_type = PDO::PARAM_STR,
    public bool $hash_this_value = false,
  ) {
    $this->pdo_param_type = $field_type->pdo_param_type;
    if ($incoming_parameter_name) {
      $this->value = $field_type->value($incoming_parameter_name);
    } else {
      $this->value = $field_type->value();
      $this->incoming_parameter_name = $field_type->field_name;
    }
    if ($hash_this_value) {
      $this->value = _Hasher::generate_hash($this->value);
    }
  }
  public mixed $value = null;
}
