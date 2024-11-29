INSERT INTO
    `rate_limits` (`client_id`, `request_count`, `window_start`)
VALUES
    (:client_id, 1, :window_start)