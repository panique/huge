# A simple PHP & MySQL Login Script #

Have a look on the official project website (but you'll always find the most current informations here on github):
http://www.php-login.net or follow via [Twitter](https://twitter.com/simplephplogin), [Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)
or [Google+](https://plus.google.com/104110071861201951660).

*A simple, secure, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script.
Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility
pack, which makes those functions available in those versions too). This strength of the encryption can be increased (and decreased) to
stay secure, even if server technology (and hacker technology!) gets much much stronger.*

####Available in 4 versions (plus beautiful themes/templates/user interfaces):

0. One-File version. A full login system in one php file. Does not need a MySQL database, the script comes with a full power SQLite one-file database.
1. Extremely reduced (perfect for quickly setting up your project, made for people who need a simple login)
2. Advanced (much more features)
4. Full-MVC-framework (even more features and professional MVC-framework code structure)

Version #3, the styled version, has been dismissed to introduce a new project:
Simple, beautiful, professional themes/templates/user interfaces for all 4 versions, packed into an independent project.
Free to use and perfectly fitting into any version the php-login project. Simply copy into your css(/js?) folder
and your are ready-to-go. Find the code here [coming up in the second half of 2013]: 
https://github.com/panique/php-login-styles

###LIVE-DEMOS

- `0-one-file`: http://php-login.net/demo1.html
- `1-minimal`: http://php-login.net/demo2.html
- `2-advanced`: http://php-login.net/demo3.html
- `4-full-mvc-framework`: http://php-login.net/demo4.html

Server's phpinfo() here: http://109.75.177.79:80/ ! Feel free to test the scripts, and feel free to find security holes, problems and errors.
Please note that this is just a preview, so don't misuse it. Also don't post your personal email adress here as it will be visible! Use a trash
mail account instead, like this one: http://trashmail.ws/

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
- main feature: PDO (instead of mysqli)
- main feature: remember me / keep me logged in
- main feature: mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail)
- new feature: user can log in with email adress
- new feature: language files / i18n

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
- main feature: account upgrade/downgrade (for basic/premium accounts)
- COMING UP: PDF/Tutorial that shows how to use this framework
- COMING UP: code cleanup

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

