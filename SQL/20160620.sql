INSERT INTO `application` (`id`, `app_name`, `parent_app_id`, `description`, `display_order`, `status`, `display_row`, `url`, `app_group_id`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('CRN0035', 'Daily Duplicate purchase detection', NULL, 'Daily Duplicate purchase detection', 0, 1, 0, 'cron/cron_duplicate_purchase.php', NULL, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang');
INSERT INTO `rights` (`app_id`, `rights`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('CRN0035', '', 1, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang');
