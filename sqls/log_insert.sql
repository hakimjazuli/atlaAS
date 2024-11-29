INSERT
    OR REPLACE INTO `log` (`context`, `time_stamp`, `valid_until`, `message`)
VALUES
    (:context, :time_stamp, :valid_until, :message)