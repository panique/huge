CREATE TABLE IF NOT EXISTS `huge`.`emails` (
 `email_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing email_id of each email, unique index',
 `user_id` int(11) unsigned NOT NULL,
 `email_address` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'email address, unique',
 `email_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'default email, 0 = no, 1 = yes',
 `email_notify` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email notifications, 0 = off, 1 = on',
 `email_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'email activation status',
 `email_activation_hash` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'email verification hash string',   
 `email_creation_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the creation of email',  
 PRIMARY KEY (`email_id`),
 UNIQUE KEY `email_address` (`email_address`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';
