<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Vars\__Settings;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * @see
 * - contains method(s) for file serving related functionalities;
 */
final class _FileServer {
    private static string|null $log_dir = null;

    public static function log(string $prefix, array $content): null|string {
        $prefix = preg_replace('/[\/\\\\:*?"<>|]/', '', $prefix);
        $prefix = preg_replace('/\s+/', '_', $prefix);
        $prefix = trim($prefix);
        $log_dir = _FileServer::$log_dir;
        if (!$log_dir) {
            $log_dir = _FileServer::$log_dir = __atlaAS::$__->app_root . \DIRECTORY_SEPARATOR .  __Settings::$__->app_log;
        }
        if (!\is_dir($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        $content = \json_encode($content);
        if (!$content) {
            return null;
        }
        $log_path = $log_dir . \DIRECTORY_SEPARATOR . $prefix . "-" . time() . ".json";
        file_put_contents($log_path, $content);
        return $log_path;
    }
    /**
     * file_version
     * get html absolute path,
     * 
     * @param  string $server_absolute_path
     * - must be on the routes path
     * @param  string $with_file_version
     * - default: true;
     * @return string
     */
    public static function resource_path(string $server_absolute_path, bool $with_file_version = true): string {
        $public_uri = \str_replace(
            ['/' . __Settings::$__->routes_path, '/index'],
            ['', ''],
            $server_absolute_path
        );
        if (!$with_file_version) {
            return $public_uri;
        }
        $version =   \filemtime(
            __Settings::$__->system_path(__atlaAS::$__->app_root . $server_absolute_path)
        );
        return "$public_uri?t=$version";
    }
    /**
     * recurse_dir_and_path
     * 
     * @param  string $path
     * @param  callable|null $callback_file
     * - callable: will call $callback($file_or_dir); for each files detected in the $path
     * - null with $callback_dir: will returns array
     * @param  callable|null $callback_dir
     * - callable: will call $callback($file_or_dir); for each dirs detected in the $path
     * - null with $callback_file: will returns array
     * @return void|array
     * - array: if $callback_file AND $callback_dir is null;
     * - void: if $callback_file OR $callback_dir is callable
     */
    public static function recurse_dir_and_path(string $path, null|callable $callback_file = null, null|callable $callback_dir = null) {
        $path = __Settings::$__->system_path(__atlaAS::$__->app_root . $path);
        $recurvecontainer = new RecursiveDirectoryIterator($path);
        $files_and_dirs = new RecursiveIteratorIterator($recurvecontainer);
        if ($callback_file === null && $callback_dir === null) {
            return $files_and_dirs;
        }
        foreach ($files_and_dirs as $file_or_dir) {
            if (\is_file($file_or_dir)) {
                $callback_file($file_or_dir);
            } elseif (\is_dir($file_or_dir)) {
                $callback_dir($file_or_dir);
            }
        }
    }
}
