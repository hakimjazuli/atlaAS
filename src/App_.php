<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\FunctionHelpers;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Utils\Temp_;
use HtmlFirst\atlaAS\Vars\AppSettings_;
use HtmlFirst\atlaAS\Vars\AppEnv_;

class App_ {
    public array $global = [];

    public Request $request;
    public string $app_root;
    public string $public_url_root;
    public AppEnv_ $app_env;
    public AppSettings_ $app_settings;
    public function __construct(string $app_env_ref, string $app_settings_ref) {
        $this->app_env = new $app_env_ref;
        $this->app_settings = new $app_settings_ref($this->app_env);
        $this->request = new Request;
        $this->app_root = \dirname($this->request->public_path);
        $this->public_url_root = $this->request->http_mode . '://' . $_SERVER['HTTP_HOST'] . '/';
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
        $reseters = FunctionHelpers::callable_collections(
            Temp_::var($this->request->method, 'get'),
            Temp_::var($this->request->uri_array, $route_array_path),
            Temp_::var($this->request->query_params_arrray, $query_parameter)
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
     * - array: $this->app->render_get(array $fallback, array $generated_query_parameter);
     * - callable: $fallback(array $generated_fallback_arguments);
     * - after running any of the $fallback above, App will run exit(0);
     * @param  array $conditionals
     * - $fallback will be triggered when any $conditionals bool are false;
     * - [
     *      ...[
     *          bool, ['param_name' => 'warning message']
     *      ]
     * ]
     * - consider use $this->app->param_match(...args);
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
        return \preg_match($regex, $this->request->query_params_arrray[$param_name]);
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
        self::reroute($this->app_settings::$routes_errors_prefix . $code);
    }
    public function get_api_key(): string {
        return \array_keys($this->app_env::$api['check'])[0];
    }
}
