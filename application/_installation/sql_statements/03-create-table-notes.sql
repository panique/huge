CREATE TABLE IF NOT EXISTS `login`.`notes` (
 `note_id` int(11) NOT NULL AUTO_INCREMENT,
 `note_text` text NOT NULL,
 `user_id` bigint(20) NOT NULL,
 PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
