<?php

/**
 * This is a helper file that simply outputs the content of the users.db file.
 * Might be useful for your development.
 */

// error reporting config
error_reporting(E_ALL);

// config
$db_type = "sqlite";
$db_sqlite_path = "../database/users.db";

// create new database connection
$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

// query
$sql = 'SELECT * FROM users';

// execute query
$query = $db_connection->prepare($sql);
$query->execute();

// show all the data from the "users" table inside the database
var_dump($query->fetchAll());
