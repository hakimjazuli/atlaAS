<?php

namespace HtmlFirst\atlaAS\Utils;

/**
 * @see
 * - instance helper for `__atlaAS::$__::validate_params` method;
 */
class _FolloupParams {
    /**
     * @param bool $conditional
     * - consider use __atlaAS::$__->input_match(...args);
     */
    public function __construct(public bool $conditional, public array $if_meet_merge) {
    }
}
