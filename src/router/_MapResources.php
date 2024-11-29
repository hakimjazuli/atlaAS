<?php

namespace HtmlFirst\atlaAS\Router;

/**
 * @see
 *```php
 *<?php
 *
 *namespace Routes;
 *
 *use HtmlFirst\atlaAS\Router\_MapResources;
 *
 *class assets extends _MapResources {
 *}
 *```
 *
 *your folder should then looks like this
 *
 *- `routes`
 *
 *  > - `assets.php`
 *  > - `assets`
 *  >   > - `atlaAS.mjs`
 *  >   > - `main.css`
 *
 *- you can overwrite `map_resources` method to use it as additional middleware to get the list of uri array request;
 *> - although you might not output anything as it will bug the headers for file range;
 *- your intellisense warning is your friend;
 *- _\_MapResources_ Routes's map_resources method uses spread parameters;
 *- don't worry, it will **NOT** serve your `.php` files( or any file extentions, listed in extended _\_\_Settings $system_file_);
 */
abstract class _MapResources extends _Routes {
    /**
     * overwrite this get method to use it as this route middleware;
     */
    public function map_resources(string ...$uri_array) {
        /**
         * - your middleware script goes here;
         * - make sure to not output anything as it will bug out the headers for file range;
         */
    }
}
