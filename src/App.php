<?php

namespace HtmlFirst\atlaAS;

use HtmlFirst\atlaAS\Router\FSRouter;
use HtmlFirst\atlaAS\Utils\Request;
use HtmlFirst\atlaAS\Vars\AppSettings;
use HtmlFirst\atlaAS\Vars\AppEnv;

class App {
    public Request $request;
    public string $app_root;
    public string $public_url_root;
    public AppEnv $app_env;
    public AppSettings $app_settings;
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
        $this->fs_router->render('get', $route_array_path, $query_parameter);
    }
    /**
     * follow_up_params
     * - generate followup for ParamsReceiver and
     * - fallback using render(...args);
     *
     * @param  array $conditionals
     * - conditional will be triggered when bool are false;
     * - consider use $this->app->param_match(...args);
     * - [
     *      ...[
     *          bool, ['param_warning_receiver_name' => 'warning message']
     *      ]
     * ]
     * @param  array $add_to associative :
     * - [
     *      ... $new_param_name_to_send_as => $prop_of_the_class
     * ]
     * @param bool $url_fallback :
     * - null: use it's own class route to render as fallback;
     * - array: use public uri array;
     * @return array
     */
    public function follow_up_params(array $conditionals, array $add_to = [], array|null $url_fallback = null): void {
        $this->fs_router->follow_up_params($conditionals, $add_to, $url_fallback);
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
        self::reroute($this->app_settings->routes_errors_prefix . $code);
    }
}
