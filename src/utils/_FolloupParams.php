<?php

namespace HtmlFirst\atlaAS\Utils;

class _FolloupParams {
    /**
     * @param bool $conditional
     * - consider use __atlaAS::input_match(...args);
     */
    public function __construct(public bool $conditional, public array $if_meet_merge) {
    }
}
