UPDATE
    `rate_limits`
SET
    `request_count` = `request_count` + 1
WHERE
    `client_id` = :client_id
    AND `window_start` = :window_start