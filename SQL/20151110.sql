ALTER TABLE `template`
ADD COLUMN `type`  tinyint(4) NOT NULL DEFAULT 1 COMMENT '1-email;2-file;' AFTER `id`;
INSERT INTO `template` (`id`, `type`, `tpl_id`, `tpl_name`, `platform_id`, `description`, `subject`, `bcc`, `cc`, `reply_to`, `from`, `tpl_file_name`, `tpl_alt_file_name`, `message_html`, `message_alt`, `status`, `create_on`, `create_at`, `create_by`, `modify_on`, `modify_at`, `modify_by`) VALUES (NULL, '2', 'delivery_note', 'Delivery Note', 'WEBAU', 'Delivery Note', '', '', '', '', '', 'delivery_note.html', '', NULL, NULL, '1', NOW(), '2130706433', 'willzhang', NOW(), '2130706433', 'willzhang');