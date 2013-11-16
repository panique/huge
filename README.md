# PHP & MySQL Login

Simple, lightweight and easy to use **Login Script for PHP** that comes in 4 different versions. From one-file version with SQLite database to full-featured MVC framework. Uses the ultra-modern & future-proof PHP 5.5. BLOWFISH hashing/salting functions (includes the official PHP 5.3 & PHP 5.4 compatibility pack, which makes those functions available in those versions too). Follow the project on **[Twitter](https://twitter.com/simplephplogin)**, **[Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)**
or **[Google+](https://plus.google.com/104110071861201951660)** and have a look on the official support blog **[Dev Metal](http://www.dev-metal.com)**.
Ask questions in the [Official Support Forum](http://109.75.177.79/forum/) (new!).

## Live Demos

See live demonstrations of **[1. One File Version](http://php-login.net/demo1.html)**, **[2. Minimal Version](http://php-login.net/demo2.html)**, **[3. Advanced Version](http://php-login.net/demo3.html)** and **[4. Full MVC Framework Version](http://php-login.net/demo4.html)**. The server's phpinfo() can be found [here](http://109.75.177.79:80/).

## Four different versions

#### One file
Full login script in one file. Uses a one-file SQLite database (no MySQL needed) and PDO. Features: Register, login, logout.

#### Minimal
All the basic functions in a clean file structure, uses MySQL and mysqli. Register, login, logout.

#### Advanced
Same like minimal (uses MySQL and PDO), but much more features: Register, login, logout, email verification, password reset, edit user data, gravatars, captchas, remember me / stay logged in cookies, login with email, i18n/internationalization, mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail).

#### Full MVC Framework
Same like Advanced Version, but everything comes with a professional MVC framework structure, perfect for building real applications. Additional features like: URL rewriting, professional usage of controllers and actions, PDO, MySQL, mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail), user profile pages, public user profiles, gravatars and local avatars, account upgrade/downgrade etc.  

## License

Licensed under [MIT](http://www.opensource.org/licenses/mit-license.php).

## Support the project

If you want to support the project, rent your next server at [DigitalOcean ($5 per month, or $0.007 per hour)](https://www.digitalocean.com/?refcode=40d978532a20).
The php-login project will get a little reward for each new customer. Alternativly, feel free to donate via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)
or [GitTip](https://www.gittip.com/Panique/).

## Requirements
####Basic Requirements

1. **PHP 5.3.7+**, PHP 5.4+ or PHP 5.5+
2. **MySQL 5** database for Minimal, Advanced and Full MVC version. The one-file version does not need a database.

####Additional Requirements
3. Minimal version needs the PHP **mysqli** (last letter is an "i") extension activated (default)
4. Advanced and Full MVC versions use mail sending, so you need to have an **SMTP mail sending account** somewhere OR you know how to get **linux's sendmail** etc. to run.
5. Advanced and Full MVC versions use **PHP's GD graphic functions**, so you need to have them enabled. That's standard on most php installations.

**Please don't use this script if you have absolutly no idea what the above things mean. Seriously.**

## Themes / User Interfaces / Stylings

Bookmark the highly related partner-project "[php-login-styles](https://github.com/panique/php-login-styles)" which will host beautiful themes for all the php-login versions. Currently this is only a placeholder, the project starts in late 2013.

## Installation

This script has been made to run out-of-the-box. Not more configuration than necessary. For a full installation guideline please have a look into these blog posts from the official support blog:

#####HOW TO INSTALL 0-ONE-FILE VERSION

A very detailed guideline on how to install the `0-one-file` version [here in this blog post](http://www.dev-metal.com/how-to-install-php-login-nets-0-one-file-login-script-on-ubuntu/).

* 1. call the install script via `_install.php`, which will create a `users.db` file right in the `database` folder. That's it.
Please note that the `database` folder needs to be writable and you need to have the SQLite extension activated in PHP.

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

## Troubleshooting & Useful Stuff
### When using gmail.com as SMTP service

Gmail is very popular as an SMTP mail sending service and would perfectly fit for small projects, but
Sometimes gmail.com will not send mails anymore, usually because of:

1. "SMTP Connect error": PHPMailer says "smtp login failed", but login is correct: Gmail.com thinks you are a spammer. You'll need to
"unlock" your application for gmail.com by logging into your gmail account via your browser, go to http://www.google.com/accounts/DisplayUnlockCaptcha
and then, within the next 10minutes, send an email via your app. Gmail will then white-list your app server.
Have a look here for full explanaition: https://support.google.com/mail/answer/14257?p=client_login&rd=1

2. "SMTP data quota exceeded": gmail blocks you because you have sent more than 500 mails per day (?) or because your users have provided
 too much fake email addresses. The only way to get around this is renting professional SMTP mail sending, prices are okay, 10.000 mails for $5.

### Session lifetime

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime
in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

### Session Garbage Collector

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

### The PHP 5.5 password hashing functions

1. [A little guideline on how to use the PHP 5.5 password hashing functions and it's "library plugin" based PHP 5.3 & 5.4 implementation](http://www.dev-metal.com/use-php-5-5-password-hashing-functions/)
2. [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/php-login/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
3. [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)

You can find all them in the project's [github wiki](https://github.com/panique/php-login/wiki).

### How to use PDO (it's really easy!)

[http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)

### Prevent/Allow PHP session sharing 

[How to prevent PHP sessions being shared between different apache vhosts / different applications](http://www.dev-metal.com/prevent-php-sessions-shared-different-apache-vhosts-different-applications/)

### Installing PHP 5.5

Sweet little (3 bash commands) guideline for Ubuntu 12.04:
[How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/). Same for Debian 7.0 / 7.1:
[How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)

## Contribute

Please review CONTRIBUTING.md before sending a pull request.

## Project Wiki and project issues

See [the wiki pages here](https://github.com/Panique/PHP-Login/wiki) for in-depth stuff. Have a look on the [issue list](https://github.com/panique/php-login/issues?state=open) for feature request and security discussions.

## Thanks

This project is kindly powered by **PHPStorm**. Thanks to IntelliJ for giving php-login free licenses of this wonderful IDE.
I've switched from NetBeans to PHPStorm as it is so much more advanced, bringing config-free support for XDebug,
Vagrant, composer etc. into your workflow. Try it: [PHPStorm - The PHP IDE](http://www.jetbrains.com/phpstorm/).
Totally free beta-versions here: [PHPStorm - Free beta version](http://www.jetbrains.com/phpstorm/nextversion/index.html).

BIG BIG THANKS to JREAM and his excellent mvc-framework tutorial / codebase on http://jream.com/lab/ ! The PHP Login Framework is build using code from JReam's framework (I took the base code from "Part 3" and improved with code from "Part 9", "Part 10" and "Part 11", so the code itself is still basic and not too advanced). If you like, have a look on the how-to-build-a-framework-tutorials on his site, they are excellent and very sympathic. And it's probably the best
MVC/framework tutorial on the web. Get started here: [http://www.youtube.com/watch?v=Aw28-krO7ZM](http://www.youtube.com/watch?v=Aw28-krO7ZM)

A big thanks goes out to Anthony Ferrara (ircmaxell) and Nikita Popov (nikic) for creating and documenting the wonderful PHP 5.5 password
hashing/salting functions and the compatibility pack for PHP 5.3/5.4 ! I love it, when people create things, that make it much much easier
and safer to use other things. You can find the official info on those functions on [php.net](https://wiki.php.net/rfc/password_hash) and
[here](http://benwerd.com/2012/09/12/more-secure-password-hashing-in-php-5-5/) and the official PHP 5.3/5.4 compatibility pack
[here](https://github.com/ircmaxell/password_compat/blob/master/lib/password.php).

Huge Thanks to Jay Zawrotny for the beautiful (avatar) image resizing/cropping function.

Also a big big "thank you" to the donors of this project, your tips gimme a good feeling and show that it's a useful project!

## Support / Donate

If you think this script is useful and saves you a lot of work, then think about supporting the project by

1. Donating via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)
   or [GitTip](https://www.gittip.com/Panique/)
2. Renting your next server at [DigitalOcean](https://www.digitalocean.com/?refcode=40d978532a20).
   SSD servers for $5+ per month or $0.007 per hour (!). PHP-MVC will get a small reward for every new customer.
3. Contributing to this project. Feel free to improve this project with your skills.

## Official Support Blog: [dev-metal.com](http://www.dev-metal.com)

Have a look on my new dev blog, highly relevant to the login script ! The blog will feature installation guidelines for this script, insights about the current development (of php-login), masses of PHP and linux security stuff, lots of UI/UX related things, talks, slides,
interviews, tutorials, and some delicious details about the Berlin startup scene.

## Author available for hire

I'm available for freelance work. Remote worldwide or locally around Central Europe. Drop me a line if you like.

## GitHub repository stats (by BitDeli)

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/panique/php-login/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

