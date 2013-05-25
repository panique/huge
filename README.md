# A simple PHP & MySQL Login Script #

Always find the latest version, a documentation and other stuff on the project website:
http://www.php-login.net

*A simple, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script. Uses ultramodern futureproof SHA512 hashing algorithm with a salt string.*

Available in 3 versions: 

1. extremely reduced (perfect for quickly setting up your project, made for people who need a simple login)
2. advanced (much more possibilites, better organized code)
3. [coming up] high-end (same like "advanced", but with css themes, javascript actions, ajax login etc.)

###DIFFERENT VERSIONS

MINIMAL VERSION
* users can register a new account with username, email and password
* users can login (with username and password)
* users can logout
* 2 classes (Login & Registration)
* 3 views (login form (=not logged in), logged in, registration form)
* 1 simple database connection config file
* simple, but effective, clean and easy to understand code/file structure

ADVANCED VERSION (same like minimal version, but additional functions, you can find this version in the develop-branch)
* users can edit their properties
* users need to provide an email in registration process, and they have to click on a mailed registration verify link to active account
* (TODO: users can have a role status (like normal user, premium user, super premium user))
* (TODO: users can delete their accounts)
* (TODO: graphical captchas)
* (TODO: users can reset their passwords)

NOTE: this version needs the mail-function (and in upcoming versions also the graphic/GD functions of PHP).
Usually naked servers don't have a mail server installed, that will make it possible to send mail.
In order to use this version of the script, please install a mail server by following the tutorial in the "2-advanced/_install" folder

PROFESSIONAL VERSION (same functions like advanced version, but totally new code/file structure)
* biggest change: quite professional MVC file/code structure
* URL rewriting (/index.php?controller=user&action=edit becomes /user/edit)
* professional usage of controllers and actions
* database object, that is shared within all classes (dependency injection. no usage of bad bad bad ;) singleton. good thing!)
* ...

###HOW TO INSTALL

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###REQUIREMENTS / TROUBLESHOOTING

* needs PHP 5.3.2+, PHP 5.4+ or PHP 5.5+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers) ?
* are the database connection infos in config/db.php correct ?
* have you created a database named "login" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?

###USAGE WITH OLDER PHP VERSIONS: older than 5.3.2

Sorry, this makes no sense anymore. PHP 5.2 is outdated since 2009, so supporting this would be useless.
PHP 5.3.2 is needed (as this introduces the hashing algorithms used here)

###DONATION###

If you want to support this script, feel free to donate via paypal:

Click here https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG
