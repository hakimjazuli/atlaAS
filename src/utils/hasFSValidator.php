<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasFSValidator {
    public string $current_folder;
    public string $current_class;
    public function is_method_exist(string $method_name): bool {
        return \method_exists($this->current_class, $method_name);
    }
    public function is_folder_exist(): bool {
        return \is_dir($this->current_folder);
    }
    public function is_class_exist(): bool {
        return \class_exists($this->current_class);
    }
}
