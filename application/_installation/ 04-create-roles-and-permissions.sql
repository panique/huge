--User Permission

CREATE TABLE IF NOT EXISTS `roles` (
 `role_id` int(3) NOT NULL AUTO_INCREMENT,
 `role_inherit_id` int(3) NOT NULL AUTO_INCREMENT,
 `role_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
 PRIMARY KEY (`role_id`),
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `permissions` (
 `permission_id` int(11) NOT NULL AUTO_INCREMENT,
 `role_id` int(3) NOT NULL,
 `permission_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
 `permission_granted` tinyint(1) NOT NULL,
 PRIMARY KEY (`role_id`),
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
