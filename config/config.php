<?php


/**
 * Database Connection 
 * This is the place where your database constants are saved
 */

define("DB_HOST", "localhost");
define("DB_NAME", "login");
define("DB_USER", "root");
define("DB_PASS", "");




// Allow non-authentificated people to create accounts.
define('PUBLIC_REGISTER', true);  //true or false


// This delay is used to massively slowdown bruteforcing attack. 
define('LOGIN_FAIL_DELAY', 2);  //number in sec.