A very detailed guideline on how to install the `0-one-file` version [here in this blog post](http://www.dev-metal.com/how-to-install-php-login-nets-0-one-file-login-script-on-ubuntu/).

TODO: change the tutorial in the blog !

* 1. call the install script via `_install.php`, which will create a `users.db` file right in the `database` folder. That's it.
Please note that the `database` folder needs to be writeable and you need to have the SQLite extension activated in PHP.

#####HOW TO INSTALL 1-MINIMAL VERSION

A very detailed guideline on how to install the `1-minimal` version [here in this blog post](http://www.dev-metal.com/install-php-login-nets-1-minimal-login-script-ubuntu/).

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").

#####HOW TO INSTALL 2-ADVANCED VERSION

A very detailed guideline on how to install the `2-advanced` version [here in this blog post](http://www.dev-metal.com/install-php-login-nets-2-advanced-login-script-ubuntu/).

* 1. create database "login" and table "users" via the sql statements or the .sql file in folder "_install"
* 2. change mySQL user and or mySQL password in config/config.php ("DB_USER" and "DB_PASS").
* 3. as this version uses email sending, you'll need to a) provide an SMTP account in the config OR b) install a mail server tool on your server.
If you want to use local mail sending (which is NOT recommended) then please have a look into the file "how to setup mail in PHP.txt" in the "_install" folder.
If you want to use SMTP mail sending, then get an SMTP account (gmail.com for example) and put your login data into the config/email.php file.
There's already a demo account scheme in the config. To connect to a SMTP service you'll proably need the PHP OpenSSL module, which is usually
preinstalled on php/apache2. If it's not activated, please do so by uncommenting this line `extension=php_openssl.dll` in your php.ini !
* 4. change the links/etc in config/config to your needs! You need to provide the URL of your project here to link to your project from within
verification/password reset mails.
* 5. in config/config.php, change COOKIE_DOMAIN to your domain name
* 5. in config/config.php, change COOKIE_SECRET_KEY to something new, simply a random string that will be a unique code for your project

#####HOW TO INSTALL 4-FULL-MVC-FRAMEWORK VERSION

A very detailed guideline on how to install the `4-full-mvc-framework` version [here in this blog post](http://www.dev-metal.com/install-php-login-nets-4-full-mvc-framework-login-script-ubuntu/).

Usually this script works out-of-the-box. Simply copy the script to your server's web folder (/var/www/ maybe) and change
the config files/.htaccess like described below. Sometimes, you'll need to install/activate mod_rewrite first:

*ON YOUR SERVER*
* 1. make your avatar folder (public/avatars) writeable by doing a `chmod 775 public/avatars` or `chmod 777 public/avatars` [depends on how you installed apache/php] on that folder.
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

If you want to use local mail sending (which is NOT recommended) then please have a look into the file "how to setup mail in PHP.txt" in the "_install" folder.
If you want to use SMTP mail sending, then get an SMTP accound (gmail.com for example) and put your login data into the config/email.php file.
There's already a demo account sheme in the config. To connect to a SMTP service you'll proably need PHP OpenSSL module, which is usually
preinstalled on php/apache2. If it's not activated, please do so by uncommenting this line `extension=php_openssl.dll` in your php.ini !

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
* 7. Change the domain in COOKIE_DOMAIN in config/config.php to your needs. Note: there needs to be a dot in front of it!
* 8. Read the TUTORIAL.md file to get an idea how everything works together !

###IMPORTANT NOTICE WHEN USING GMAIL.COM AS SMTP MAIL SERVICE

Gmail is very popular as an SMTP mail sending service and would perfectly fit for small projects, but
Sometimes gmail.com will not send mails anymore, usually because of:

1. "SMTP Connect error": PHPMailer says "smtp login failed", but login is correct: Gmail.com thinks you are a spammer. You'll need to
"unlock" your application for gmail.com by logging into your gmail account via your browser, go to http://www.google.com/accounts/DisplayUnlockCaptcha
and then, within the next 10minutes, send an email via your app. Gmail will then white-list your app server.
Have a look here for full explanaition: https://support.google.com/mail/answer/14257?p=client_login&rd=1

2. "SMTP data quota exceeded": gmail blocks you because you have sent more than 500 mails per day (?) or because your users have provided
 too much fake email addresses. The only way to get around this is renting professional SMTP mail sending, prices are okay, 10.000 mails for $5.

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

###IF YOU WANT TO KNOW MORE ABOUT THE PHP 5.5 (and 5.3/5.4) PASSWORD FUNCTIONS

1. [A little guideline on how to use the PHP 5.5 password hashing functions and it's "library plugin" based PHP 5.3 & 5.4 implementation](https://github.com/panique/php-login/wiki/A-little-guideline-on-how-to-use-the-PHP-5.5-password-hashing-functions-and-it%27s-%22library-plugin%22-based-PHP-5.3-&-5.4-implementation) 
2. [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/php-login/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
3. [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)

You can find all them in the project's [github wiki](https://github.com/panique/php-login/wiki).

###REQUIREMENTS / TROUBLESHOOTING

* needs **PHP 5.3.7+**, PHP 5.4+ or PHP 5.5+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers) for `1-minimal`
* are the database connection info in config/db.php or config/config.php correct ?
* have you created a database named "login" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?
* please don't use this script if you have absolutely no idea what PHP or MySQL is. Seriously.
* the 2-advanced and 4-full-mvc-framework versions use mail sending, so you need to have `sendmail` or something enabled on your server.
Please see the instruction in the folder "__install" if you need help with that. The 4-full-mvc-framework version also allows to send
mails with an SMTP account.
* the 2-advanced and 4-full-mvc-framework version use the PHP GD graphic functions, so you need to have them enabled. That's standard on most
php installations.

###CONTRIBUTING

Please review CONTRIBUTING.md before sending a pull request.

###USAGE WITH OLDER PHP VERSIONS: 

This script needs PHP 5.3.7 or higher. PHP 5.2 is outdated since 2009, so supporting this would be contra-productive (and is impossible btw).
**PHP 5.3.7 is REALLY needed** (as this version introduced modern password hashing algorithms). There is no way to work around
this. For your own security: Please don't use several years old versions of PHP ! This makes you an excellent target
for attackers. Every good webhost / server provider offers fresh and secure versions of PHP. To get an overview about outdated, supported and
active versions of PHP, please have a look [on wikipedia](https://en.wikipedia.org/wiki/PHP#Release_history).

###USEFUL STUFF

####How to use PDO (it's really easy!)

http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers

####Multiple projects on one server that should share / not share their sessions

If you want to run multiple instances of this script on one server, maybe like /myproject1/ and /myproject2/ and need to be logged
in into both applications via ONE session (sound weird, but some people actually need this) please have a look into this ticket, 
there's a nice solution: https://github.com/panique/php-login/issues/82

####Installing PHP 5.5

Sweet little (3 bash commands) guideline for Ubuntu 12.04:
[How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/) with 3 simple bash commands.

Same for Debian 7.0 / 7.1:
[How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)

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

###DONATE SOME DOLLARS IF YOU REALLY USE THIS SCRIPT###

If you think this script is useful and saves you a lot of work, a lot of costs (PHP developers are expensive) and let you sleep much better,
then donating a small amount would be very cool.

[![Paypal](http://www.php-login.net/img/paypal.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)

###CHECK MY NEW BLOG: [DEV-METAL.COM](http://www.dev-metal.com)###

Have a look on my new dev blog, highly relevant to the login script ! The blog will feature installation guidelines for this script,
insights about the current development (of php-login), masses of PHP and security stuff, lots of UI/UX related things, talks, slides,
interviews, tutorials, and some delicious details about the Berlin startup scene.

###AVAILABLE FOR HIRE###

I'm available for freelance work. Remote worldwide or locally around Central Europe. Drop me a line.
