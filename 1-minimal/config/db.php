<?php

/**
 * Configuration file for: Database Connection 
 * This is the place where your database login constants are saved
 * 
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/questions/2447791/define-vs-const
 */


/** database host, usually it's "127.0.0.1" or "localhost", some servers also need port info, like "127.0.0.1:8080" */
define("DB_HOST", "127.0.0.1");

/** name of the database. please note: database and database table are not the same thing! */
define("DB_NAME", "login");

/** user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT.
/** By the way, it's bad style to use "root", but for development it will work */
define("DB_USER", "root");

/** The password of the above user */
define("DB_PASS", "mysql");
