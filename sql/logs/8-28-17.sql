ALTER TABLE `app_users` ADD `mobile` VARCHAR(15) NOT NULL AFTER `firstname`, ADD `address` VARCHAR(125) NOT NULL AFTER `mobile`;

ALTER TABLE `app_users` ADD `is_citizen` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_admin`;