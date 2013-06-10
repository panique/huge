# CHANGE LOG #

June 10th 2013
* 2-advanced: password reset fix for wrong password length check
* 2-advanced: password reset fix for missing/wrong email adress

June 3rd 2013
* 2-advanced: basic gravatar profile pic support

June 2nd 2013
* 2-advanced: users can now edit their passwords (when being logged in and providing the password again)
* 2-advanced: users can now request a password reset and provide a new one ("forgot my password" function)
* 2-advanced: little beautifications & corrections

June 1st 2013
* 1-minimal: all user submitted content is now filtered for html/javascript code (to prevent cross-site-scripting attacks)
* 1-minimal: additional username length check in backend (>= 2 characters)
* 1-minimal: simplified HTML5 password pattern in the views, removed oninvalid attribute due to odd behaviour
* 2-advanced: all user submitted content is now filtered for html/javascript code (to prevent cross-site-scripting attacks)
* 2-advanced: additional username length check in backend (>= 2 characters)
* 2-advanced: additional username pattern/length check in edit.php via HTML5 attribute
* 2-advanced: simplified HTML5 password pattern in the views, removed oninvalid attribute due to odd behaviour
* entire project: big correction of spelling mistakes

May 29th 2013
* 1-minimal: removed unnecessary lines from registration class
* 2-advanced: removed unnecessary lines from registration class

May 28th 2013
* 1-minimal: changed the hash/salt and verification process to simply native PHP 5.5 functions (see readme for more info)
* 1-minimal: added "libraries/password_compatibility_library", which contains those functions for PHP 5.3 and 5.4 (included in index.php etc.)
* 1-minimal: changed the SQL table creation files (sorry, again): database column "user_password_hash" from CHAR(118) to VARCHAR(255)
* 2-advanced: changed the hash/salt and verification process to simply native PHP 5.5 functions (see readme for more info)
* 2-advanced: added "libraries/password_compatibility_library", which contains those functions for PHP 5.3 and 5.4 (included in index.php etc.)
* 2-advanced: changed the SQL table creation files (sorry, again): database column "user_password_hash" from CHAR(118) to VARCHAR(255)

May 18th 2013
* 1-minimal: html5 form attributes that (optionally) validate the input fields on client's browsers:
* 1-minimal: min/max length for email input fields
* 1-minimal: min/max length for username input field, additionally html5 string check (a-z, A-Z, 0-9)
* 1-minimal: user need to provide email now, registration without email is not possible any more
* 1-minimal: PHP checks for username structure (a-z, A-Z, 0-9), email structure
* 1-minimal: removed 64 char limit for password. passwords can now be 1024 chars
* 1-minimal: login.sql (in "_install" folder) renamed to users.sql (as it is the name of the database table)

May 12th 2013
* changed hashing algorithm from blowfish/SHA256 to SHA512
* changed database creation files (due to new SHA512 hashing algorithm)
* changed database column "user_password_hash" from CHAR(60) to CHAR(118) [as hash is always 118 chars long]
* added HTML5 attributes to views (type="email", required etc.)

April 26th 2013
* complete makeover, nearly all files have been touched
* registration process is now in separate class and separate init file / view (register.php etc.)
* massive reduction of the views: no css, no js, no unnecessary stuff. just pure naked basics
* entire project is now free of php "notice" error when you have hard error reporting
* documented nearly EVERYTHING
* entire project tries to be PSR-1/2 compliant, which means: everything fits to the PSR coding standards
* (see https://github.com/php-fig/fig-standards for more)
* changed database column "user_password_hash" from TEXT to CHAR(60) [as hash is always 60 chars long]
* changed database column "user_email" from TEXT to VARCHAR(64) [variable length string, up to 64 chars]
