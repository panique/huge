<?php

/**
 * This is a helper file that simply outputs the content of the users.db file.
 * Might be useful for your development.
 */

error_reporting(E_ALL);

$db_type = "sqlite";
$db_sqlite_path = "database/users.db";

$db_connection = new PDO($db_type . ':' . $db_sqlite_path);

$sql = 'SELECT * FROM users';
$query = $db_connection->prepare($sql);
$query->execute();

var_dump($query->fetchAll());
