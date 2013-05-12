# CHANGELOG #

May 12, 2013
* changed hashing algorithm from blowfish/SHA256 to SHA512
* changed database creation files (due to new SHA512 hashing algorithm)
* changed database column "user_password_hash" from CHAR(60) to CHAR(118) [as hash is always 118 chars long]
* added HTML5 attributes to views (type="email", required etc.)

April 26, 2013
* complete makeover, nearly all files have been touched
* registration process is now in seperate class and seperate init file / view (register.php etc.)
* massive reduction of the views: no css, no js, no unnecessary stuff. just pure naked basics
* entire project is now free of php "notice" error when you have hard error reporting
* documented nearly EVERYTHING
* entire project tries to be PSR-1/2 compilant, which means: everything fits to the PSR coding standards
* (see https://github.com/php-fig/fig-standards for more)
* changed database column "user_password_hash" from TEXT to CHAR(60) [as hash is always 60 chars long]
* changed database column "user_email" from TEXT to VARCHAR(64) [variable length string, up to 64 chars]