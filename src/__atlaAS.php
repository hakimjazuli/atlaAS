<?php

namespace HtmlFirst\atlaAS;

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
class __atlaAS {
    use hasSetGlobal;
    public static __atlaAS $__;

    public array $global = [];

    public string $app_root;
    public string $public_url_root;


    public function __construct(string $app_env_ref, string $app_settings_ref) {
        new $app_env_ref;
        new $app_settings_ref;
        new __Request;
        $this->app_root = \dirname(__Request::$__->public_path);
        $this->public_url_root = __Request::$__->http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
        new __Response;
        $this->set_as_global();
    }
    private FSRouter $fs_router;
    public function run(): void {
        $this->fs_router = new FSRouter($this);
        $this->fs_router->run();
        exit(0);
    }
    /**
     * render_get
     *
     * @param  null|array $class_ref_and_uri_input
     * - null: use the same __Request::$\_\_->uri_array where this method is called;
     * - array: [class_ref::class, ...$arguments_for_the_class_get_method];
     * @param  array $query_parameters
     * - associative array, assigned to route class property if any (for best practice);
     * @param  bool $inherit_query_parameters 
     * - rendered route will:
     * >- true:  inherit parent query parameter;
     * >- false: use $query_parameters as new query parameters;
     * @return void
     */
    public function render_get(
        null|array $class_ref_and_uri_input = null,
        array $query_parameters = [],
        bool $inherit_query_parameters = true
    ) {
        $class_reference = _FunctionHelpers::class_name_as_array($class_ref_and_uri_input[0], [__Settings::$__->routes_class]);
        \array_shift($class_ref_and_uri_input);
        $uri_array = \array_merge($class_reference, $class_ref_and_uri_input);
        if ($inherit_query_parameters) {
            $query_parameters = \array_merge(__Request::$__->query_params_arrray, $query_parameters);
        }
        $reseters = _FunctionHelpers::callable_collections(
            _Temp::var(__Request::$__->method, 'get'),
            _Temp::var(__Request::$__->uri_array, $uri_array),
            _Temp::var(__Request::$__->query_params_arrray, $query_parameters)
        );
        $this->fs_router->render();
        $reseters();
    }
    public function assign_query_param_to_class_property(object $class_instance) {
        $query_params = __Request::$__->query_params_arrray;
        foreach ($query_params as $name => $value) {
            if (\property_exists($class_instance, $name)) {
                $class_instance->$name = $value;
            }
        }
    }
    /**
     * follow_up_params
     * - generate followup for ParamsReceiver and
     * - fallback using render(...args);
     *
     * @param callable|array $fallback : upon failing any $conditionals it will run:
     * - array: [route_class_ref::class, ...$arguments_for_the_class_get_method];
     * - callable: $fallback(array $generated_fallback_arguments);
     * - after running any of the $fallback above, App will run exit(0);
     * @param  array $conditionals
     * - $fallback will be triggered when any $conditionals bool are false;
     * - [
     *      ...[
     *          bool, ['param_name' => 'warning message']
     *      ]
     * ]
     * - consider use __atlaAS::$\_\_->param_match(...args);
     * @param  array $add_to_fallback_args associative :
     * - [
     *      ... $new_param_name_to_send_as => $prop_of_the_class
     * ]
     * @return array
     */
    public function follow_up_params(
        array|callable $fallback,
        array $conditionals,
        array $add_to_fallback_args = [],
    ): void {
        FSRouter::follow_up_params($fallback, $conditionals, $add_to_fallback_args);
    }
    /**
     * param_match
     *
     * @param  string $param_name: key of method parameter
     * @param  string $regex
     * @return bool
     */
    public function param_match(string $regex, string $param_name): bool {
        return \preg_match($regex, __Request::$__->query_params_arrray[$param_name]);
    }
    public function reroute(string $path): void {
        \header("location: $path");
        exit(0);
    }
    public function set_error_header(int $code = 404): void {
        $header = match ($code) {
            403 => 'HTTP/1.1 403 Forbidden',
            500 => 'HTTP/1.0 500 Internal Server Error',
            404 => 'HTTP/1.0 404 Not Found',
            default => 'HTTP/1.0 404 Not Found'
        };
        \header($header);
    }
    public function reroute_error(int $code = 404): void {
        $code = match ($code) {
            403, 404, 500 => $code,
            default => 404,
        };
        $this->set_error_header($code);
        $this->reroute(__Settings::$__->routes_errors_prefix . $code);
    }
    public function get_api_key(): string {
        return \array_keys(__Env::$__->api['check'])[0];
    }
}
