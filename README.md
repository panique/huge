# A simple PHP & MySQL Login Script #

Have a look on the official project website (but you'll always find the most current informations here on github):
http://www.php-login.net or follow via [Twitter](https://twitter.com/simplephplogin), [Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)
or [Google+](https://plus.google.com/104110071861201951660).

*A simple, secure, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script.
Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility
pack, which makes those functions available in those versions too). This strength of the encryption can be increased (and decreased) to
stay secure, even if server technology (and hacker technology!) gets much much stronger.*

Available in 4 versions: 

1. extremely reduced (perfect for quickly setting up your project, made for people who need a simple login)
2. advanced (much more features)
3. [coming up] styled/themed (same like "advanced", but with css themes, maybe js actions)
4. [coming up] full-MVC-framework (same features like 1./2./3., but professional MVC-framework code structure)

###DIFFERENT VERSIONS

####MINIMAL VERSION

- main features: user can register, log in and log out
- feature: username cannot be empty, must be >= 2 characters and <= 64 characters (checked in PHP and client-side in HTML5)
- feature: username must fit the azAZ09 pattern (checked in PHP and client-side in HTML5)
- feature: email must be provided, email must fit email format (checked in PHP and client-side in HTML5)
- feature: password and password repeat check need to be the same (strict php string check ===)
- security: SQL injection prevention: everything is escaped with real_escape_string()
- security: passwords are hashed and salted using the offical PHP 5.5 password hashing functions
- security: (works 100% too in PHP 5.3 and 5.4 due to included function compatibility file (in "libraries"))
- security: user input is cleaned, your php app is protected against XSS attacks

####ADVANCED VERSION (same like minimal, but with additional features)

- main feature: username can be changed by user
- main feature: email can be changed by user
- main feature: user gets email after registration, has to click on verification link (one-time hash-check)
- main feature: user can edit password (need to provide password again to prevent account takeovers when keeping browser open)
- main feature: user can request password reset ("i forgot my password" function)
- main feature: gravatar profile pic support

*NOTE: this version needs the mail()-function (and in upcoming versions also the graphic/GD functions) of PHP.
Usually naked servers don't have a mail server installed that will make it possible to send mail.
In order to use this version of the script, please install a mail server by following the tutorial in the "2-advanced/_install" folder!*

####STYLED/THEMED [not published yet. coming up in late 2013]

- same like 2., but with additional css/js stylings
- several stylings to choose

####FULL-MVC-FRAMEWORK [currently only a preview, full version needs some weeks]

- same functions like advanced version, but totally new code/file structure
- perfect for building REAL applications
- main feature: URL rewriting (beautiful URLs)
- main feature: professional usage of controllers and actions
- main feature: PDO database connector (@see http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html)
- COMING UP: PDF/Tutorial that shows how to use this framework

To install the script, please change the RewriteBase in .htaccess to the folder your code is in. You can delete this line
if index.php is in the root of your web folder. If you have put your stuff into /myfolder/, then please change
the RewriteBase to /myfolder/ ! Additionally, please fill your settings into config/config.php, otherwise your app will not
run properly.

BIG BIG THANKS to **JREAM** and his excellent mvc-framework tutorial / codebase on http://jream.com/lab/ !
The **PHP Login Framework** is build using code from JReam's framework (I took the base code from
"Part 3" and improved with code from "Part 9", "Part 10" and "Part 11", so the code itself is still basic
and not too advanced). If you like, have a look on the how-to-build-a-framework-tutorials on his site,
they are excellent and very sympathic.

###HOW TO DOWNLOAD

Here on github you'll always find a stable and secure master branch and the in-development develop branch.
The master branch is ready for production, the develop branch is not (as it just shows the current state of development,
maybe including some not-so-good tested features). For newbies: to download, simply click the ZIP-button on the upper left area of the
github project header.

###HOW TO INSTALL

This script has been made to run out-of-the-box. Not more config stuff than necessary.

#####HOW TO INSTALL 1-MINIMAL VERSION

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").

#####HOW TO INSTALL 2-ADVANCED VERSION

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").
* 3. as this version uses email sending, you'll need to install a mail server tool on your server [SMTP via PHPMailer coming up].
Maybe a mail server is already installed on your server. This is something you can do within 60 seconds on your linux command line.
Please have a look into the file "how to setup mail in PHP.txt" in the "_install" folder.

#####HOW TO INSTALL 4-FULL-MVC-FRAMEWORK VERSION

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").
* 3. change the LIB constant in config/config.php to the URL (not path!) of your app.
* 4. change the RewriteBase in .htaccess to the subfolder of your web/ht_docs/www directory (not path!, not URL!) where your app is in.
If your app is in the root of your web folder, then pleae delete this line. If it's in var/www/myapp, then your line should look like
RewriteBase /myapp/
* 5. Mail sending: if you are using a local mail server tool (sendmail) then you can skip this step. If you want to use an SMTP account 
then fill in your credentials in EMAIL_SMTP_... and set EMAIL_USE_SMTP to true.
* 6. Change the URLs, emails and texts of EMAIL_PASSWORDRESET_... and EMAIL_VERIFICATION_... to your needs.

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime
in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###REQUIREMENTS / TROUBLESHOOTING

* needs PHP 5.3.7+, PHP 5.4+ or PHP 5.5+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers)
* are the database connection infos in config/db.php correct ?
* have you created a database named "login" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?
* please don't use this script if you have absolutly no idea what PHP or MySQL is. Seriously.

###USAGE WITH OLDER PHP VERSIONS: older than 5.3.7

Sorry, this makes no sense any more. PHP 5.2 is outdated since 2009, so supporting this would be useless.
PHP 5.3.7 is needed (as this introduces the hashing algorithms used here). Using an older version of PHP,
especially older than the latest PHP 5.3.x is totally unprofessional and makes you, your server and your data
a good target for attacks.

###MORE INFO IN THE WIKI

See [the wiki pages here](https://github.com/Panique/PHP-Login/wiki) for in-depth stuff.

###THANKS TO###

A big thanks goes out to Anthony Ferrara (ircmaxell) and Nikita Popov (nikic) for creating and documenting the wonderful PHP 5.5 password
hashing/salting functions and the compatibility pack for PHP 5.3/5.4 ! I love it, when people create things, that make it much much easier
and safer to use other things. You can find the official info on those functions on [php.net](https://wiki.php.net/rfc/password_hash) and
[here](http://benwerd.com/2012/09/12/more-secure-password-hashing-in-php-5-5/) and the official PHP 5.3/5.4 compatibility pack
[here](https://github.com/ircmaxell/password_compat/blob/master/lib/password.php).

I would also like to thank Jesse from http://jream.com for his excellent framework tutorial (and code). It's probably the best
MVC/framework tutorial on the web. Get started here: http://www.youtube.com/watch?v=Aw28-krO7ZM

Also a big big "thank you" to the donors of this project, your tips gimme a good feeling and show that it's a useful project!

###DONATE 1$+ IF YOU USE THIS SCRIPT###

If you think this script is useful and saves you a lot of work, a lot of costs (PHP developers are expensive) and let you sleep much better,
then donating a small amount would be very cool.

[Visit PayPal here](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG) to donate. Thanks!

###AVAILABLE FOR HIRE###

I'm available for freelance work. Remote worldwide or locally around Central Europe. Drop me a line.
