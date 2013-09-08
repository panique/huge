# CHANGE LOG #

September 8th 2013
* 2-advanced: Fixing the breaking commits

September 4th 2013
* 0-one-file: Deletion of 0-one-file project (because code quality)
* 0-one-file: Complete rewrite of 0-one-file project

September 1st 2013
* 2-advanced: Feedback message are now config variables, not inline text anymore
* 2-advanced: Language support (english, french)
* 2-advanced: Email are now unique in the system
* 2-advanced: User can log in with email now
* 2-advanced: Bigger file structure changes
* 2-advanced: Code quality improvements
* 2-advanced: Lot's of structural changes, mostly code improvements

August 25th 2013
* 2-advanced: cookies / remember me feature
* 2-advanced: registration saves ip/timestamp now

August 23th 2013
* 0-one-file: moved db file to folder and blocked db folder/db file access from the outside

August 20th 2013
* 4-full-mvc-framework: complete rewrite of cookie handling (now clean and in mvc-structure)
* 4-full-mvc-framework: removed avatar file write-rights check (only checking for folder)
* 4-full-mvc-framework: put error output in view files
* 4-full-mvc-framework: route user to last visited page after re-login with cookie
* 2-advanced: this version uses now PDO all over the script

August 18th 2013
* 4-full-mvc-framework: fixed cookie vulnerability introduced few days ago
* 2-advanced: first PSR-code-convention styles implemented

August 18th 2013
* 2-advanced: merged config files to one file

August 13th 2013
* 4-full-mvc-framework: smaller fixes
* 4-full-mvc-framework: much better SMTP mail config (with ports etc)
* 4-full-mvc-framework: SMTP mail config now shows a ready-to-go gmail.com example
* 4-full-mvc-framework: live-demo links for the project!
* 4-full-mvc-framework: avatar folder is now checked for writing rights
* 2-advanced: SMTP mailing now possible by default

August 13th 2013
* 4-full-mvc-framework: email change now asks for password (to prevent account takeovers)
* 4-full-mvc-framework: "my account" menu item now links directly to user's profile
* 4-full-mvc-framework: users can upgrade/downgrade their account status, like standard/premium etc.
* 4-full-mvc-framework: system feedback messages are now constants, not pure text in the model anymore

August 12th 2013
* introducing the styles for the php-login project: https://github.com/panique/php-login-styles

August 11th 2013
* 4-full-mvc-framework: logout happens now via model (which is cleaner)
* 4-full-mvc-framework: remember me feature introduced

August 10th 2013
* 4-full-mvc-framework: local avatars
* README: new section USEFUL STUFF for edge cases
* README: USEFUL STUFF: info on shared session/login for multiple instances of the script
* introducing new "0-one-file" version of the script that uses a SQLite one-file database

August 9th 2013
* 4-full-mvc-framework: application title/logo is now clickable
* 4-full-mvc-framework: private user profile page
* 4-full-mvc-framework: prevented access to views if user is not logged in
* 4-full-mvc-framework: Google Chrome messes up webfonts, so switchback to Arial :(
* 4-full-mvc-framework: public user list
* 4-full-mvc-framework: better autoloader (LIB constant, splitting internal/external libs)
* 4-full-mvc-framework: time delay after 3 failed logins

August 8th 2013
* 2-advanced: pull request merged: getUsername() now always returns username
* 2-advanced: basic formatting in views
* 2-advanced: promotion of long sentences as password (in views)
* 4-full-mvc-framework: promotion of long sentences as password (in views)

August 5th 2013
* all registration / password change views have a password pattern explaining notice now

July 20th 2013
* removed netbeans files from repo ;)
* comment fixes
* 4-full-mvc-framework: new user verification mail does no work with user_id, not user_email
* 2-advanced: new user verification mail does no work with user_id, not user_email
(to prevent weird URL encoding problems with special email adresses)


July 16th 2013
* 4-full-mvc-framework: captcha is now case-insensitive
* 2-advanced: captcha is now case-insensitive

July 13th 2013
* 4-full-mvc-framework: captcha support (check the register page) !
* 2-advanced: captcha support (check the register page) !

July 8th 2013
* 4-full-mvc-framework: added SMTP email sender name support

July 6th 2013
* all versions: direct access of view files forbidden via .htaccess

July 5th 2013
* 4-full-mvc-framework: full frontend interface
* 4-full-mvc-framework: basic responsive version of the entire framework
* 4-full-mvc-framework: new way of handling the registration routing
* 4-full-mvc-framework: litte tutorial for this version in TUTORIAL.md
* 4-full-mvc-framework is now available for public in master branch

July 4th 2013
* 4-full-mvc-framework: several smaller improvements

July 2nd 2013
* 4-full-mvc-framework: better navigation with active-view-checker
* 4-full-mvc-framework: new jQuery version

July 1st 2013
* 4-full-mvc-framework: better navigation with active-view-checker

June 28th 2013
* 1-minimal: minimum php version checker
* 2-advanced: minimum php version checker
* 4-full-mvc-framework: first commit of the new login framework (still only a preview, there will be lot of changes!)

June 15th 2013
* 2-advanced: testwise changed property comments to official phpDocumentor syntax
* readme: better declaration of versions, call to donation

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