CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `client_id` TEXT NOT NULL,
    `request_count` INTEGER NOT NULL,
    `window_start` INTEGER NOT NULL
);