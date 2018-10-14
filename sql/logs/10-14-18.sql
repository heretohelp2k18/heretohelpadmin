ALTER TABLE `chatroom` ADD `chatdate` DATETIME NOT NULL AFTER `chatroom`;
ALTER TABLE `app_users` ADD `autoresponse` VARCHAR(225) NOT NULL AFTER `idimage`;