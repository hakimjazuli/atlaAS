# atlaAS

- php:

> - [file system routing](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#routing);

> - [setting up middleware](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#middlewares);

> - [file serving](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#serving-files);

> - [connection and SQL DB querying library](https://github.com/hakimjazuli/atlaAS/blob/main/README.md#sql-query);

- <strike>this library is designed to be used in the conjuction with our client side HATEOAS sister</strike>

library *https://github.com/hakimjazuli/atlaAS_client* in mind;

- however you can still uses it as Backend normally for, like:

> - building REST json api backend: using our "_HtmlFirstatlaASMiddlewares\_Middleware_",

> to set up header default header on `/api/**` routes;

> - serving files: using our "_HtmlFirstatlaASRouter\_MapResources;_";

> - building HATEOAS backend for htmx/other HATEOAS library/framework;

> > - in fact you might be surprissed how good File System Routing might fare for

> > htmx/other HATEOAS library due to the nature of atlaAS code splitting in general;

> >

> > > - automatic routes setup;

> > > - no need to register it using framework class instances first;

> >

> > - in htmx use case, you can even opt out from using `hx-select` and/or `hx-target` as

> > the returned html needed are easily split per routes file;

> > - not to mention how php is a natural templating language for html _(well... if

> > there's any more natural language, php is still the most easiest to set up, "there's no setup", just use `?>` to enter front end and `<?php` to go back to backend)_

> > > - just make sure to sanitize your output, so you don't get XSS attack from user

> > > generated content;

## assumption

this library assumes you are familiar with:

- php psr-4 auto-loading, using composer;

- php OOP(for extending, and using our helper classes in general, also **atlaAS** uses little

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

	SetEnvIf Origin "^http(s)?://(.+.)?(127.0.0.1:8000)$" ACAO=$0

	# SetEnvIf Origin "^http(s)?://(.+.)?(127.0.0.1:8000|172.23.224.1:8000)$" ACAO=$0

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

## Credit(s)

this library is inspired by:

- htmx.js: *https://htmx.org/*

> - more precisely it's HATEOAS paradigm in general;

- sveltekit: https://kit.svelte.dev/

> - more precisely it's clean File System Routing in general;

> - and many other js meta framework with FS Routing;

## Globals

- class prefixed "__" with are singleton made globals by accessing it like this `__ClassName::$__`;

## Setting_Class

- class that are need to be extended, instantiated as argument for `__atlaAS`

- modifiy it's properties and methods as you needed

## Internals

- core class that are meant to be used only for library internals functionality and not to be called on the app;

## exported-api-by-namespace

- [HtmlFirst\atlaAS\Connection\Conn](#htmlfirst_atlaas_connection_conn)

- [HtmlFirst\atlaAS\Connection\_atlaASQuery](#htmlfirst_atlaas_connection__atlaasquery)

- [HtmlFirst\atlaAS\connection\_Binder](#htmlfirst_atlaas_connection__binder)

- [HtmlFirst\atlaAS\Connection\_FieldType](#htmlfirst_atlaas_connection__fieldtype)

- [HtmlFirst\atlaAS\Connection\_Query](#htmlfirst_atlaas_connection__query)

- [HtmlFirst\atlaAS\Connection\_Table](#htmlfirst_atlaas_connection__table)

- [HtmlFirst\atlaAS\Middlewares\FSMiddleware](#htmlfirst_atlaas_middlewares_fsmiddleware)

- [HtmlFirst\atlaAS\Middlewares\_Middleware](#htmlfirst_atlaas_middlewares__middleware)

- [HtmlFirst\atlaAS\Router\FSRouter](#htmlfirst_atlaas_router_fsrouter)

- [HtmlFirst\atlaAS\Router\_MapResources](#htmlfirst_atlaas_router__mapresources)

- [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes)

- [HtmlFirst\atlaAS\Router\_RoutesWithMapResources](#htmlfirst_atlaas_router__routeswithmapresources)

- [HtmlFirst\atlaAS\Router\_RoutesWithMapResourcesAndMiddleware](#htmlfirst_atlaas_router__routeswithmapresourcesandmiddleware)

- [HtmlFirst\atlaAS\Router\_RoutesWithMiddleware](#htmlfirst_atlaas_router__routeswithmiddleware)

- [HtmlFirst\atlaAS\Utils\Validate](#htmlfirst_atlaas_utils_validate)

- [HtmlFirst\__atlaAS\Utils\VideoStream](#htmlfirst___atlaas_utils_videostream)

- [HtmlFirst\atlaAS\Utils\_Cors](#htmlfirst_atlaas_utils__cors)

- [HtmlFirst\atlaAS\Utils\_FileServer](#htmlfirst_atlaas_utils__fileserver)

- [HtmlFirst\atlaAS\Utils\_FunctionHelpers](#htmlfirst_atlaas_utils__functionhelpers)

- [HtmlFirst\atlaAS\Utils\_GlobalVar](#htmlfirst_atlaas_utils__globalvar)

- [HtmlFirst\atlaAS\Utils\_Hasher](#htmlfirst_atlaas_utils__hasher)

- [HtmlFirst\atlaAS\Utils\_Is](#htmlfirst_atlaas_utils__is)

- [HtmlFirst\atlaAS\Utils\_Temp](#htmlfirst_atlaas_utils__temp)

- [HtmlFirst\atlaAS\Utils\__Request](#htmlfirst_atlaas_utils___request)

- [HtmlFirst\atlaAS\Utils\__Response](#htmlfirst_atlaas_utils___response)

- [HtmlFirst\atlaAS\Vars\__Env](#htmlfirst_atlaas_vars___env)

- [HtmlFirst\atlaAS\Vars\__Settings](#htmlfirst_atlaas_vars___settings)

- [HtmlFirst\atlaAS\Vars\__SQLite3](#htmlfirst_atlaas_vars___sqlite3)

- [HtmlFirst\atlaAS\__atlaAS](#htmlfirst_atlaas___atlaas)

<h2 id="htmlfirst_atlaas_connection_conn">HtmlFirst\atlaAS\Connection\Conn</h2>

- containt static methods for connection helpers;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_connection__atlaasquery">HtmlFirst\atlaAS\Connection\_atlaASQuery</h2>

- type generator for [_Query](#_query)


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_connection__binder">HtmlFirst\atlaAS\connection\_Binder</h2>




*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_connection__fieldtype">HtmlFirst\atlaAS\Connection\_FieldType</h2>

- internal class helper for _Query;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_connection__query">HtmlFirst\atlaAS\Connection\_Query</h2>

-   query helper```php<?phpnamespace Backend\Queries;use Backend\Tables\Test as TablesTest;use HtmlFirst\atlaAS\Connection\_atlaASQuery;use HtmlFirst\atlaAS\Connection\_Query;class Test extends _Query {   public static function test_name_like(string $test_name): _atlaASQuery {       $test = new TablesTest;       return self::sql_query('/sql/views/test.sql', bind: [           ... new HtmlFirst\atlaAS\connection\_Binder(...$args),       ]);   }}```-   setting up `/sql/views/test.sql````sqlSELECT`id`,   test.test_nameFROM   testWHERE   `test_name` LIKE :test_name;```


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_connection__table">HtmlFirst\atlaAS\Connection\_Table</h2>

- extend this class for sql table templating;- assign all property type as \HtmlFirst\atlaAS\Connection\_FieldType;- eg. public _FieldType $field_name_alias;- then on constructor assign it by calling $this->column(...$neccessary_args);-   table helper```php<?phpnamespace Backend\Tables;use HtmlFirst\atlaAS\Connection\_FieldType;use HtmlFirst\atlaAS\Connection\_Table;use PDO;class Test extends _Table {    public _FieldType $id;   public _FieldType $name;   public function __construct() {       $this->id = $this->column(PDO::PARAM_INT);       $this->name = $this->column(PDO::PARAM_STR, $this->regex_alphanumeric_loose(1, 255));   }}```


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_middlewares_fsmiddleware">HtmlFirst\atlaAS\Middlewares\FSMiddleware</h2>

- [internal class](#internals)


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_middlewares__middleware">HtmlFirst\atlaAS\Middlewares\_Middleware</h2>

- class helper to validate `mw.php` and _Routes derived class which also have Middleware in it's name; ```php<?phpnamespace Routes\api;use HtmlFirst\atlaAS\Middlewares\_Middleware;class mw extends _Middleware {    public function mw(string $method): bool {        \header('Content-Type: application/json');        }        return true; /** return true to continue response */}```- folder structure > - `routes` > > -  `index.php` > > -  `api` > > > - `mw.php` <-- this is middleware file- which then turns all of your `/api/**` routes suitable for json api server;*


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router_fsrouter">HtmlFirst\atlaAS\Router\FSRouter</h2>

- [internal class](#internals)


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router__mapresources">HtmlFirst\atlaAS\Router\_MapResources</h2>

```php<?phpnamespace Routes;use HtmlFirst\atlaAS\Router\_MapResources;class assets extends _MapResources {}```your folder should then looks like this- `routes` > - `assets.php` > - `assets` >   > - `atlaAS.mjs` >   > - `main.css`- you can overwrite `map_resources` method to use it as additional middleware to get the list of uri array request;> - although you might not output anything as it will bug the headers for file range;- your intellisense warning is your friend;- _\_MapResources_ Routes's map_resources method uses spread parameters;- don't worry, it will **NOT** serve your `.php` files( or any file extentions, listed in extended _\_\_Settings $system_file_);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router__routes">HtmlFirst\atlaAS\Router\_Routes</h2>

- using extended \_\_Settings class you can change> - folder: _\_\_Settings class property \$routes_path_> - namespace: _\_\_Settings class property \$routes_class_- routes naming:> - have to be the same with the class name(case-sensitve), preferably lowercase> - method are public function http-method(lower case) with parameters of the dynamic uri's;> - bellow are available on _'/example/test/**my_name**/**my_num**'_ url, will result in> echoing "my_name, my_num"```php<?phpnamespace Routes\example;use HtmlFirst\atlaAS\Router\_Routes;class test extends _Routes {   public function get(string $name, string $num) {       echo "$name, $num";   }}```- routes naming:> - you have to extend it from> > - "_HtmlFirst\atlaAS\Router\\\_Routes;_"> > - "_HtmlFirst\atlaAS\Router\\\_RoutesWithMiddleware;_"


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router__routeswithmapresources">HtmlFirst\atlaAS\Router\_RoutesWithMapResources</h2>

- derived from:> - [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes);> - [HtmlFirst\atlaAS\Router\_MapResources](#htmlfirst_atlaas_router__mapresources);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router__routeswithmapresourcesandmiddleware">HtmlFirst\atlaAS\Router\_RoutesWithMapResourcesAndMiddleware</h2>

- derived from:> - [HtmlFirst\atlaAS\Middlewares\_Middleware](#htmlfirst_atlaas_middlewares__middleware);> - [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes);> - [HtmlFirst\atlaAS\Router\_MapResources](#htmlfirst_atlaas_router__mapresources);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_router__routeswithmiddleware">HtmlFirst\atlaAS\Router\_RoutesWithMiddleware</h2>

- derived from:> - [HtmlFirst\atlaAS\Middlewares\_Middleware](#htmlfirst_atlaas_middlewares__middleware);> - [HtmlFirst\atlaAS\Router\_Routes](#htmlfirst_atlaas_router__routes);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils_validate">HtmlFirst\atlaAS\Utils\Validate</h2>

- internal class helper;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst___atlaas_utils_videostream">HtmlFirst\__atlaAS\Utils\VideoStream</h2>

- a modified VideoStream helper which the original I got from```php<?php/** * @author Rana modified by HS* @link http://codesamplez.com/programming/php-html5-video-streaming-tutorial*/```


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__cors">HtmlFirst\atlaAS\Utils\_Cors</h2>

- contains static method(s) to handle cors policy;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__fileserver">HtmlFirst\atlaAS\Utils\_FileServer</h2>

- contains method(s) for file serving related functionalities;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__functionhelpers">HtmlFirst\atlaAS\Utils\_FunctionHelpers</h2>

- contains method(s) for php general variable handling functionalities;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__globalvar">HtmlFirst\atlaAS\Utils\_GlobalVar</h2>

- class helper to use `__atlaAS::$__->global` feature;- lookup at [HtmlFirst\atlaAS\Utils\_Is](#htmlfirst_atlaas_utils__is) for example


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__hasher">HtmlFirst\atlaAS\Utils\_Hasher</h2>

collection of static methods for hashing purposes;- html_csrf_element: for generating string element of csrf;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__is">HtmlFirst\atlaAS\Utils\_Is</h2>

- an example to use [HtmlFirst\atlaAS\Utils\_GlobalVar](#htmlfirst_atlaas_utils__globalvar):```php<?phpclass _Is extends _GlobalVar {   protected static string $global_namespace = 'is';   public static function atlaAS_client_request(_Routes $_routes): false|string {       if (!$_routes->is_real_route) {           return false;       }       $atlaAS_client_request_header = __Request::valid_request_header('atlaAS_client_form'); // 1       if (isset($_SERVER[$atlaAS_client_request_header])) {           return self::global($atlaAS_client_request_header, $_SERVER[$atlaAS_client_request_header]); // 2       }       return false;   }}```- 1  generate valid http request header for `atlaAS_client_from` in this case `HTTP_ATLAAS_CLIENT_FORM`;- 2  can be used to access (and assign at the same time) __atlaAS::$__::$global associative array, which then be used down the line of current request;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils__temp">HtmlFirst\atlaAS\Utils\_Temp</h2>

- static method `var` of this class to be used to hold temporary value onto reference, which then returns a `callable`, to return the value before calling `var`;?> - `var_reference` the first argument is a pointer;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils___request">HtmlFirst\atlaAS\Utils\__Request</h2>

- this class is [global singelton](#globals) - altough this class are global singleton all methods and properties are public static;- this class contains several values that contains incoming request variables;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_utils___response">HtmlFirst\atlaAS\Utils\__Response</h2>

- this class is [global singelton](#globals) - altough this class are global singleton all methods and properties are public static;- this class contains several common methods to handle response to client;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_vars___env">HtmlFirst\atlaAS\Vars\__Env</h2>

- this class is a [global singelton](#globals);- this class is a [setting class]($setting_class);- overwrite this  `public function pdo`;


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_vars___settings">HtmlFirst\atlaAS\Vars\__Settings</h2>

- this class is a [global singelton](#globals);- this class is a [setting class]($setting_class);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas_vars___sqlite3">HtmlFirst\atlaAS\Vars\__SQLite3</h2>

- this class is a [global singelton](#globals);- this class is a [setting class]($setting_class);


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>


<h2 id="htmlfirst_atlaas___atlaas">HtmlFirst\atlaAS\__atlaAS</h2>

- this class is [global singelton](#globals)- use this class as entry point;- instantiate it, with extended __Env, __Settings, __SQLite3* as arguments;- then call run method;```php// /your_public_root/index.html<?phprequire_once __DIR__ . '/../vendor/autoload.php';(new \Backend\__atlaAS(    new \Backend\__Env,    new \Backend\__Settings,    new \Backend\__SQLite3,))->run();```


*) <sub>[go to exported list](#exported-api-by-namespace)</sub>
