ALTER TABLE `crimes` ADD `type` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '1 - major, 2 - minor' AFTER `crime`;

ALTER TABLE `crimes` ADD `enabled` TINYINT(1) NOT NULL DEFAULT '1' AFTER `type`;