<?php

/**
 * This is the installation file for the 0-one-file version of the php-login script.
 * It simply creates a new and empty database.
 */

// error reporting config
error_reporting(E_ALL);

// config
$db_type = "sqlite";
$db_sqlite_path = "database/users.db";

// create new database file / connection (the file will be automaticly created the first time a connection is made up)
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// create new empty table inside the database (if table does not already exist)
$sql = 'CREATE TABLE IF NOT EXISTS `users` (
        `user_id` INTEGER PRIMARY KEY,
        `user_name` varchar(64),
        `user_password_hash` varchar(255),
        `user_email` varchar(64));
        CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);
        CREATE UNIQUE INDEX `user_email_UNIQUE` ON `users` (`user_email` ASC);
        ';

// execute the above query
$query = $db_connection->prepare($sql);
$query->execute();

// check for success
if (file_exists($db_sqlite_path)) {
    echo "Database $db_sqlite_path exists, installation was successful.";
} else {
    echo "Database $db_sqlite_path does not exist, installation was NOT successful. Missing folder write rights ?";
}
