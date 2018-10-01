ALTER TABLE `app_users` ADD `is_admin` TINYINT(1) NOT NULL DEFAULT '0' ;

ALTER TABLE `app_users` ADD `session_id` VARCHAR(50) NOT NULL ;

ALTER TABLE `app_users` ADD `session_app_id` VARCHAR(50) NOT NULL ;