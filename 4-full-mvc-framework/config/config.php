<?php

/**
 * THIS IS THE CONFIGURATION FILE
 * 
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 * If you want to know why we use "define" instead of "const" @see http://stackoverflow.com/q/2447791/1114320
 */

/**
 * Configuration for: Base URL
 * This is the base url of our app. if you go live with your app, put your full domain name here.
 * if you are using a (differen) port, then put this in here, like http://mydomain:8888/mvc/
 * TODO: when not using subfolder, is the trailing slash important ?
 */
define('URL', 'http://127.0.0.1/php-login/4-full-mvc-framework/');


/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
define('LIBS', 'libs/');


/**
 * Configuration for: Database
 * This is the place where you define your database credentials, type etc.
 * 
 * database type
 * define('DB_TYPE', 'mysql');
 * database host, usually it's "127.0.0.1" or "localhost", some servers also need port info, like "127.0.0.1:8080"
 * define('DB_HOST', '127.0.0.1');
 * name of the database. please note: database and database table are not the same thing!
 * define('DB_NAME', 'login');
 * user for your database. the user needs to have rights for SELECT, UPDATE, DELETE and INSERT
 * By the way, it's bad style to use "root", but for development it will work
 * define('DB_USER', 'root');
 * The password of the above user
 * define('DB_PASS', 'xxx');
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'login');
define('DB_USER', 'root');
define('DB_PASS', 'mysql');


/**
 * Configuration for: Hashing strength
 * This is the place where you define the strength of your password hashing/salting
 * 
 * To make password encryption very safe and future-proof, the PHP 5.5 hashing/salting functions
 * come with a clever so called COST FACTOR. This number defines the base-2 logarithm of the rounds of hashing,
 * something like 2^12 if your cost factor is 12. By the way, 2^12 would be 4096 rounds of hashing, doubling the
 * round with each increase of the cost factor and therefore doubling the CPU power it needs.
 * Currently, in 2013, the developers of this functions have chosen a cost factor of 10, which fits most standard
 * server setups. When time goes by and server power becomes much more powerful, it might be useful to increase
 * the cost factor, to make the password hashing one step more secure. Have a look here
 * (@see https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
 * in the BLOWFISH benchmark table to get an idea how this factor behaves. For most people this is irrelevant,
 * but after some years this might be very very useful to keep the encryption of your database up to date.
 * 
 * Remember: Every time a user registers or tries to log in (!) this calculation will be done.
 * Don't change this if you don't know what you do.
 * 
 * To get more information about the best cost factor please have a look here
 * @see http://stackoverflow.com/q/4443476/1114320
 */

// the hash cost factor, PHP's internal default is 10. You can leave this line
// commented out until you need another factor then 10.
define("HASH_COST_FACTOR", "10");


/**
 * Configuration for: Email server credentials
 * 
 * Here you can define how you want to send emails.
 * If you have successfully set up a mail server on your linux server and you know
 * what you do, then you can skip this section. Otherwise please set EMAIL_USE_SMTP to true
 * and fill in your SMTP provider account data.
 * 
 * It's really recommended to use SMTP!
 * 
 */
define("EMAIL_USE_SMTP", false);
define("EMAIL_SMTP_HOST", 'yourhost');
define("EMAIL_SMTP_AUTH", true); // leave this true until your SMTP can be used without login
define("EMAIL_SMTP_USERNAME", 'yourusername');
define("EMAIL_SMTP_PASSWORD", 'yourpassword');
// Enable encryption, 'ssl' also accepted (optional?)
//define("EMAIL_SMTP_ENCRYPTION", 'tls');                     


/**
 * Configuration for: Email content data
 * 
 * php-login uses the PHPMailer library, please have a look here if you want to add more
 * config stuff: @see https://github.com/PHPMailer/PHPMailer
 * 
 * As email sending within your project needs some setting, you can do this here:
 * 
 * Absolute URL to password reset action, necessary for email password reset links
 * define("EMAIL_PASSWORDRESET_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/passwordReset"); 
 * define("EMAIL_PASSWORDRESET_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_PASSWORDRESET_FROM_NAME", "My Project");
 * define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset for PROJECT XY");
 * define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password:");
 * 
 * absolute URL to verification action, necessary for email verification links
 * define("EMAIL_VERIFICATION_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/verify/");
 * define("EMAIL_VERIFICATION_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
 * define("EMAIL_VERIFICATION_SUBJECT", "Account Activation for PROJECT XY");
 * define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account:");
 */
define("EMAIL_PASSWORDRESET_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/verifypasswordrequest");
define("EMAIL_PASSWORDRESET_FROM_EMAIL", "noreply@example.com");
define("EMAIL_PASSWORDRESET_FROM_NAME", "My Project");
define("EMAIL_PASSWORDRESET_SUBJECT", "Password reset for PROJECT XY");
define("EMAIL_PASSWORDRESET_CONTENT", "Please click on this link to reset your password: ");

define("EMAIL_VERIFICATION_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/verify");
define("EMAIL_VERIFICATION_FROM_EMAIL", "noreply@example.com");
define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
define("EMAIL_VERIFICATION_SUBJECT", "Account Activation for PROJECT XY");
define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account: ");
