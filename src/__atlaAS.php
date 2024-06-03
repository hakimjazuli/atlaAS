<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\Middlewares\_Middleware;
use HtmlFirst\atlaAS\Router\_Routes;
use HtmlFirst\atlaAS\Utils\hasSetGlobal;
use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\__Request;
use HtmlFirst\atlaAS\Utils\__Response;
use HtmlFirst\atlaAS\Utils\_FunctionHelpers;
use HtmlFirst\atlaAS\Utils\_Temp;
use HtmlFirst\atlaAS\Vars\__Settings;
use HtmlFirst\atlaAS\Vars\__Env;

/**
 * use this class as entry point;
 * instantiate it, with extended __Env and __Settings as arguments;
 * then call run method;
 */
abstract class __atlaAS {
    use hasSetGlobal;
    protected static __atlaAS|null $__ = null;

    public static array $global = [];

    public static string $public_url_root;

    public static string $app_root;
    public function __construct(__Env $env, __Settings $settings) {
        if (static::$__ !== null) {
            return;
        }
        new __Request;
        $this::$app_root = \dirname(__Request::$public_path);
        $this::$public_url_root = __Request::$http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
        new __Response;
        $this->set_as_global();
    }
    private static FSRouter $fs_router;
    public static function run(): void {
        self::$__::$fs_router = new FSRouter();
        self::$__::$fs_router->run();
    }
    /**
     * render_get
     *
     * @param  string $route_file
     * - full path prefixed with '/', ends with file extention too, OR
     * - class_reference::class;
     * @param  array $uri_input
     * - array input for get method arguments;
     * @param  array $query_parameters
     * - associative array, assigned to route class property if any (for best practice);
     * @param  bool $inherit_query_parameters 
     * - rendered route will:
     * >- true:  inherit parent query parameter merge with $query_parameters;
     * >- false: use $query_parameters as new query parameters;
     * @return void
     */
    public static function render_get(
        $route_file,
        $uri_input = [],
        $query_parameters = [],
        $inherit_query_parameters = true
    ) {
        $uri_array = \array_filter(
            \explode(
                '/',
                \str_replace(
                    [
                        __Settings::$routes_class,
                        '\\',
                        __Settings::$routes_path,
                        '//',
                        '.' . __Settings::$system_file[0]
                    ],
                    [
                        '',
                        '/',
                        '',
                        '/',
                        ''
                    ],
                    $route_file
                )
            ),
            fn ($str) => $str !== ''
        );
        \array_push($uri_array, ...$uri_input);
        if ($inherit_query_parameters) {
            $query_parameters = \array_merge(__Request::$query_params_array, $query_parameters);
        }
        $reseters = _FunctionHelpers::callable_collections(
            _Temp::var(__Request::$method, 'get'),
            _Temp::var(__Request::$uri_array, $uri_array),
            _Temp::var(__Request::$query_params_array, $query_parameters)
        );
        self::$__::$fs_router->render(false);
        $reseters();
    }
    public static function assign_query_param_to_class_property(_Routes|_Middleware $class_instance) {
        $query_params = __Request::$query_params_array;

        foreach ($query_params as $name => $value) {
            if (\property_exists($class_instance, $name)) {
                $class_instance->$name = \htmlspecialchars($value);
            }
        }
    }
    /**
     * follow_up_params
     * - generate followup for ParamsReceiver and
     * - fallback using render(...args);
     *
     * @param callable|string $fallback : upon failing any $conditionals it will run:
     * - string:
     * > - full path prefixed with '/';
     * > - ends with file extention too;
     * - callable: $fallback(array $generated_fallback_arguments);
     * - after running any of the $fallback above, App will run exit();
     * @param  array $conditionals
     * - $fallback will be triggered when any $conditionals bool are false;
     * - [
     *      ...[
     *          bool, ['param_name' => 'warning message']
     *      ]
     * ]
     * - consider use __atlaAS::input_match(...args);
     * @param  array $add_to_fallback_args associative :
     * - [
     *      ... $new_param_name_to_send_as => $prop_of_the_class
     * ]
     * @param  bool $inherit_query_parameters 
     * - rendered route will:
     * >- true:  inherit parent query parameter merged with $query_parameters;
     * >- false: use $query_parameters as new query parameters;
     * @return array
     */
    public static function follow_up_params(
        string|callable $fallback,
        array $url_input = [],
        array $conditionals = [],
        array $query_parameter = [],
        bool $inherit_query_parameter = true
    ): void {
        $match = true;
        foreach ($conditionals as $data) {
            [$conditional, $if_meet_merge] = $data;
            if ($conditional) {
                continue;
            }
            $query_parameter = \array_merge($query_parameter, $if_meet_merge);
            $match = false;
        }
        if ($match) {
            return;
        }
        if (\is_array($fallback)) {
            __atlaAS::render_get($fallback, $url_input, $query_parameter, $inherit_query_parameter);
        } else {
            $fallback($query_parameter);
        }
        exit(0);
    }
    /**
     * input_match
     *
     * @param  string $input_name: key of method parameter
     * @param  string $regex
     * @return bool
     */
    public static function input_match(string $regex, string $input_name): bool {
        if (self::$__::$fs_router::$form_s_input_param === null) {
            self::$__::$fs_router::$form_s_input_param = __Request::method_params();
        }
        return \preg_match($regex, self::$__::$fs_router::$form_s_input_param[$input_name]);
    }
    public static function reroute(string $path, array $url_input = [], $use_client_side_routing = false): void {
        if (\count($url_input) >= 1) {
            $path .= '/' . \join($url_input);
        }
        if ($use_client_side_routing) {
            __Response::echo_json_api([
                __Settings::$client_reroute_key => $path
            ]);
        }
        \header("location: $path");
        exit(0);
    }
    public static function set_error_header(int $code = 404): void {
        $header = match ($code) {
            403 => 'HTTP/1.1 403 Forbidden',
            500 => 'HTTP/1.0 500 Internal Server Error',
            404 => 'HTTP/1.0 404 Not Found',
            default => 'HTTP/1.0 404 Not Found'
        };
        \header($header);
    }
    public static function reroute_error(int $code = 404): void {
        $code = match ($code) {
            403, 404, 500 => $code,
            default => 404,
        };
        self::$__::set_error_header($code);
        self::$__::reroute(__Settings::$routes_errors_prefix . $code);
    }
    public static function get_api_key(): string {
        return \array_keys(__Env::$api['check'])[0];
    }
}
