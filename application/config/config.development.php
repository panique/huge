<?php

// TODO un-pollute global namespace by putting this into a class or into an array
// TODO maybe return = array('this' => 1, 'that' => 333) is the way to go here

/**
 * Configuration for DEVELOPMENT environment
 * To create another configuration set just copy this file to config.test.php, config.production.php etc.
 * You get the idea :)
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production.
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/**
 * Configuration for: Base URL
 * This detects your URL/IP incl. sub-folder automatically. You can also deactivate auto-detection and provide the URL
 * manually. Note: The trailing slash is important!
 */
define('AUTODETECT_URL', true);
define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', 'http://');

// TODO trailing slash handling is not perfect yet
if (AUTODETECT_URL) {
	define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
	define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
	define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
} else {
	// manually set domain, without protocol, without trailing slash
	define('URL_DOMAIN', '192.168.33.44');
	define('URL_SUB_FOLDER', '/');
	define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);
}

/**
 * Configuration for: Folders
 * Usually there's no reason to change this.
 */
define('PATH_BASE',        realpath(dirname(__FILE__).'/../../'));
define('PATH_APPLICATION', PATH_BASE . '/application/');
define('PATH_CORE',        PATH_APPLICATION . '/core/');
define('PATH_CONTROLLER',  PATH_APPLICATION . '/controller/');
define('PATH_MODEL',       PATH_APPLICATION . '/model/');
define('PATH_VIEW',        PATH_APPLICATION . '/view/');

// internal path to save avatars. make sure this folder is writable. the slash at the end is VERY important!
define('PATH_AVATARS',     PATH_BASE . '/public/avatars/');
// public path to avatars
define('PATH_AVATARS_PUBLIC', 'avatars/');

/**
 * Configuration for: Default actions
 * This defines what should be called (controller and/or action) when URL does not provide a controller / action.
 * Make sure the controller is like "index", NOT like "IndexController".
 */
define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_ACTION', 'index');

/**
 * Configuration for: Additional login providers: Facebook
 * CURRENTLY REMOVED (as Facebook has removed support for the used API version).
 * Another, better and up-to-date implementation might come soon.
 */
define('FACEBOOK_LOGIN', false);

/**
 * Configuration for: Avatars/Gravatar support
 * Set to true if you want to use "Gravatar(s)", a service that automatically gets avatar pictures via using email
 * addresses of users by requesting images from the gravatar.com API. Set to false to use own locally saved avatars.
 * AVATAR_SIZE set the pixel size of avatars/gravatars (will be 44x44 by default). Avatars are always squares.
 * AVATAR_DEFAULT_IMAGE is the default image in public/avatars/
 */
define('USE_GRAVATAR', false);
define('GRAVATAR_DEFAULT_IMAGESET', 'mm');
define('GRAVATAR_RATING', 'pg');
define('AVATAR_SIZE', 44);
define('AVATAR_JPEG_QUALITY', 85);
define('AVATAR_DEFAULT_IMAGE', 'default.jpg');

/**
 * Configuration for: Captcha size
 * The currently used Captcha generator (https://github.com/Gregwar/Captcha) also runs without giving a size,
 * so feel free to use ->build(); inside CaptchaModel.
 */
define('CAPTCHA_WIDTH', 359);
define('CAPTCHA_HEIGHT', 100);

/**
 * Configuration for: Cookies
 * @see http://stackoverflow.com/q/9618217/1114320
 * @see php.net/manual/en/function.setcookie.php
 */
// 1209600 seconds = 2 weeks
define('COOKIE_RUNTIME', 1209600);
// default path the cookie is valid on. Usually "/" to make it valid on the whole domain.
define('COOKIE_PATH', '/');

/**
 * Configuration for: Database
 * DB_TYPE The used database type. Note that other types than "mysql" might break the construction in Database.php
 * DB_HOST The mysql hostname, usually localhost or 127.0.0.1
 * DB_NAME The database name
 * DB_USER The username
 * DB_PASS The password
 * DB_PORT The mysql port, 3306 by default (?), find out via phpinfo() and look for mysqli.default_port.
 * DB_CHARSET The charset, necessary for security reasons. Check Database.php class for more info.
 */
