<?php

/**
 * Configuration file for: Database Connection 
 * This is the place where your database login constants are saved
 * 
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * MySQL hostname
 * usually it's "127.0.0.1" or "localhost", some servers also need port info, like "127.0.0.1:8080"
 */
define('DB_HOST', 'host_here');

/**
 * MySQL database name
 * name of the database. please note: database and database table are not the same thing!
 */
define('DB_NAME', 'databasename_here');

/**
 * MySQL database username
 * user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
 */
define('DB_USER', 'username_here');

/** 
 * MySQL database password
 * The password of the above user
 */
define('DB_PASS', 'password_here');

/** 
 * Mysql database charset
 * Used when connecting to the database to better sanitize your data before insertion
 */
define('DB_CHARSET', 'utf8');
