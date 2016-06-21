INSERT INTO `application` (`id`, `app_name`, `parent_app_id`, `description`, `display_order`, `status`, `display_row`, `url`, `app_group_id`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('ACC000207', 'RIA Control Report', NULL, 'RIA Control Report', 10, 1, 1, 'account/flex/riaControlReport', 9, now(), 2130706433, 'willzhang', now(), 2130706433, 'willzhang');
INSERT INTO `rights` (`app_id`, `rights`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('ACC000207', '', 1, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang');

INSERT INTO `role_rights` (`role_id`, `rights_id`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`)
VALUES
    ('admin', 248, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang'),
    ('acc_lead', 248, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang'),
    ('acc_man', 248, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang'),
    ('acc_staff', 248, now(), 127001001, 'will zhang', now(), 127001001, 'will zhang');


