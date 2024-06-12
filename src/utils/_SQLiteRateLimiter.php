<?php

namespace HtmlFirst\atlaAS\Utils;

use HtmlFirst\atlaAS\__atlaAS;
use HtmlFirst\atlaAS\Vars\__Settings;
use PDO;

class _SQLiteRateLimiter {
    /**
     * @param  string $dbPath
     * @param  float $rate_limit
     * @param  float $time_window : in seconds
     * @return void
     */
    public static function limit(string $dbPath, float $rate_limit = 100, float $time_window = 60, string|null $clientId = null): void {
        $clientId ??= $_SERVER['REMOTE_ADDR'];
        $dsn = "sqlite:" . __Settings::system_path(__atlaAS::$app_root . $dbPath);
        $pdo = new PDO($dsn);
        $pdo->exec('CREATE TABLE IF NOT EXISTS rate_limits (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                client_id TEXT NOT NULL,
                request_count INTEGER NOT NULL,
                window_start INTEGER NOT NULL
            );');
        $currentTime = time();
        $windowStart = intval($currentTime / $time_window) * $time_window;
        $stmt = $pdo->prepare('SELECT request_count FROM rate_limits WHERE client_id = :client_id AND window_start = :window_start');
        $stmt->execute([
            ':client_id' => $clientId,
            ':window_start' => $windowStart
        ]);
        $rateLimit = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rateLimit) {
            if ($rateLimit['request_count'] >= $rate_limit) {
                http_response_code(429);
                __Response::echo_json_api([
                    'code' => 429,
                    'status' => 'not enough resource',
                    'message' => 'try again later'
                ]);
                return;
            }
            $stmt = $pdo->prepare('DELETE FROM rate_limits WHERE client_id = :client_id AND window_start <> :window_start');
            $stmt->execute([
                ':client_id' => $clientId,
                ':window_start' => $windowStart
            ]);
            $stmt = $pdo->prepare('UPDATE rate_limits SET request_count = request_count + 1 WHERE client_id = :client_id AND window_start = :window_start');
            $stmt->execute([
                ':client_id' => $clientId,
                ':window_start' => $windowStart
            ]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO rate_limits (client_id, request_count, window_start) VALUES (:client_id, 1, :window_start)');
            $stmt->execute([
                ':client_id' => $clientId,
                ':window_start' => $windowStart
            ]);
        }
    }
}
