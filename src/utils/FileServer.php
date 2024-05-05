<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Vars\__Settings;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileServer {
    public function file_version(string $public_uri): string {
        $version = $public_uri . '?t=' . \filemtime(__atlaAS::$__->app_root . \DIRECTORY_SEPARATOR . __Settings::$routes_path . \DIRECTORY_SEPARATOR . trim($public_uri, '/'));
        return $version;
    }
    /**
     * recurse_dir_and_path
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
    public function recurse_dir_and_path(string $path, null|callable $callback_file = null, null|callable $callback_dir = null) {
        $recurvecontainer = new RecursiveDirectoryIterator($path);
        $files_and_dirs = new RecursiveIteratorIterator($recurvecontainer);
        if ($callback_file === null && $callback_dir === null) {
            return $files_and_dirs;
        }
        foreach ($files_and_dirs as $file_or_dir) {
            if (\is_file(__Settings::system_path($file_or_dir))) {
                $callback_file($file_or_dir);
            } elseif (\is_dir(__Settings::system_path($file_or_dir))) {
                $callback_dir($file_or_dir);
            }
        }
    }
    /**
     * map_resource
     * @param  array $relative_path
     * @param  string $mapper_directory
     * @param  bool $force_download
     * @return void
     */
    public function map_resource(array $relative_path, string $mapper_directory, $force_download = false): void {
        $file = __Settings::system_path($mapper_directory . '/' . join('/', $relative_path));
        $resource = self::page_resource_handler($file, $force_download);
        switch ($resource) {
            case 'is_resource_file':
                break;
            case 'is_system_file':
            case 'not_found':
                __atlaAS::$__->reroute_error(404);
                break;
        }
    }
    private static function unix_unit_to_days($days): float {
        return $days * 86400/* 60 * 24 * 60 */;
    }
    private static function header_file_type(string $filename): string {
        $file_size = filesize($filename);
        \header('Accept-Ranges: bytes');
        \header("Content-Length: $file_size");
        $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
        switch ($file_ext) {
            case 'js':
            case 'mjs':
                $content_type = 'application/javascript';
                break;
            case 'png':
                $content_type = 'image/png';
                break;
            case 'bmp':
                $content_type = 'image/bmp';
                break;
            case 'gif':
                $content_type = 'image/gif';
                break;
            case 'mp4':
                $content_type = 'video/mp4';
                break;
            case 'tif':
            case 'tiff':
                $content_type = 'image/tiff';
                break;
            case 'jpg':
            case 'jpeg':
                $content_type = 'image/jpeg';
                break;
            case 'svg':
                $content_type = 'image/svg+xml';
                break;
            case 'ico':
                $content_type = 'image/x-icon';
                break;
            case '3gp':
                $content_type = 'video/3gpp';
                break;
            case 'avi':
                $content_type = 'video/x-msvideo';
                break;
            case 'flv':
                $content_type = 'video/x-flv';
                break;
            case 'm4v':
                $content_type = 'video/x-m4v';
                break;
            case 'mkv':
                $content_type = 'video/x-matroska';
                break;
            case 'mov':
                $content_type = 'video/quicktime';
                break;
            case 'wmv':
                $content_type = 'video/x-ms-wmv';
                break;
            case 'webm':
                $content_type = 'video/webm';
                break;
            case 'html':
                $content_type = 'text/html';
                break;
            case 'css':
                $content_type = 'text/css';
                break;
            case 'woff':
                $content_type = 'text/woff';
                break;
            case 'json':
                $content_type = 'text/json';
                break;
            case 'wav':
                $content_type = 'audio/wav';
                break;
            case 'amr':
                $content_type = 'audio/amr';
                break;
            case 'flac':
                $content_type = 'audio/flac';
                break;
            case 'm4a':
                $content_type = 'audio/m4a';
                break;
            case 'midi':
                $content_type = 'audio/midi';
                break;
            case 'mp3':
                $content_type = 'audio/mpeg';
                break;
            case 'ogg':
                $content_type = 'audio/ogg';
                break;
            case 'map':
                $content_type = 'application/json';
                break;
            case 'wasm':
                $content_type = 'application/wasm';
                break;
            case 'pdf':
                $content_type = 'application/pdf';
                break;
            case 'pptx':
                $content_type = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
                break;
            case 'rar':
                $content_type = 'application/x-rar-compressed';
                break;
            case 'swf':
                $content_type = 'application/x-shockwave-flash';
                break;
            case 'tar':
                $content_type = 'application/x-tar';
                break;
            case 'xls':
                $content_type = 'application/vnd.ms-excel';
                break;
            case 'xlsx':
                $content_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                break;
            case 'xml':
                $content_type = 'application/xml';
                break;
            case 'zip':
                $content_type = 'application/zip';
                break;
            case 'psd':
                $content_type = 'image/vnd.adobe.photoshop';
                break;
            case 'ttf':
                $content_type = 'font/ttf';
                break;
            case 'apk':
                $content_type = 'application/vnd.android.package-archive';
                break;
            case 'cab':
                $content_type = 'application/vnd.ms-cab-compressed';
                break;
            case 'dmg':
                $content_type = 'application/x-apple-diskimage';
                break;
            case 'iso':
                $content_type = 'application/x-iso9660-image';
                break;
            case 'ppt':
                $content_type = 'application/vnd.ms-powerpoint';
                break;
            case 'rm':
                $content_type = 'audio/x-pn-realaudio';
                break;
            default:
                $content_type = mime_content_type($filename);
                break;
        }
        \header("Content-Type: $content_type");
        return $content_type;
    }
    private function caching(float $days = 60, bool $force_cache = false): void {
        if (__Settings::$__->use_caching()[0] || $force_cache) {
            $expires = self::unix_unit_to_days($days);
            \header('Pragma: public');
            \header("Cache-Control: max-age=$expires");
            \header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
        }
    }
    private static function download_force(string $path): void {
        $path = basename($path);
        \header('Content-Type: aplication/octet-stream');
        \header('Content-Transfer-Encoding: Binary');
        \header("Content-disposition: filename=$path");
    }
    private function file_handler(string $filename, bool $use_stream = false, bool $force_download = false): void {
        self::caching(__Settings::$__->use_caching()[1]);
        $content_type = self::header_file_type($filename);
        \header('Accept-Ranges: bytes');
        $file_size = filesize($filename);
        if ($force_download) {
            self::download_force($filename);
        } elseif ($use_stream && str_starts_with($content_type, 'video') && !$force_download) {
            $stream = new VideoStream($filename);
            $stream->start();
            return;
        } else {
            \header("Content-Length: $file_size");
        }
        if (__Settings::$load_file_with_php_require) {
            require $filename;
            return;
        }
        /** readfile|require will automatically echo the result */
        readfile($filename);
    }
    private function page_resource_handler(string $file, bool $force_download = false): string {
        $file_ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($file_ext == __Settings::$system_file) {
            return 'is_system_file';
        }
        if (is_file(__Settings::system_path($file))) {
            self::file_handler($file, __Settings::$use_stream, $force_download);
            return 'is_resource_file';
        }
        return 'not_found';
    }
}
