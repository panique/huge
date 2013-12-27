php-login
=========

### A full-featured PHP login script built into a skeleton MVC application.

This script is part of the **PHP Login Project**, a collection of four similar login scripts for different use-cases.
This script here is the MVC framework version.
Find the official portal page of the project here: **[php-login.net](http://www.php-login.net)**.
Follow the project on **[Twitter](https://twitter.com/simplephplogin)**,
**[Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)** or
**[Google+](https://plus.google.com/104110071861201951660)** and have a look on the official support blog
**[Dev Metal](http://www.dev-metal.com)**.
Ask questions in the **[Official Support Forum](http://109.75.177.79/forum/)**.


### MVC Framework Version 2.0 (this one here)
* built with the official PHP password hashing functions, fitting the most modern password hashing/salting web standards
* users can register, login, logout (with username, email, password)
* users can register and login via Facebook (official Facebook PHP SDK used)
* [planned: users can register/login via Twitter, Google+, etc.]
* password-forget/reset
* remember-me (login via cookie)
* account verification via mail
* captcha
* failed-login-throttling
* user profiles
* account upgrade/downgrade
* supports local avatars and remote Gravatars
* supports native mail and SMTP sending (via PHPMailer)
* comes with a super-sexy Model-View-Controller (MVC) barebone-application structure
* uses PDO for database access
* uses URL rewriting ("beautiful URLs")
* file- and folder protection via .htaccess
* uses Composer to load external dependencies (PHPMailer, Facebook SDK, Captcha-Generator, etc.)
* can be installed via Composer
* fits PSR-1/2 coding guidelines
* fully commented
* is actively developed, maintained and bug-fixed
* has detailed tutorials
* [planned: ready-to-go PuPHPet files and Vagrant boxes]

### Other (smaller) versions of this script

#### One File Version [https://github.com/panique/php-login-one-file]
Full login script in one file. Uses a one-file SQLite database (no MySQL needed) and PDO. Features: Register,
login, logout.

#### Minimal Version [https://github.com/panique/php-login-minimal]
All the basic functions in a clean file structure, uses MySQL and mysqli. Register, login, logout.

#### Advanced Version [https://github.com/panique/php-login-advanced]
Same like minimal (uses MySQL and PDO), but much more features:
Register, login, logout, email verification, password reset, edit user data, gravatars, captchas,
remember me / stay logged in cookies, "remember me" supports parallel login from multiple devices,
login with email, i18n/internationalization, mail sending via PHPMailer (SMTP or PHP's mail() function/linux sendmail).

## Live Demo

See a [live demonstration of this script](http://php-login.net/demo4.html) or [see the server's phpinfo()](http://109.75.177.79:80/).

## Requirements

* **PHP 5.3.7+**, PHP 5.4+ or PHP 5.5+
* **MySQL 5** database (better use versions 5.5+ as very old versions have a [PDO injection bug](http://stackoverflow.com/q/134099/1114320)
* installed PHP extensions: PDO, gd (the tutorial shows how to do)
* installed tools on your server: git, curl, openssl, composer (the tutorial shows how to do)
* for professional mail sending: an SMTP account
* activated mod_rewrite on your server (the tutorial shows how to do)

## License

Licensed under [MIT](http://www.opensource.org/licenses/mit-license.php). Totally free for private or commercial projects.

## Contribute

Please commit only in *develop* branch. The *master* branch will always contain the stable version.

## Support / Donate

If you think this script is useful and saves you a lot of work, then think about supporting the project:

1. Rent your next server at [A2 Hosting](http://www.a2hosting.com/4471.html) or [DigitalOcean](https://www.digitalocean.com/?refcode=40d978532a20).
2. Donate via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)
   or [GitTip](https://www.gittip.com/Panique/)
3. Contribute to this project.

## Themes / User Interfaces / Styles

Bookmark the highly related partner-project "[php-login-styles](https://github.com/panique/php-login-styles)" which
will host beautiful themes for all the php-login versions. Currently this is only a placeholder,
the project starts in early 2014.

## Installation

TODO 
TODO 
TODO 
TODO 
TODO 
TODO 
TODO 
TODO 
TODO 
TODO 
TODO 


## Useful links

- [How to use PDO](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)
- [A short guideline on how to use the PHP 5.5 password hashing functions and its PHP 5.3 & 5.4 implementations](http://www.dev-metal.com/use-php-5-5-password-hashing-functions/)
- [How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/)
- [How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)
- [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/php-login/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
- [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/php-login/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
- [How to prevent PHP sessions being shared between different apache vhosts / different applications](http://www.dev-metal.com/prevent-php-sessions-shared-different-apache-vhosts-different-applications/)

You can find more in the project's [github wiki](https://github.com/panique/php-login/wiki).

## Thanks

This project is kindly powered by **[PHPStorm](http://www.jetbrains.com/phpstorm/)**. A big "Thank You!" to IntelliJ for giving php-login free licenses of this wonderful IDE.

## Hire me

I'm available for freelance work. Remote worldwide or locally around Central Europe. Mail me if you like.

## GitHub stats (by BitDeli)

[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/panique/php-login/trend.png)](https://bitdeli.com/free "Bitdeli Badge")
