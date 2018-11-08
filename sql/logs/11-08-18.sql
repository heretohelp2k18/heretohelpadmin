ALTER TABLE `app_users` ADD `is_superadmin` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_admin`, ADD `skipchatbot` TINYINT(1) NOT NULL DEFAULT '0' AFTER `is_superadmin`;

UPDATE `app_users` SET `is_superadmin` = '1' WHERE `app_users`.`id` = 1;