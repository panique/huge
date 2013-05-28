# A simple PHP & MySQL Login Script #

Have a look on the official project website (but you'll always find the most current informations here on github):
http://www.php-login.net

*A simple, secure, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script.
Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility
pack, which makes those functions available in those versions too). This strength of the encryption can be increased (and decreased) to
stay secure, even if server technology (and hacker technology!) gets much much stronger.*

Available in 3 versions: 

1. extremely reduced (perfect for quickly setting up your project, made for people who need a simple login)
2. advanced (much more features)
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

ADVANCED VERSION (same like minimal version, but additional functions)
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

###HOW TO DOWNLOAD

Here on github you'll always find a stable and secure master branch and the in-development develop branch.
The master branch ist ready for production, the develop branch is not (as it just shows the current state of development,
maybe including some not-so-good tested features). For newbies: to download, simply click the ZIP-button on the upper left area of the
github project header.

###HOW TO INSTALL

This script has been made to run out-of-the-box. Not more config stuff than necessary.

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").
* (3.) in the 2-advanced version, you'll EVENTUALLY need to set up a mail server on your linux server. that sounds crazy, but is
something you can do within 60 seconds on your linux command line. Please have a look into the file "how to setup mail in PHP.txt"
in the "_install" folder.

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###REQUIREMENTS / TROUBLESHOOTING

* needs PHP 5.3.7+, PHP 5.4+ or PHP 5.5+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers) ?
* are the database connection infos in config/db.php correct ?
* have you created a database named "login" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?

###USAGE WITH OLDER PHP VERSIONS: older than 5.3.7

Sorry, this makes no sense anymore. PHP 5.2 is outdated since 2009, so supporting this would be useless.
PHP 5.3.7 is needed (as this introduces the hashing algorithms used here). Using an older version of PHP,
especially older than the latest PHP 5.3.x is totally unprofessional and makes you, your server and your data
a good target for criminals.

###THANKS TO###

A big thanks goes out to Anthony Ferrara (ircmaxell) and Nikita Popov (nikic) for creating and documenting the wonderful PHP 5.5 password
hashing/salting functions and the compatibility pack for PHP 5.3/5.4 ! I love it, when people create things, that make it much much easier
and safer to use other things. You can find the official info on those functions on [php.net](https://wiki.php.net/rfc/password_hash), [here](http://benwerd.com/2012/09/12/more-secure-password-hashing-in-php-5-5/) and
[here]() and the official PHP 5.3/5.4 compatibility pack [here](https://github.com/ircmaxell/password_compat/blob/master/lib/password.php).

Also a big big "thank you" to the donators of this project, your tips gimme a good feeling and show that it's a useful project!

###DONATION###

If you want to support this script, feel free to donate via paypal:

Click here https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG
