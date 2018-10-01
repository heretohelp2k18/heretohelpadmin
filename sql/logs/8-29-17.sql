ALTER TABLE `crime_reports` ADD `user_id` INT NOT NULL AFTER `crime`;

ALTER TABLE `crime_reports` ADD `date_reported` DATETIME NOT NULL AFTER `image`;

ALTER TABLE `crime_reports` ADD `is_flag` TINYINT(1) NOT NULL AFTER `status`;

CREATE TABLE `icrime`.`crime_report_comments` ( `id` INT NOT NULL AUTO_INCREMENT , `crime_report_id` INT NOT NULL , `user_id` INT NOT NULL , `comment` TEXT NOT NULL , `date_added` DATETIME NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;