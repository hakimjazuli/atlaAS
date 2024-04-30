<?php

namespace HtmlFirst\atlaAS\Utils;

trait hasFSValidator {
    public string $current_folder;
    public function is_folder_exist(): bool {
        return \is_dir($this->current_folder);
    }
}
