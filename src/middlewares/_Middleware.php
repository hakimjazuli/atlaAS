<?php

namespace HtmlFirst\atlaAS\Middlewares;

/**
 * @see
 * - class helper to validate `mw.php` and _Routes derived class which also have Middleware in it's name;
 *  ```php
 * <?php
 * 
 * namespace Routes\api;
 * 
 * use HtmlFirst\atlaAS\Middlewares\_Middleware;
 * 
 * class mw extends _Middleware {
 *     public function mw(string $method): bool {
 *         \header('Content-Type: application/json');
 *         }
 *         return true; /** return true to continue response *[blank]/
 * }
 * ```
 * - folder structure
 *  > - `routes`
 *  > > -  `index.php`
 *  > > -  `api`
 *  > > > - `mw.php` <-- this is middleware file
 *
 * - which then turns all of your `/api/**` routes suitable for json api server;
 *
 **/
abstract class _Middleware {
    use hasMiddleware;
}