define('DB_TYPE', 'mysql');
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'login');
define('DB_USER', 'root');
define('DB_PASS', '12345678');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8');

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
// the hash cost factor, PHP's default is 10. This is commented out as php-login currently don't use this feture.
// define("HASH_COST_FACTOR", "10");

/**
 * Configuration for: Email server credentials
 *
 * Here you can define how you want to send emails.
 * If you have successfully set up a mail server on your linux server and you know
 * what you do, then you can skip this section. Otherwise please set EMAIL_USE_SMTP to true
 * and fill in your SMTP provider account data.
 *
 * An example setup for using gmail.com [Google Mail] as email sending service,
 * works perfectly in August 2013. Change the "xxx" to your needs.
 * Please note that there are several issues with gmail, like gmail will block your server
 * for "spam" reasons or you'll have a daily sending limit. See the readme.md for more info.
 *
 * define("PHPMAILER_DEBUG_MODE", 0);
 * define("EMAIL_USE_SMTP", true);
 * define("EMAIL_SMTP_HOST", 'ssl://smtp.gmail.com');
 * define("EMAIL_SMTP_AUTH", true);
 * define("EMAIL_SMTP_USERNAME", 'xxxxxxxxxx@gmail.com');
 * define("EMAIL_SMTP_PASSWORD", 'xxxxxxxxxxxxxxxxxxxx');
 * define("EMAIL_SMTP_PORT", 465);
 * define("EMAIL_SMTP_ENCRYPTION", 'ssl');
 *
 * It's really recommended to use SMTP!
 */

define('EMAIL_USED_MAILER', 'phpmailer');

// use SMTP or basic mail() ? SMTP is strongly recommended
define("EMAIL_USE_SMTP", false);
// name of your host
define("EMAIL_SMTP_HOST", 'yourhost');
// leave this true until your SMTP can be used without login
define("EMAIL_SMTP_AUTH", true);
// SMTP provider username
define("EMAIL_SMTP_USERNAME", 'yourusername');
// SMTP provider password
define("EMAIL_SMTP_PASSWORD", 'yourpassword');
// SMTP provider port
define("EMAIL_SMTP_PORT", 465);
// SMTP encryption, usually SMTP providers use "tls" or "ssl", for details see the PHPMailer manual
define("EMAIL_SMTP_ENCRYPTION", 'ssl');

/**
 * Configuration for: Email content data
 *
 * php-login uses the PHPMailer library, please have a look here if you want to add more
 * config stuff: @see https://github.com/PHPMailer/PHPMailer
 *
 * As email sending within your project needs some setting, you can do this here:
 *
 * Absolute URL to password reset action, necessary for email password reset links
 * define("EMAIL_PASSWORD_RESET_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/passwordReset");
 * define("EMAIL_PASSWORD_RESET_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_PASSWORD_RESET_FROM_NAME", "My Project");
 * define("EMAIL_PASSWORD_RESET_SUBJECT", "Password reset for PROJECT XY");
 * define("EMAIL_PASSWORD_RESET_CONTENT", "Please click on this link to reset your password:");
 *
 * absolute URL to verification action, necessary for email verification links
 * define("EMAIL_VERIFICATION_URL", "http://127.0.0.1/php-login/4-full-mvc-framework/login/verify/");
 * define("EMAIL_VERIFICATION_FROM_EMAIL", "noreply@example.com");
 * define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
 * define("EMAIL_VERIFICATION_SUBJECT", "Account Activation for PROJECT XY");
 * define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account:");
 */
define("EMAIL_PASSWORD_RESET_URL", URL . "login/verifypasswordreset");
define("EMAIL_PASSWORD_RESET_FROM_EMAIL", "no-reply@example.com");
define("EMAIL_PASSWORD_RESET_FROM_NAME", "My Project");
define("EMAIL_PASSWORD_RESET_SUBJECT", "Password reset for PROJECT XY");
define("EMAIL_PASSWORD_RESET_CONTENT", "Please click on this link to reset your password: ");

