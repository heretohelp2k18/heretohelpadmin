ALTER TABLE `crime_reports` ADD `enabled` TINYINT(1) NOT NULL DEFAULT '1' AFTER `is_flag`;