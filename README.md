﻿# atlaAS

php file system routing, file serving, connection and SQL DB querying library.

## assumption

this library assumes you are familiar with:

-   php psr-4 auto-loading, using composer;
-   php OOP syntax(for extending, and using our helper classes in general);

## how to initialize

extends our

-   _"HtmlFirst\atlaAS\\\_\_atlaAS;"_
-   _"HtmlFirst\atlaAS\Vars\\\_\_Env;"_
-   _"HtmlFirst\atlaAS\Vars\\\_\_Settings;"_

then run static method from extended \_\_atlaAs; your `/public/index.php` then should looks this

```php
<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new \Backend\__atlaAS(
    new \Backend\__Env,
    new \Backend\__Settings
))::run();

```

## Routes

-   using extended \_\_Settings class you can change
    > -   folder: _\_\_Settings class::\$routes_path_
    > -   namespace: _\_\_Settings class::\$routes_class_
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

## Serving files

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

-   overwrite get method to use it as middleware for this specific routes;
    > -   your intellisense warning is your friend;
    > -   \_MapResources Routes's get method uses spread parameters;
-   don't worry it will **NOT** serve your `.php` files( or any file extentions, set using
    _\_\_Settings::$system_file_);

## Middlewares

-   naming:

    > -   uses extended _\_\_Settings::$middleware_name_;

-   middleware file

```php
<?php

namespace Routes;

use HtmlFirst\atlaAS\Middlewares\_Middleware;

class mw extends _Middleware {
    public function mw(string $method) {
        }
}
```

-   folder structure

    > -   `routes`
    >     > -   `index.php`
    >     > -   `mw.php` <-- this is middleware file
    >     > -   `other_routes.php`

-   routes can also have middleware

```php
<?php

namespace Routes\example;

use HtmlFirst\atlaAS\Router\_RoutesWithMiddleware;

class test extends _RoutesWithMiddleware {
    public function mw(string $method){
        // your middleware code
    }
    public function get(string $name, string $num) {
        echo "$name, $num";
    }
}
```

-   middleware are run like this
    > -   toppest parent `mw.php`;
    > -   then parent bellow **until** routes folder;
    > -   then `mw.php` on the same folder;
    > -   then routes `mw` method;

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
        return self::sql_query('views/test.sql', bind: [
            'test_name' => [$test->name->type, "%$test_name%"]
        ]);
    }
}
```

-   then you can call it anywhere on your \_Routes method like

```php
<?php

$results = \Backend\Queries\Test::test_name_like('a');
```

-   setting sql on _\_\_Settings::\$sqls_path_ `./views/test.sql`

```sql
SELECT
	`id`,
    test.test_name
FROM
    test
WHERE
    `test_name` LIKE :test_name;
```

-   yea... I know... we are doing raw sql with no orm here... so you need to edit your sql file
    using sql client software to type safe your query, like (including but not limited to) DBeaver,
    HeidiSQL, SQLite Expert, and many other;
-   but fundamentally you can just opt out from raw sql, by installing your preffered orm;

## composer setting

```json
{
    ...
	"repositories": [
		{
			"type": "vcs",
			"url": "https://github.com/hakimjazuli/atlaAS"
		}
	],
	"require": {
		"html_first/atla-as": "*"
	},
    ...
}
```

## what... no .env?

-   yes, this library doesn't support `.env` by default;
    > -   it's for type safe purposes;
-   options:
    > -   install dependency for `.env`, or
    > -   put your extended `Env.php` on your `.gitignore` and make `EnvExample.php`;

## Library Naming Convenience

-   classes that **ARE** prefixed with "**\_\_**" are globals, no need to instantiate it after the
    `/public/index.php` script;
-   classes or traits that **ARE** prefixed "**\_**" are meant to be used in your app;
-   objects that **ARE NOT** prefixed with any "**\_**" are meant for framework internals;
