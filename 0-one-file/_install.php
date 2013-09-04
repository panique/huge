<?php

error_reporting(E_ALL);

$db_type = "sqlite";
$db_sqlite_path = "database/users.db";

$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

$sql = 'CREATE TABLE IF NOT EXISTS `users` (
        `user_id` INTEGER PRIMARY KEY,
        `user_name` varchar(64),
        `user_password_hash` varchar(255),
        `user_email` varchar(64));
        CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);';

$query = $db_connection->prepare($sql);
$query->execute();