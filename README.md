[![HUGE, formerly "php-login" logo](_pictures/huge-logo.png)](http://www.php-login.net)

# HUGE

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/panique/huge/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/panique/huge/?branch=master)
[![Code Climate](https://codeclimate.com/github/panique/huge/badges/gpa.svg)](https://codeclimate.com/github/panique/huge)
[![Travis CI](https://travis-ci.org/panique/huge.svg?branch=master)](https://travis-ci.org/panique/huge)
[![Dependency Status](https://www.versioneye.com/user/projects/54ca11fbde7924f81a000010/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54ca11fbde7924f81a000010)

Just a simple user authentication solution inside a super-simple framework skeleton that works out-of-the-box
(and comes with an auto-installer), using the future-proof official bcrypt password hashing/salting implementation of 
PHP 5.5+, plus some nice features that will speed up the time from idea to first usable prototype application 
dramatically. Nothing more. This project has its focus on hardcore simplicity. Everything is as simple as possible, 
made for smaller projects, typical agency work and quick pitch drafts. If you want to build massive corporate 
applications with all the features modern frameworks have, then have a look at [Laravel](http://laravel.com), 
[Symfony](http://symfony.com) or [Yii](http://www.yiiframework.com), but if you just want to quickly create something
that just works, then this script might be interesting for you.

HUGE's simple-as-possible architecture was inspired by several conference talks, slides and articles about huge 
applications that - surprisingly and intentionally - go back to the basics of programming, using procedural programming, 
static classes, extremely simple constructs, not-totally-DRY code etc. while keeping the code extremely readable 
([StackOverflow](http://www.dev-metal.com/architecture-stackoverflow/), Wikipedia, SoundCloud).

Buzzwords: [KISS](http://en.wikipedia.org/wiki/KISS_principle), [YASNI](http://en.wikipedia.org/wiki/You_aren%27t_gonna_need_it).

#### Quick-Index 

+ [Features](#features)
+ [Live-Demo](#live-demo)
+ [Support](#support)
+ [Follow the project](#follow)
+ [License](#license)
+ [Requirements](#requirements)
+ [Auto-Installation](#auto-installation)
    - [Auto-Installation in Vagrant](#auto-installation-vagrant)
    - [Auto-Installation in Ubuntu 14.04 LTS server](#auto-installation-ubuntu)
+ [Installation (Ubuntu 14.04 LTS)](#installation)
    - [Quick Installation](#quick-installation)
    - [Detailed Installation](#detailed-installation)
+ [Documentation](#documentation)    
+ [Why is there no support forum anymore ?](#why-no-support-forum)
+ [Zero tolerance for idiots, trolls and vandals](#zero-tolerance)
+ [Contribute](#contribute)
+ [Report a bug](#bug-report)

### The History of HUGE

This script was formerly named "php-login" and by far the most popular version of the 4 simple PHP user auth
scripts of [The PHP Login Project](http://www.php-login.net) (a collection of simple login scripts, made to prevent 
people from using totally outdated and insecure MD5 password hashing, which was still very popular in the PHP world 
back in 2012).

Why the name "HUGE" ? It's a nice combination to 
[TINY](https://github.com/panique/tiny), 
[MINI](https://github.com/panique/mini) and 
[MINI2](https://github.com/panique/mini2), my other projects :)

### Features <a name="features"></a>
* built with the official PHP password hashing functions, fitting the most modern password hashing/salting web standards
* users can register, login, logout (with username, email, password)
* [planned: OAuth2 implementation for proper future-proof 3rd party auth]
* password-forget / reset
* remember-me (login via cookie)
* account verification via mail
* captcha
* failed-login-throttling
* user profiles
* account upgrade / downgrade
* supports local avatars and remote Gravatars
* supports native mail and SMTP sending (via PHPMailer and other tools)
* uses PDO for database access for sure, has nice DatabaseFactory (in case your project goes big) 
* uses URL rewriting ("beautiful URLs")
* proper split of application and public files (requests only go into /public)
* uses Composer to load external dependencies (PHPMailer, Captcha-Generator, etc.)
* fits PSR-0/1/2/4 coding guidelines
* masses of comments
* is actively developed, maintained and bug-fixed

### Live-Demo <a name="live-demo"></a>

See a [live demo here](http://demo-huge.php-login.net) and [the server's phpinfo() here](http://demo-huge.php-login.net/info.php).

### Support the project <a name="support"></a>

There a lot of work behind this project. I might save you hundreds, maybe thousands of hours of work (calculate that
in developer costs). So when you are earning money by using HUGE, be fair and give something back to open-source.
HUGE is totally free to private and commercial use.

TODO new banners

[![Donate with PayPal banner](_pictures/support-via-paypal.png)](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG)
[![Donate by server affiliate sale](_pictures/support-via-a2hosting.png)](https://affiliates.a2hosting.com/idevaffiliate.php?id=4471&url=579)

You can also rent your next $5 server at [Virpus](http://my.virpus.com/aff.php?aff=1836) or [DigitalOcean](https://www.digitalocean.com/?refcode=40d978532a20) 
or donate via [PayPal](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P5YLUK4MW3LDG).

Also feel free to contribute to this project.

### Follow the project <a name="follow"></a>

Here on **[Twitter](https://twitter.com/simplephplogin)** or **[Facebook](https://www.facebook.com/pages/PHP-Login-Script/461306677235868)**. 
I'm also blogging at **[Dev Metal](http://www.dev-metal.com)**.

### License <a name="license"></a>

Licensed under [MIT](http://www.opensource.org/licenses/mit-license.php). 
Totally free for private or commercial projects.

### Requirements <a name="requirements"></a>

Make sure you know the basics of object-oriented programming and MVC, are able to use the command line and have
used Composer before. This script is not for beginners.

* **PHP 5.5+**
* **MySQL 5** database (better use versions 5.5+ as very old versions have a [PDO injection bug](http://stackoverflow.com/q/134099/1114320)
* installed PHP extensions: pdo, gd, openssl (the install guideline shows how to do)
* installed tools on your server: git, curl, composer (the install guideline shows how to do)
* for professional mail sending: an SMTP account (I use [SMTP2GO](http://www.smtp2go.com/?s=devmetal))
* activated mod_rewrite on your server (the install guideline shows how to do)

### Auto-Installations <a name="auto-installation"></a>

Yo, fully automatic. Why ? Because I always hated it to spend days trying to find out how to install a thing.
This will save you masses of time and nerves. Donate a coffee if you like it.

#### Auto-Installation (in Vagrant) <a name="auto-installation-vagrant"></a>

If you are using Vagrant for your development, then simply 

1. Add the official Ubuntu 14.04 LTS box to your Vagrant: `vagrant box add ubuntu/trusty64`
2. Move *Vagrantfile* and *bootstrap.sh* (from *_one-click-installation* folder) to a folder where you want to initialize your project.
3. Do `vagrant up` in that folder.

5 minutes later you'll have a fully installed HUGE inside Ubuntu 14.04 LTS. The full code will be auto-synced with
the current folder. MySQL root password and the PHPMyAdmin root password are set to *12345678*. By default
192.168.33.111 is the IP of your new box.

#### Auto-Installation in a naked Ubuntu 14.04 LTS server <a name="auto-installation-ubuntu"></a>

Extremely simple installation in a fresh and naked typical Ubuntu 14.04 LTS server:

Download the installer script
```bash
wget https://raw.githubusercontent.com/panique/huge/master/_one-click-installation/bootstrap.sh
```

Make it executable
```bash
chmod +x bootstrap.sh
```

Run it! Give it some minutes to perform all the tasks. And yes, you can thank me later :)
```bash
sudo ./bootstrap.sh
```
### Installation <a name="installation"></a>

This script is very fresh, so the install guidelines are not perfect yet. 

#### Quick guide: <a name="quick-installation"></a>

0. Make sure you have Apache, PHP, MySQL installed. [Tutorial](http://www.dev-metal.com/installsetup-basic-lamp-stack-linux-apache-mysql-php-ubuntu-14-04-lts/). 
1. Clone the repo to a folder on your server
2. Activate mod_rewrite, route all traffic to application's /public folder. [Tutorial](http://www.dev-metal.com/enable-mod_rewrite-ubuntu-14-04-lts/).
3. Edit application/config: Set your database credentials
4. Execute SQL statements from application/_installation to setup database tables
5. [Install Composer](http://www.dev-metal.com/install-update-composer-windows-7-ubuntu-debian-centos/),
   run `Composer install` on application's root folder to install dependencies
6. Make avatar folder (application/public/avatars) writable
7. For proper email usage: Set SMTP credentials in config file, set EMAIL_USE_SMTP to true

"Email does not work" ? See the troubleshooting below. TODO

#### Detailed guide (Ubuntu 14.04 LTS): <a name="detailed-installation"></a>

This is just a quick guideline for easy setup of a development environment!

Make sure you have Apache, PHP 5.5+ and MySQL installed. [Tutorial here](http://www.dev-metal.com/installsetup-basic-lamp-stack-linux-apache-mysql-php-ubuntu-14-04-lts/). 
Nginx will work for sure too, but no install guidelines are available yet. 

Edit vhost to make clean URLs possible and route all traffic to /public folder of your project:
```bash
sudo nano /etc/apache2/sites-available/000-default.conf
```

and make the file look like
```
<VirtualHost *:80>
    DocumentRoot "/var/www/html/public"
    <Directory "/var/www/html/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Enable mod_rewrite and restart apache.
```bash
sudo a2enmod rewrite
service apache2 restart
```

Install curl (needed to use git), openssl (needed to clone from GitHub, as github is https only),
PHP GD, the graphic lib (we create captchas and avatars), and git.
```bash
sudo apt-get -y install curl
sudo apt-get -y install php5-curl
sudo apt-get -y install openssl
sudo apt-get -y install php5-gd
sudo apt-get -y install git
```

git clone HUGE
```bash
sudo git clone https://github.com/panique/huge "/var/www/html"
```

Install Composer
```bash
curl -s https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Go to project folder, load Composer packages (--dev is optional, you know the deal)
```bash
cd /var/www/html
composer install --dev
```

Execute the SQL statements. Via phpmyadmin or via the command line for example. 12345678 is the example password.
Note that this is written without a space.
```bash
sudo mysql -h "localhost" -u "root" "-p12345678" < "/var/www/html/application/_installation/01-create-database.sql"
sudo mysql -h "localhost" -u "root" "-p12345678" < "/var/www/html/application/_installation/02-create-table-users.sql"
sudo mysql -h "localhost" -u "root" "-p12345678" < "/var/www/html/application/_installation/03-create-table-notes.sql"
```

Make avatar folder writable
```bash
sudo chmod 0777 -R "/var/www/html/public/avatars"
```

Remove Apache's default demo file
```bash
sudo rm "/var/www/html/index.html"
```

Edit the application's config in application/config.development.php and put in your database credentials.

Last part (not needed for a first test): Set your SMTP credentials in the same file and set EMAIL_USE_SMTP to true, so
you can send proper emails. It's highly recommended to use SMTP for mail sending! Native sending via PHP's mail() will
not work in nearly every case (spam blocking). I use [SMTP2GO](http://www.smtp2go.com/?s=devmetal).

Then check your server's IP / domain. Everything should work fine.

#### Testing with demo user

By default HUGE has a demo-user: username is `demo`, password is `12345678`. The user is already activated.

### What the hell are .travis.yml, .scrutinizer.yml etc. ?

There are several files in the root folder of the project that might be irritating:

 - *.htaccess* (optionally) routes all traffic to /public/index.php! If you installed this project correctly, then this
   file is not necessary, but as lots of people have problems setting up the vhost correctly, .htaccess it still there
   to increase security, even on partly-broken-installations.
 - *.scrutinizer.yml* (can be deleted): Configs for the external code quality analyzer Scrutinizer, just used here on
   GitHub, you don't need this for your project.
 - *.travis.yml* (can be deleted): Same like above. Travis is an external service that creates installations of this
   repo after each code change to make sure everything runs fine. Also runs the unit tests. You don't need this inside
   your project.
 - *composer.json* (important): You should know what this does. ;) This file says what external dependencies are used.  
 - *travis-ci-apache* (can be deleted): Config file for Travis, see above, so Travis knows how to setup the Apache.    
    
*README* and *CHANGELOG* are self-explaining.

#### Documentation <a name="documentation"></a>

A real documentation is in the making. Until then, please have a look at the code and use your IDE's code completion 
features to get an idea how things work, it's quite obvious when you look at the controller files, the model files and
how data is shown in the view files. A big sorry that there's no documentation yet, but time is rare :)
 
 TODO: Full documentation
 TODO: Basic examples on how to do things

### Why is there no support forum (anymore) ? <a name="why-no-support-forum"></a>

There were two (!) support forums for v1 and v2 of this project (HUGE is v3), and both were vandalized by people who
didn't even read the readme and / or the install guidelines. Most asked question was "script does not work plz help"
without giving any useful information (like code or server setup or even the version used). While I'm writing these 
lines somebody just asked via Twitter "how to install without Composer". You know what I mean :) ... Beside, 140 
characters on Twitter are not a clever way to ask for / describe a complex development situation. 99% of the questions 
were not necessary if the people would had read the guidelines, do a minimal research on their own or would stop making 
things so unnecessarily complicated. And even when writing detailed answers most of them still messed it up, resulting 
in rants and complaints (for free support for a free software!). It was just frustrating to deal with this every day, 
especially when people take it for totally granted that *it's the duty* of open-source developers to give detailed, 
free and personal support for every "plz help"-request.
 
So I decided to completely stop any free support. For serious questions about real problems inside the script please
use the GitHub issues feature.

### Zero tolerance for idiots, trolls and vandals! <a name="zero-tolerance"></a>

Harsh words, but as basically every public internet project gets harassed, vandalized and trolled these days by very 
strange people it's necessary: Some simple rules. 

1. Respect that this is just a simple script written by unpaid volunteers in their free-time. 
   This is NOT business-software you've bought for $10.000.
   There's no reason to complain (!) about free open-source software. The attitude against free software
   is really frustrating these days, people take everything for granted without realizing the work behind it, and the
   fact they they get serious software totally for free, saving thousands of dollars. If you don't like it, then don't 
   use it. If you want a feature, try to take part in the process, maybe even build it by yourself and add it to the 
   project! Be nice and respectful. Constructive criticism is for sure always welcome!
   
2. Don't bash, don't hate, don't spam, don't vandalize. Don't ask for personal free support, don't ask if somebody 
   could do your work for you. Before you ask something, make sure you've read the README, followed every tutorial, 
   double-checked the code and tried to solve the problem by yourself.

Trolls and very annoying people will get a permanent ban / block. GitHub has a very powerful anti-abuse team.

### Contribute <a name="contribute"></a>

Please commit only in *develop* branch. The *master* branch will always contain the stable version.

### Found a bug (Responsible Disclosure) ? <a name="bug-report"></a>

Due to the possible consequences when publishing a bug on a public open-source project I'd kindly ask you to send really
big bugs to my email address, not posting this here. If the bug is not interesting for attackers: Feel free to create
an normal GitHub issue.

### Current and further development

See active issues and requested features here:
https://github.com/panique/huge/issues?state=open

### Useful links

- [How to use PDO](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)
- [A short guideline on how to use the PHP 5.5 password hashing functions and its PHP 5.3 & 5.4 implementations](http://www.dev-metal.com/use-php-5-5-password-hashing-functions/)
- [How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/)
- [How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)
- [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/huge/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
- [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/huge/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
- [How to prevent PHP sessions being shared between different apache vhosts / different applications](http://www.dev-metal.com/prevent-php-sessions-shared-different-apache-vhosts-different-applications/)

### Side-facts

1. Weird! When I renamed php-login to HUGE (to get rid off the too generic project name and to make it fitting nicely
   to MINI, TINY and MINI2, my other projects) I had a research if the word "huge" is already used in the php world for 
   sure. Nothing came up. Then, weeks later, I stumbled upon this: https://github.com/ffremont/HugeRest
   I nice little framework in PHP, but it has only 1 star on Github, so it's obviously not so widely used. Looks very 
   professional, too. Hmm.... The guy behind published the entire readme etc. in pure french (!), so it's hard to use 
   for non-french-speaking people. However, I'm not related to him in any way, this is pure coincidence.
