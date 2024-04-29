<?php

namespace HtmlFirst\atlaAS\csr;

class String_ {
    private static function to_string(mixed $value): string {
        return is_string($value) ? $value : \serialize($value);
    }
    public static function de_js_script(string $js_script): string {
        return \preg_replace(Regex::$js_script, '', $js_script);
    }
    private array $html_attributes = [];
    public function attr() {
        $attributes = [];
        foreach ($this->html_attributes as $key => $value) {
            $attributes = "$key=\"$value\"";
        }
        return \join(' ', $attributes);
    }
    public function set_attr(string $name, $value): static {
        $this->html_attributes[$name] = self::to_string($value);
        return $this;
    }
}
