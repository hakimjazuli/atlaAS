<?php


namespace HtmlFirst\atlaAS\utils;

use HtmlFirst\__atlaAS\Utils\VideoStream;
use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Vars\__Settings;

/**
 * @see
 * - [internal class](#internals)
 */
trait hasServes {
  /**
   * serves
   * 
   * @param  array $server_url
   * @param  string $system_dir
   * prefix with '/'
   * @param  bool $force_download
   * @return void
   */
  protected static function serves(array $server_url, string $system_dir, $force_download = false): void {
    $file = __Settings::$__->system_path(__atlaAS::$__->app_root . $system_dir . '/' . join('/', $server_url));
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
  private static function unix_unit_to_days(float $days): float {
    return $days * 86400/* 60 * 24 * 60 */;
  }
  private static function caching(float $days = 60, bool $force_cache = false): void {
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
  private static function file_handler(string $filename, bool $use_stream = false, bool $force_download = false): void {
    self::caching(__Settings::$__->use_caching()[1]);
    $content_type = self::header_file_type($filename);
    if ($force_download) {
      self::download_force($filename);
    } elseif ($use_stream && str_starts_with($content_type, 'video')) {
      $stream = new VideoStream($filename);
      $stream->start();
      return;
    }
    /**
     * readfile|require will automatically echo the result 
     */
    if (__Settings::$__->load_file_with_php_require) {
      require $filename;
      return;
    }
    readfile($filename);
  }
  private static function page_resource_handler(string $file, bool $force_download = false): string {
    $file_ext = pathinfo($file, PATHINFO_EXTENSION);
    if (in_array($file_ext, _FunctionHelpers::merge_unique_1d_array(__Settings::$__->system_file, ['php']))) {
      return 'is_system_file';
    }
    if (is_file(__Settings::$__->system_path($file))) {
      self::file_handler($file, __Settings::$__->use_stream, $force_download);
      return 'is_resource_file';
    }
    return 'not_found';
  }
  private static function header_file_type(string $filename): string {
    $file_size = filesize($filename);
    \header('Accept-Ranges: bytes');
    \header("Content-Length: $file_size");
    $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
    $content_type = match ($file_ext) {
      'js', 'mjs' => 'application/javascript',
      'png' => 'image/png',
      'bmp' => 'image/bmp',
      'gif' => 'image/gif',
      'tif', 'tiff' => 'image/tiff',
      'jpg', 'jpeg' => 'image/jpeg',
      'svg' => 'image/svg+xml',
      'ico' => 'image/x-icon',
      'mp4' => 'video/mp4',
      '3gp' => 'video/3gpp',
      'avi' => 'video/x-msvideo',
      'flv' => 'video/x-flv',
      'm4v' => 'video/x-m4v',
      'mkv' => 'video/x-matroska',
      'mov' => 'video/quicktime',
      'wmv' => 'video/x-ms-wmv',
      'webm' => 'video/webm',
      'html' => 'text/html',
      'css' => 'text/css',
      'woff' => 'text/woff',
      'json' => 'text/json',
      'wav' => 'audio/wav',
      'amr' => 'audio/amr',
      'flac' => 'audio/flac',
      'm4a' => 'audio/m4a',
      'midi' => 'audio/midi',
      'mp3' => 'audio/mpeg',
      'ogg' => 'audio/ogg',
      'map' => 'application/json',
      'wasm' => 'application/wasm',
      'pdf' => 'application/pdf',
      'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
      'rar' => 'application/x-rar-compressed',
      'swf' => 'application/x-shockwave-flash',
      'tar' => 'application/x-tar',
      'xls' => 'application/vnd.ms-excel',
      'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
      'xml' => 'application/xml',
      'zip' => 'application/zip',
      'psd' => 'image/vnd.adobe.photoshop',
      'ttf' => 'font/ttf',
      'apk' => 'application/vnd.android.package-archive',
      'cab' => 'application/vnd.ms-cab-compressed',
      'dmg' => 'application/x-apple-diskimage',
      'iso' => 'application/x-iso9660-image',
      'ppt' => 'application/vnd.ms-powerpoint',
      'rm' => 'audio/x-pn-realaudio',
      default => mime_content_type($filename),
    };
    \header("Content-Type: $content_type");
    return $content_type;
  }
}
