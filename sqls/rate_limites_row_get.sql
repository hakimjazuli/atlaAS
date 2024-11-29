SELECT
    `request_count`
FROM
    `rate_limits`
WHERE
    `client_id` = :client_id
    AND `window_start` = :window_start