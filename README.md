# A simple PHP & MySQL Login Script #

Have a look on the official project website (but you'll always find the most current informations here on github):
http://www.php-login.net or follow via [Twitter](https://twitter.com/simplephplogin), [Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)
or [Google+](https://plus.google.com/104110071861201951660).

*A simple, secure, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script.
Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility
pack, which makes those functions available in those versions too). This strength of the encryption can be increased (and decreased) to
stay secure, even if server technology (and hacker technology!) gets much much stronger.*

Available in 5 versions: 

0. One-File version. Seriously, a full login system in one php file. Does not even need a MySQL database, the script comes
with a full power SQLite one-file database.
1. extremely reduced (perfect for quickly setting up your project, made for people who need a simple login)
2. advanced (much more features)
3. [coming up] styled/themed (same like "advanced", but with css themes, maybe js actions)
4. full-MVC-framework (same features like 1./2./3., but professional MVC-framework code structure)

###DIFFERENT VERSIONS

#####ONE-FILE VERSION (by Mark Constable)

- extremely reduced to one (!) php file that handles ALL the stuff (plus a compatibility file for PHP 5.3 and 5.4)
- uses a one-file SQLite database (that will be created automatically while installation), does not need a real MySQL database

#####MINIMAL VERSION

- main features: user can register, log in and log out
- feature: username cannot be empty, must be >= 2 characters and <= 64 characters (checked in PHP and client-side in HTML5)
- feature: username must fit the azAZ09 pattern (checked in PHP and client-side in HTML5)
- feature: email must be provided, email must fit email format (checked in PHP and client-side in HTML5)
- feature: password and password repeat check need to be the same (strict php string check ===)
- security: SQL injection prevention: everything is escaped with real_escape_string()
- security: passwords are hashed and salted using the offical PHP 5.5 password hashing functions
- security: (works 100% too in PHP 5.3 and 5.4 due to included function compatibility file (in "libraries"))
- security: user input is cleaned, your php app is protected against XSS attacks

#####ADVANCED VERSION (same like minimal, but with additional features)

- main feature: username can be changed by user
- main feature: email can be changed by user
- main feature: user gets email after registration, has to click on verification link (one-time hash-check)
- main feature: user can edit password (need to provide password again to prevent account takeovers when keeping browser open)
- main feature: user can request password reset ("i forgot my password" function)
- main feature: gravatar profile pic support
- main feature: captcha

*NOTE: this version needs the mail()-function (and in upcoming versions also the graphic/GD functions) of PHP.
Usually naked servers don't have a mail server installed that will make it possible to send mail.
In order to use this version of the script, please install a mail server by following the tutorial in the "2-advanced/_install" folder!*

#####STYLED/THEMED [not published yet. coming up in late 2013]

- same like 2., but with additional css/js stylings
- several stylings to choose

#####FULL-MVC-FRAMEWORK

- all the features from 2-advanced and more
- quite professional code/file structure
- perfect for building REAL applications
- main feature: URL rewriting (beautiful URLs)
- main feature: professional usage of controllers and actions
- main feature: PDO database connector (@see http://www.phpro.org/tutorials/Introduction-to-PHP-PDO.html)
- main feature: mail sending via local linux mail tool OR SMTP account
- main feature: captcha
- main feature: user profile page
- main feature: public user list / public profiles
- main feature: time delay after failed logins
- main feature: local avatars
- main feature: remember me / keep me logged in
- COMING UP: PDF/Tutorial that shows how to use this framework
- COMING UP: more features
- COMING UP: code cleanup
- COMING UP: better error handling

BIG BIG THANKS to **JREAM** and his excellent mvc-framework tutorial / codebase on http://jream.com/lab/ !
The **PHP Login Framework** is build using code from JReam's framework (I took the base code from
"Part 3" and improved with code from "Part 9", "Part 10" and "Part 11", so the code itself is still basic
and not too advanced). If you like, have a look on the how-to-build-a-framework-tutorials on his site,
they are excellent and very sympathic.

*Screenshot (desktop) from the 4-full-mvc-framework:*

![Screenshot desktop](http://imageshack.us/a/img14/536/ndd.png)

*Screenshot (mobile) from the 4-full-mvc-framework:*

![Screenshot mobile](http://img59.imageshack.us/img59/9750/fbvk.png)

###HOW TO DOWNLOAD

Here on github you'll always find a stable and secure master branch and the in-development develop branch.
The master branch is ready for production, the develop branch is not (as it just shows the current state of development,
maybe including some not-so-good tested features). For newbies: to download, simply click the ZIP-button on the right
upper sidebar here on this page.

###HOW TO INSTALL

This script has been made to run out-of-the-box. Not more config stuff than necessary.

#####HOW TO INSTALL 0-ONE-FILE VERSION

* 1. call the script via `index.php?a=install`, which will create a `users.db` file right in your folder. That's it.
* (2.) when really using this script in a live project, please select a strong password for the database, you can change
that in the first lines on the script.

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

Usually this script works out-of-the-box. Simply copy the script to your server's web folder (/var/www/ maybe) and change
the config files/.htaccess like described below. Sometimes, you'll need to install/activate mod_rewrite first:

*ON YOUR SERVER*
* 1. make your avatar folder (public/avatars) writeable by doing a `chmod 775`.
* 2. activate the apache module mod_rewrite by typing on the command line (on your server): `a2enmod rewrite`
* 3. usually the mod_rewrite module will not work now (why?), so you have to edit
`/etc/apache2/sites-available/default` and change the first two occurences of `AllowOverride None` to `AllowOverride All`
* 4. restart your server by typing `service apache2 restart` or `/etc/init.d/apache2 restart` 

Please note: I really don't understand why it's so goddamn complicated to set up the most simple features on a linux server.
But we have to live with that. If you keep running into problems with that mod_rewrite shit, then please send me an email, 
open a github issue or get a server that is ready-to-go with mod_rewrite already activated.

You can also find this intro on mod_rewrite here:
http://www.jarrodoberto.com/articles/2011/11/enabling-mod-rewrite-on-ubuntu

And a general StackOverflow discussion about the activation of mod_rewrite (and troubleshooting) here:
http://stackoverflow.com/q/869092/1114320

*IN THE CODE*

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").
* 3. change the LIB constant in config/config.php to the URL (not path!) of your app.
* 4. change the RewriteBase in .htaccess to the subfolder of your web/ht_docs/www directory (not path!, not URL!) where your app is in.
If your app is in the root of your web folder, then pleae delete this line. If it's in var/www/myapp, then your line should look like
RewriteBase /myapp/
* 5. Mail sending: if you are using a local mail server tool (sendmail) then you can skip this step. If you want to use an SMTP account 
then fill in your credentials in EMAIL_SMTP_... and set EMAIL_USE_SMTP to true.
* 6. Change the URLs, emails and texts of EMAIL_PASSWORDRESET_... and EMAIL_VERIFICATION_... to your needs.
* 7. Read the TUTORIAL.md file to get an idea how everything works together !

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime
in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###IMPORTANT NOTE REGARDING SESSION HANDLING

Sessions in PHP are easy to handle, but have a tricky configuration underneath. The common opinion is, that when you
close your browser, the session is gone. Actually, it's a little bit more complicated:
Have a look on these 3 line in your `php.ini`:

`session.gc_maxlifetime = 3600`
`session.gc_probability = 1`
`session.gc_divisor = 1000`

`session.gc_maxlifetime` says: 3600 seconds (1 hour) after session initialization, PHP will mark this session as "outdated" and
flag it as "ready to delete". The session still exists after 1 hour! But it's not deleted. The deletion process of all
outdated and ready-to-delete file is called "garbage collection" (process), and it's triggered - with a specific probability - 
when another user comes to your page. This probability is calculated by `session.gc_probability` divided by `session.gc_divisor`.
Yeah, a little bit weird, but the people behind PHP have thought about this, and there are reasons for this behaviour.

Have a look on this excellent answer on StackOverflow to read more about this topic: 
[How do I expire a PHP session after 30 minutes?](http://stackoverflow.com/a/1270960/1114320).
For this script it means, that when you close your browser and open it again, and are still logged in, it has to do with
PHP's session gargabe collector process ;)

###REQUIREMENTS / TROUBLESHOOTING

* needs **PHP 5.3.7+**, PHP 5.4+ or PHP 5.5+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers)
* are the database connection infos in config/db.php correct ?
* have you created a database named "login" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?
* please don't use this script if you have absolutly no idea what PHP or MySQL is. Seriously.
* the 2-advanced and 4-full-mvc-framework versions use mail sending, so you need to have `sendmail` or something enabled on your server.
Please see the instruction in the folder "__install" if you need help with that. The 4-full-mvc-framework version also allows to send
mails with an SMTP account.
* the 2-advanced and 4-full-mvc-framework version use the PHP GD graphic functions, so you need to have them enabled. That's standard on most
php installations.

###USAGE WITH OLDER PHP VERSIONS: 

This script needs PHP 5.3.7 or higher. PHP 5.2 is outdated since 2009, so supporting this would be contra-productive (and is impossible btw).
**PHP 5.3.7 is REALLY needed** (as this version introduced modern password hashing algorithms). There is no way to work around
this. For your own security: Please don't use several years old versions of PHP ! This makes you an excellent target
for attackers. Every good webhost / server provider offers fresh and secure versions of PHP. To get an overview about outdated, supported and
active versions of PHP, please have a look [on wikipedia](https://en.wikipedia.org/wiki/PHP#Release_history).

###USEFUL STUFF

* If you want to run multiple instances of this script on one server, maybe like /myproject1/ and /myproject2/ and need to be logged
in into both applications via ONE session (sound weird, but some people actually need this) please have a look into this ticket, 
there's a nice solution: https://github.com/panique/php-login/issues/82

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

Another very big Thanks to Mark Constable for creating the awesome 0-one-file version of the script. It's unbelievable
what powerful things you can create within ONE short and readable file!

Huge Thanks to Jay Zawrotny for the beautiful (avatar) image resizing/cropping function.

Also a big big "thank you" to the donors of this project, your tips gimme a good feeling and show that it's a useful project!

###DONATE $10+ IF YOU REALLY USE THIS SCRIPT###

If you think this script is useful and saves you a lot of work, a lot of costs (PHP developers are expensive) and let you sleep much better,
then donating a small amount would be very cool.

[Visit PayPal here](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG) to donate. Thanks!

###AVAILABLE FOR HIRE###

I'm available for freelance work. Remote worldwide or locally around Central Europe. Drop me a line.