define("EMAIL_VERIFICATION_URL", URL . "login/verify");
define("EMAIL_VERIFICATION_FROM_EMAIL", "no-reply@example.com");
define("EMAIL_VERIFICATION_FROM_NAME", "My Project");
define("EMAIL_VERIFICATION_SUBJECT", "Account activation for PROJECT XY");
define("EMAIL_VERIFICATION_CONTENT", "Please click on this link to activate your account: ");

/**
 * Configuration for: Error messages and notices
 *
 * In this project, the error messages, notices etc are all-together called "feedback".
 */
define("FEEDBACK_UNKNOWN_ERROR", "Unknown error occurred!");
define("FEEDBACK_PASSWORD_WRONG_3_TIMES", "You have typed in a wrong password 3 or more times already. Please wait 30 seconds to try again.");
define("FEEDBACK_ACCOUNT_NOT_ACTIVATED_YET", "Your account is not activated yet. Please click on the confirm link in the mail.");
define("FEEDBACK_PASSWORD_WRONG", "Password was wrong.");
define("FEEDBACK_USER_DOES_NOT_EXIST", "This user does not exist.");
// The "login failed"-message is a security improved feedback that doesn't show a potential attacker if the user exists or not
define("FEEDBACK_LOGIN_FAILED", "Login failed.");
define("FEEDBACK_USERNAME_FIELD_EMPTY", "Username field was empty.");
define("FEEDBACK_PASSWORD_FIELD_EMPTY", "Password field was empty.");
define("FEEDBACK_USERNAME_OR_PASSWORD_FIELD_EMPTY", "Username or password field was empty.");
define("FEEDBACK_USERNAME_EMAIL_FIELD_EMPTY", "Username / email field was empty.");
define("FEEDBACK_EMAIL_FIELD_EMPTY", "Email field was empty.");
define("FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY", "Email and password fields were empty.");
define("FEEDBACK_USERNAME_SAME_AS_OLD_ONE", "Sorry, that username is the same as your current one. Please choose another one.");
define("FEEDBACK_USERNAME_ALREADY_TAKEN", "Sorry, that username is already taken. Please choose another one.");
define("FEEDBACK_USER_EMAIL_ALREADY_TAKEN", "Sorry, that email is already in use. Please choose another one.");
define("FEEDBACK_USERNAME_CHANGE_SUCCESSFUL", "Your username has been changed successfully.");
define("FEEDBACK_USERNAME_AND_PASSWORD_FIELD_EMPTY", "Username and password fields were empty.");
define("FEEDBACK_USERNAME_DOES_NOT_FIT_PATTERN", "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters.");
define("FEEDBACK_EMAIL_DOES_NOT_FIT_PATTERN", "Sorry, your chosen email does not fit into the email naming pattern.");
define("FEEDBACK_EMAIL_SAME_AS_OLD_ONE", "Sorry, that email address is the same as your current one. Please choose another one.");
define("FEEDBACK_EMAIL_CHANGE_SUCCESSFUL", "Your email address has been changed successfully.");
define("FEEDBACK_CAPTCHA_WRONG", "The entered captcha security characters were wrong.");
define("FEEDBACK_PASSWORD_REPEAT_WRONG", "Password and password repeat are not the same.");
define("FEEDBACK_PASSWORD_TOO_SHORT", "Password has a minimum length of 6 characters.");
define("FEEDBACK_USERNAME_TOO_SHORT_OR_TOO_LONG", "Username cannot be shorter than 2 or longer than 64 characters.");
define("FEEDBACK_EMAIL_TOO_LONG", "Email cannot be longer than 64 characters.");
define("FEEDBACK_ACCOUNT_SUCCESSFULLY_CREATED", "Your account has been created successfully and we have sent you an email. Please click the VERIFICATION LINK within that mail.");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_FAILED", "Sorry, we could not send you an verification mail. Your account has NOT been created.");
define("FEEDBACK_ACCOUNT_CREATION_FAILED", "Sorry, your registration failed. Please go back and try again.");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_ERROR", "Verification mail could not be sent due to: ");
define("FEEDBACK_VERIFICATION_MAIL_SENDING_SUCCESSFUL", "A verification mail has been sent successfully.");
define("FEEDBACK_ACCOUNT_ACTIVATION_SUCCESSFUL", "Activation was successful! You can now log in.");
define("FEEDBACK_ACCOUNT_ACTIVATION_FAILED", "Sorry, no such id/verification code combination here...");
define("FEEDBACK_AVATAR_UPLOAD_SUCCESSFUL", "Avatar upload was successful.");
define("FEEDBACK_AVATAR_UPLOAD_WRONG_TYPE", "Only JPEG and PNG files are supported.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_SMALL", "Avatar source file's width/height is too small. Needs to be 100x100 pixel minimum.");
define("FEEDBACK_AVATAR_UPLOAD_TOO_BIG", "Avatar source file is too big. 5 Megabyte is the maximum.");
define("FEEDBACK_AVATAR_FOLDER_DOES_NOT_EXIST_OR_NOT_WRITABLE", "Avatar folder does not exist or is not writable. Please change this via chmod 775 or 777.");
define("FEEDBACK_AVATAR_IMAGE_UPLOAD_FAILED", "Something went wrong with the image upload.");
define("FEEDBACK_PASSWORD_RESET_TOKEN_FAIL", "Could not write token to database.");
define("FEEDBACK_PASSWORD_RESET_TOKEN_MISSING", "No password reset token.");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_ERROR", "Password reset mail could not be sent due to: ");
define("FEEDBACK_PASSWORD_RESET_MAIL_SENDING_SUCCESSFUL", "A password reset mail has been sent successfully.");
define("FEEDBACK_PASSWORD_RESET_LINK_EXPIRED", "Your reset link has expired. Please use the reset link within one hour.");
define("FEEDBACK_PASSWORD_RESET_COMBINATION_DOES_NOT_EXIST", "Username/Verification code combination does not exist.");
define("FEEDBACK_PASSWORD_RESET_LINK_VALID", "Password reset validation link is valid. Please change the password now.");
define("FEEDBACK_PASSWORD_CHANGE_SUCCESSFUL", "Password successfully changed.");
define("FEEDBACK_PASSWORD_CHANGE_FAILED", "Sorry, your password changing failed.");
define("FEEDBACK_ACCOUNT_UPGRADE_SUCCESSFUL", "Account upgrade was successful.");
define("FEEDBACK_ACCOUNT_UPGRADE_FAILED", "Account upgrade failed.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_SUCCESSFUL", "Account downgrade was successful.");
define("FEEDBACK_ACCOUNT_DOWNGRADE_FAILED", "Account downgrade failed.");
define("FEEDBACK_NOTE_CREATION_FAILED", "Note creation failed.");
define("FEEDBACK_NOTE_EDITING_FAILED", "Note editing failed.");
define("FEEDBACK_NOTE_DELETION_FAILED", "Note deletion failed.");
define("FEEDBACK_COOKIE_INVALID", "Your remember-me-cookie is invalid.");
define("FEEDBACK_COOKIE_LOGIN_SUCCESSFUL", "You were successfully logged in via the remember-me-cookie.");
define("FEEDBACK_FACEBOOK_LOGIN_NOT_REGISTERED", "Sorry, you don't have an account here. Please register first.");
define("FEEDBACK_FACEBOOK_EMAIL_NEEDED", "Sorry, but you need to allow us to see your email address to register.");
define("FEEDBACK_FACEBOOK_UID_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook ID exists in our database).");
define("FEEDBACK_FACEBOOK_EMAIL_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook email exists in our database).");
define("FEEDBACK_FACEBOOK_USERNAME_ALREADY_EXISTS", "Sorry, but you have already registered here (your Facebook username exists in our database).");
define("FEEDBACK_FACEBOOK_REGISTER_SUCCESSFUL", "You have been successfully registered with Facebook.");
define("FEEDBACK_FACEBOOK_OFFLINE", "We could not reach the Facebook servers. Maybe Facebook is offline (that really happens sometimes).");
