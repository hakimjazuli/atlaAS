CREATE TABLE IF NOT EXISTS `log` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `context` TEXT,
    `time_stamp` INTEGER,
    `valid_until` INTEGER,
    `message` TEXT
)