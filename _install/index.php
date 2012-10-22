<?php

/**
 * This is the install file.
 * It's a simple one-file script that helps you installing the PHP Login Script.
 * Currently it does the following things:
 * 
 * 1. Checking your server / php version (do you have PHP 5.3+ ?)
 * 2. Checking the database connection: Can we connect to MySQL ?
 * 3. Checking your database: Does a database named "login" already exist ?
 * 4. If not, the script will:
 * 4a. Create the database "login"
 * 4b. Create the table "users" within that database (with columns user_id, user_name, user_password_hash and user_email)
 * 4c. OPTIONAL: you can create a demo user, named "demouser" with password "123"
 * 
 */


// 1. PHP version check
if (strnatcmp(phpversion(),'5.3.0') >= 0) {
    echo "YES";
} else { 
    echo "NO";
}

// WICHTIG!! Das funktioniert erst wenn die Datenbank besteht!!!
// 2. MySQL database connection check
require_once("../config/db.php");
require '../classes/Database.class.php';
$db = new Database();
if ($db->getDatabaseError() == 0) {
    echo "yes";
    $connection = $db->getDatabaseConnection();
} else {
    echo "no";
}

// 3. 
/*
$query_check_database_exists = $connection->query("SHOW DATABASES LIKE 'login';");
if ($query_check_database_exists->num_rows == 1) {
    echo "Database already exists";
} elseif ($query_check_database_exists->num_rows == 0) {
    echo "Database does not exist";
}
*/

$sql_create_database = "CREATE DATABASE IF NOT EXISTS 'login';";
$sql_create_table   = "CREATE TABLE IF NOT EXISTS `users` (
                        `user_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
                        `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name',
                        `user_password_hash` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s password in salted and hashed format',
                        `user_email` text COLLATE utf8_unicode_ci COMMENT 'user''s email',
                        PRIMARY KEY (`user_id`),
                        UNIQUE KEY `user_name` (`user_name`)
                      ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data' AUTO_INCREMENT=1 ;";

$query_create_database  = $connection->query($sql_create_database);
$query_create_table     = $connection->query($sql_create_table);


// check for created databases
$query_check_database_exists = $connection->query("SHOW DATABASES LIKE login;");
if ($query_check_database_exists->num_rows == 1) {
    echo "Database exists";
} elseif ($query_check_database_exists->num_rows == 0) {
    echo "Database does not exist";
}

$query_check_database_exists = $connection->query("SHOW TABLES LIKE login.users;");
if ($query_check_database_exists->num_rows == 1) {
    echo "Table exists";
} elseif ($query_check_database_exists->num_rows == 0) {
    echo "Table does not exist";
}

?>



<table>
    <tr>
        <td>REQUIREMENTS</td>
        <td></td>
    </tr>    
    <tr>
        <td>PHP 5.3+ installed ?</td>
        <td><?php  ?></td>
    </tr>
</table>