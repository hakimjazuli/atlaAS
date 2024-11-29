<?php

namespace HtmlFirst\atlaAS\Connection;

/**
 * @see
 * - class helper for _Query;
 */
final class _FieldType {
    public function __construct(public int $type, public string|null $regex = null, public string|null $regex_html = null) {
    }
}
