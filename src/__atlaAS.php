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
     * @param  null|array $route_array_path
     * - null: base routing;
     * - array: one dimentional array to route url;
     * @param  null|array $query_parameter
     * - associative array, assigned to route class property if any (for best practice);
     * - null do nothing;
     * @return void
     */
    public function render_get(null|array $route_array_path = null, null|array $query_parameter = null) {
        $reseters = _FunctionHelpers::callable_collections(
            _Temp::var(__Request::$__->method, 'get'),
            _Temp::var(__Request::$__->uri_array, $route_array_path),
            _Temp::var(__Request::$__->query_params_arrray, $query_parameter)
        );
        $this->fs_router->render();
        $reseters();
    }
    /**
     * follow_up_params
     * - generate followup for ParamsReceiver and
     * - fallback using render(...args);
     *
     * @param callable|array $fallback : upon failing any $conditionals it will run:
     * - array: App_::$instance->render_get(array $fallback, array $generated_query_parameter);
     * - callable: $fallback(array $generated_fallback_arguments);
     * - after running any of the $fallback above, App will run exit(0);
     * @param  array $conditionals
     * - $fallback will be triggered when any $conditionals bool are false;
     * - [
     *      ...[
     *          bool, ['param_name' => 'warning message']
     *      ]
     * ]
     * - consider use App_::$instance->param_match(...args);
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
        $this->fs_router->follow_up_params($fallback, $conditionals, $add_to_fallback_args);
    }
    /**
     * param_match
     *
     * @param  string $param_name : key of method parameter
     * @param  string $regex
     * @return bool
     */
    public function param_match(string $regex, string $param_name): bool {
        return \preg_match($regex, __Request::$__->query_params_arrray[$param_name]);
    }
    public static function reroute(string $path): void {
        \header("location: $path");
        exit(0);
    }
    public static function set_error_header(int $code = 404): void {
        switch ($code) {
            case 403:
                \header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                \header("HTTP/1.0 404 Not Found");
                break;
            case 500:
                \header("HTTP/1.0 500 Internal Server Error");
                break;
        }
    }
    public function reroute_error(int $code = 404): void {
        switch ($code) {
            case 403:
            case 404:
            case 500:
                break;
            default:
                $code = 404;
                break;
        }
        self::set_error_header($code);
        self::reroute(__Settings::$routes_errors_prefix . $code);
    }
    public function get_api_key(): string {
        return \array_keys(__Env::$api['check'])[0];
    }
}
