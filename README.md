﻿# atlaAS

-   php:
    > -   [file system routing](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#routing);
    > -   [setting up middleware](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#middlewares);
    > -   [file serving](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#serving-files);
    > -   [connection and SQL DB querying library](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#sql-query);
-   this library is designed to be used in the conjuction with our client side HATEOAS sister
    library *https://github.com/hakimjazuli/atlaAS_client* in mind;
-   however you can still uses it as Backend normally for, like:
    > -   building REST json api backend: using our "_HtmlFirst\atlaAS\Middlewares\\\_Middleware_",
    >     to set up header default header on `/api/**` routes;
    > -   serving files: using our "_HtmlFirst\atlaAS\Router\\\_MapResources;_";
    > -   building HATEOAS backend for htmx/other HATEOAS library/framework;
    >     > -   in fact you might be surprissed how good File System Routing might fare for
    >     >     htmx/other HATEOAS library due to the nature of atlaAS code splitting in general;
    >     >
    >     >     > -   automatic routes setup;
    >     >     > -   no need to register it using framework class instances first;
    >     >
    >     > -   in htmx use case, you can even opt out from using `hx-select` and/or `hx-target` as
    >     >     the returned html needed are easily split per routes file;
    >     > -   not to mention how php is a natural templating language for html _(well... if
    >     >     there's any more natural language, php is still the most easiest to set up, "there's
    >     >     no setup", just use `?>` to enter front end and `<?php` to go back to backend)_
    >     >     > -   just make sure to sanitize your output, so you don't get XSS attack from user
    >     >     >     generated content;

## assumption

this library assumes you are familiar with:

-   php psr-4 auto-loading, using composer;
-   php OOP(for extending, and using our helper classes in general, also **atlaAS** uses little
    abstraction, and not neccesarily a _battery-included_ library, so you have to have good
    underlying php OOP in generals);

## how to install

```shell
composer require html_first/atla-as
```

## how to initialize

set your `.htaccess` on your static public folder into something like this:

```t
<IfModule mod_rewrite.c>
    SetEnvIf Origin "^http(s)?://(.+\.)?(127\.0\.0\.1:8000)$" ACAO=$0
    # SetEnvIf Origin "^http(s)?://(.+\.)?(127\.0\.0\.1:8000|172\.23\.224\.1:8000)$" ACAO=$0
    Header set Access-Control-Allow-Origin "%{ACAO}e" env=ACAO

    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

extends our

-   _"HtmlFirst\atlaAS\\\_\_atlaAS;"_
-   _"HtmlFirst\atlaAS\Vars\\\_\_Env;"_
-   _"HtmlFirst\atlaAS\Vars\\\_\_Settings;"_

then run method from extended \_\_atlaAs; your `/public/index.php` then should looks this

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new \Backend\__atlaAS(
    new \Backend\__Env,
    new \Backend\__Settings
))->run();

```

## \_\_Env

```php
<?php

namespace Backend;

use HtmlFirst\atlaAS\Vars\__Env;
use PDO;

class Env extends __Env {
    public bool $is_in_production = false;

    public string $app_key = 'YOUR_APP_KEY';
    public string $preffered_connection = 'app';
    public function pdo(bool $https, string $connection): PDO {
        /**
         *
         * switch case to return desired connection mode
         * based on the _Query.sql_query(connection: $connectionValue)
         *
        */
        return new PDO(...pdoArguments);
    }
    public $api = [
        'KEY_NAME' => [
            'YOUR_API_KEY' => 'STATUS_VALUE',
        ]
    ];
}

```

## Routing

-   using extended \_\_Settings class you can change
    > -   folder: _\_\_Settings class property \$routes_path_
    > -   namespace: _\_\_Settings class property \$routes_class_
-   routes naming:
    > -   have to be the same with the class name(case-sensitve), preferably lowercase
    > -   method are public function http-method(lower case) with parameters of the dynamic uri's;
    > -   bellow are available on _'/example/test/**my_name**/**my_num**'_ url, will result in
    >     echoing "my_name, my_num"

```php
<?php

namespace Routes\example;

use HtmlFirst\atlaAS\Router\_Routes;

class test extends _Routes {
    public function get(string $name, string $num) {
        echo "$name, $num";
    }
}
```

-   routes naming:
    > -   you have to extend it from
    >     > -   "_HtmlFirst\atlaAS\Router\\\_Routes;_"
    >     > -   "_HtmlFirst\atlaAS\Router\\\_RoutesWithMiddleware;_"

## special Routes

-   `index`

    > -   `/` or `/index` which is commonly known as `root` url should be extended from
    >     `\HtmlFirst\atlaAS\Router\_RouteWithMapResources` or
    >     `\HtmlFirst\atlaAS\Router\_RouteWithMapResourcesAndMiddleware`
    > -   due to the nature of web crawler, most of your web resource are assumed to be on the root
    >     folder; you can structure your routes like this;

-   `routes`
    > -   `index.php`
    > -   `other_routes.php`
    > -   `index`
    >     > -   `ads.txt` which can be accessed using `/ads.txt`
    >     > -   `robot.txt` which can be accessed using `/robot.txt`

## Serving files

1. **routes exclusively for file serving**

```php
<?php

namespace Routes;

use HtmlFirst\atlaAS\Router\_MapResources;

class assets extends _MapResources {
}
```

your folder should then looks like this

-   `routes`

    > -   `assets.php`
    > -   `assets`
    >     > -   `atlaAS.mjs`
    >     > -   `main.css`

you can overwrite `map_resources` method to use it as additional middleware to get the list of uri
array request;

-   your intellisense warning is your friend;
-   _\_MapResources_ Routes's map_resources method uses spread parameters;
-   don't worry, it will **NOT** serve your `.php` files( or any file extentions, listed in extended
    _\_\_Settings $system_file_);

2. **normal routes with file serving (using
   `_RouteWithMapResources`|`_RouteWithMapResourcesAndMiddleware`)**

```php
<?php

namespace Routes;

use HtmlFirst\atlaAS\Router\_RouteWithMapResources;

class assets extends _RouteWithMapResources {
}
```

or

```php
<?php

namespace Routes;

use HtmlFirst\atlaAS\Router\_RouteWithMapResourcesAndMiddleware;

class assets extends _RouteWithMapResourcesAndMiddleware {
}
```

script reading priority for those routes (using
`_RouteWithMapResources`|`_RouteWithMapResourcesAndMiddleware`) are

1. route method `mw`, then
1. if method == `get` then,
    1. if uri_input length is 0, then read route method `get` with `exit(0)`;
    1. if uri_input length is 1 or more, then read route method `map_resources` with `exit(0)`;
1. if method != `get`then read route method `$method`;

## Middlewares

-   naming:

    > -   uses extended _\_\_Settings $middleware_name_;
    > -   `mw.php` by default;

-   middleware file

```php
<?php

namespace Routes\api;

use HtmlFirst\atlaAS\Middlewares\_Middleware;

class mw extends _Middleware {
    public function mw(string $method): bool {
        \header('Content-Type: application/json');
        }
        return true; /** return true to continue response */
}
```

-   folder structure

    > -   `routes`
    >     > -   `index.php`
    >     > -   `api`
    >     >     > -   `mw.php` <-- this is middleware file

-   which then turns all of your `/api/**` routes suitable for json api server;
-   routes can also have its own middleware

```php
<?php

namespace Routes\example;

use HtmlFirst\atlaAS\Router\_RoutesWithMiddleware;

class test extends _RoutesWithMiddleware {
    public function mw(string $method): bool{
        /**
         * $method can be used for conditionals
         * to apply in only on specific method
         * return false to immediately stop response;
        */
    }
    public function get(string $name, string $num) {
        echo "$name, $num";
    }
}
```

-   middleware priorities are run like this
    > -   toppest parent `mw.php`;
    > -   then parent bellow **UNTIL** routes folder;
    > -   then `mw.php` on the same folder;
    > -   then routes `mw` method (on _\_MapResources_ case, you can use its `map_resources` method
    >     too to get list of uri array)

## SQL Query

-   table helper

```php
<?php

namespace Backend\Tables;

use HtmlFirst\atlaAS\Connection\_FieldType;
use HtmlFirst\atlaAS\Connection\_Table;
use PDO;

class Test extends _Table {
    public _FieldType $id;
    public _FieldType $name;
    public function __construct() {
        $this->id = $this->column(PDO::PARAM_INT);
        $this->name = $this->column(PDO::PARAM_STR, $this->regex_alphanumeric_loose(1, 255));
    }
}

```

-   query

```php
<?php

namespace Backend\Queries;

use Backend\Tables\Test as TablesTest;
use HtmlFirst\atlaAS\Connection\_atlaASQuery;
use HtmlFirst\atlaAS\Connection\_Query;

class Test extends _Query {
    public static function test_name_like(string $test_name): _atlaASQuery {
        $test = new TablesTest;
        return self::sql_query('/sql/views/test.sql', bind: [
            'test_name' => [$test->name->type, "%$test_name%"]
        ]);
    }
}
```

-   setting up `/sql/views/test.sql`

```sql
SELECT
	`id`,
    test.test_name
FROM
    test
WHERE
    `test_name` LIKE :test_name;
```

-   then you can call it anywhere on your _\_Routes_ method like:

```php
<?php

$results = \Backend\Queries\Test::test_name_like('a');
```

-   yea... I know... we are doing raw sql with no orm here... so you need to edit your sql file
    using sql client software to type safe your query, like (including but not limited to) DBeaver,
    HeidiSQL, SQLite Expert, or many other;
-   but fundamentally you can just opt out from raw sql, by installing your preffered orm;

## what... no .env?

-   yes, this library doesn't support `.env` by default;
    > -   it's for type safe purposes;
-   options:
    > -   install dependency for `.env`, or
    > -   put your extended `Env.php` on your `.gitignore` and make `EnvExample.php`, also
    > -   if you have brainfarts to put `.whatEverYourEnvFileIS` on `./public/`, perhaps considers
    >     to ![just don't](https://i.imgflip.com/4ajqpj.jpg?a475800)

## Library Naming Convenience

-   classes that are **PREFIXED** with "\_\_" are globals, no need to be instantiated after the
    `/public/index.php` script;
-   classes or traits that are **PREFIXED** with "\_" are meant to be used in your app;
-   classes or traits that are **NOT PREFIXED** with any "\_" are meant for library internals;

## Credit(s)

this library is inspired by:

-   htmx.js: *https://htmx.org/*
    > -   more precisely it's HATEOAS paradigm in general;
-   sveltekit: https://kit.svelte.dev/
    > -   more precisely it's clean File System Routing in general;
    > -   and many other js meta framework with FS Routing;
