<?php

namespace HtmlFirst\atlaAS\Middlewares;

/**
 * @see
 * - [internal class](#internals)
 */
trait hasMiddleware {
    /**
     * @param  string $method
     * @return bool
     * - true: allow server to continue the response;
     * - false: immediately stop server response, before returning with false, you may output the response with __Response class method;
     */
    public function mw(string $method): bool {
        /**
         * mock return;
         */
        return true;
    }
}
