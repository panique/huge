![HUGE, formerly "php-login" logo](_pictures/huge.png)

# HUGE

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/panique/huge/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/panique/huge/?branch=master)
[![Code Climate](https://codeclimate.com/github/panique/huge/badges/gpa.svg)](https://codeclimate.com/github/panique/huge)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/01a221d168b04b1c94a85813519dab40)](https://www.codacy.com/app/panique/huge?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=panique/huge&amp;utm_campaign=Badge_Grade)
[![Travis CI](https://travis-ci.org/panique/huge.svg?branch=master)](https://travis-ci.org/panique/huge)
[![Dependency Status](https://www.versioneye.com/user/projects/54ca11fbde7924f81a000010/badge.svg?style=flat)](https://www.versioneye.com/user/projects/54ca11fbde7924f81a000010)
[![Support](https://supporterhq.com/api/b/9guz00i6rep05k1mwxyquz30k)](https://supporterhq.com/give/9guz00i6rep05k1mwxyquz30k)

Just a simple user authentication solution inside a super-simple framework skeleton that works out-of-the-box
(and comes with an auto-installer), using the future-proof official bcrypt password hashing/salting implementation of 
PHP 5.5+, plus some nice features that will speed up the time from idea to first usable prototype application 
dramatically. Nothing more. This project has its focus on hardcore simplicity. Everything is as simple as possible, 
made for smaller projects, typical agency work and quick drafts. If you want to build massive corporate 
applications with all the features modern frameworks have, then have a look at [Laravel](http://laravel.com), 
[Symfony](http://symfony.com) or [Yii](http://www.yiiframework.com), but if you just want to quickly create something
that just works, then this script might be interesting for you.

HUGE's simple-as-possible architecture was inspired by several conference talks, slides and articles about huge 
applications that - surprisingly and intentionally - go back to the basics of programming, using procedural programming, 
static classes, extremely simple constructs, not-totally-DRY code etc. while keeping the code extremely readable 
([StackOverflow](http://www.dev-metal.com/architecture-stackoverflow/), Wikipedia, SoundCloud).

Some interesting Buzzwords in this context: [KISS](http://en.wikipedia.org/wiki/KISS_principle), 
[YAGNI](http://en.wikipedia.org/wiki/You_aren%27t_gonna_need_it), [Feature Creep](https://en.wikipedia.org/wiki/Feature_creep),
[Minimum viable product](https://en.wikipedia.org/wiki/Minimum_viable_product).

#### HUGE has reached "soft End Of Life"

To keep this project stable, secure, clean and minimal I've decided to reduce the development of HUGE to a 
minimum. *Don't worry, this is actually a good thing:* New features usually mean new bugs, lots of testing, fixes, 
incompatibilities, and for some people even hardcore update stress. As HUGE is a security-critical script new features 
are not as important as a stable and secure core, this is why people use it. This means:

- HUGE will not get new features
- but will be maintained, so it will get bugfixes, corrections etc for sure, maybe for years

And to be honest, maintaining a framework for free in my rare free-time is also not what I want to do permanently. :)

Finally a little note: The PHP world has evolved dramatically, we have excellent frameworks with awesome features and 
big professional teams behind, very well written documentations and large communities, so there's simply no reason 
to put much work into another framework. Instead, please commit to the popular frameworks, then your work will have
much more impact and is used by much more people!

Thanks to everybody around this project, have a wonderful time! 
XOXO,
Chris

#### Releases & development  

* stable [v3.1](https://github.com/panique/huge/releases/tag/v3.1),
* public beta branch: [master](https://github.com/panique/huge)
* public in-development branch (please commit new code here): [develop](https://github.com/panique/huge/tree/develop)

#### Quick-Index 

+ [Features](#features)
+ [Live-Demo](#live-demo)
+ [Support](#support)
+ [Follow the project](#follow)
+ [License](#license)
+ [Requirements](#requirements)
+ [Auto-Installation](#auto-installation)
    - [Auto-Installation in Vagrant](#auto-installation-vagrant) (also useful for 100% reproducible installation of HUGE)
    - [Auto-Installation in Ubuntu 14.04 LTS server](#auto-installation-ubuntu)
+ [Installation (Ubuntu 14.04 LTS)](#installation)
    - [Quick Installation](#quick-installation)
    - [Detailed Installation](#detailed-installation)
    - [NGINX setup](#nginx-setup)
    - [IIS setup](#iis-setup)
+ [Documentation](#documentation)
    - [How to use the user roles](#user_roles)
    - [How to use the CSRF feature](#csrf)
+ [Community-provided features & feature discussions](#community)
+ [Future of the project, announcing soft EOL](#future)
+ [Why is there no support forum anymore ?](#why-no-support-forum)
+ [Zero tolerance for idiots, trolls and vandals](#zero-tolerance)
+ [Contribute](#contribute)
+ [Code-Quality scanner links](#code-quality)
+ [Report a bug](#bug-report)

### The History of HUGE

Back in 2010/2011 there were no useful login solutions in the PHP world, at least not for non-experts. So I did the worst 
mistake every young developer does: Trying to build something by myself without having any clue about security basics.
What made it even worse was: The web was (and is) full of totally broken tutorials about building user authentication 
systems, even the biggest companies in the world did this completely wrong (we are talking about SONY, LinkedIn and
Adobe here), and also lots of major framework in all big programming languages (!) used totally outdated and insecure
password saving technologies.

However, in 2012 security expert [Anthony Ferrara](https://github.com/ircmaxell) published a [little PHP library](https://github.com/ircmaxell/password_compat),
allowing extremely secure, modern and correct hashing of passwords in PHP 5.3 and 5.4, usable by every developer without any stress and without any knowledge
about security internals. The script was so awesome that it was written into the core of PHP 5.5, it's the de-facto standard these days.

When this came out I tried to use this naked library to build a fully working out-of-the-box login system for several private and commercial projects,
and put the code on GitHub. Lots of people found this useful, contributed and bugfixed the project, made forks, smaller and larger versions.
The result is this project.
 
Please note: Now, in 2015, most major frameworks have excellent user authentication logic embedded by default. This was 
not the case years ago. So, from today's perspective it might be smarter to chose Laravel, Yii or Symfony for serious
projects. But feel free to try out HUGE, the auto-installer will spin up a fully working installation within minutes and
without any configuration.

And why the name "HUGE" ? It's a nice combination to 
[TINY](https://github.com/panique/tiny), 
[MINI](https://github.com/panique/mini) and 
[MINI2](https://github.com/panique/mini2),
[MINI3](https://github.com/panique/mini3),
which are some of my other older projects. Super-minimal micro frameworks for extremely fast and simple development of simple websites.

### Features <a name="features"></a>
* built with the official PHP password hashing functions, fitting the most modern password hashing/salting web standards
* proper security features, like CSRF blocking (via form tokens), encryption of cookie contents etc.
* users can register, login, logout (with username, email, password)
* password-forget / reset
* remember-me (login via cookie)
* account verification via mail
* captcha
* failed-login-throttling
* user profiles
* account upgrade / downgrade
* simple user types (type 1, type 2, admin)
* supports local avatars and remote Gravatars
* supports native mail and SMTP sending (via PHPMailer and other tools)
* uses PDO for database access for sure, has nice DatabaseFactory (in case your project goes big) 
* uses URL rewriting ("beautiful URLs")
* proper split of application and public files (requests only go into /public)
* uses Composer to load external dependencies (PHPMailer, Captcha-Generator, etc.) for sure
* fits PSR-0/1/2/4 coding guidelines
* uses [Post-Redirect-Get pattern](https://en.wikipedia.org/wiki/Post/Redirect/Get) for nice application flow
* masses of comments
* is actively maintained and bug-fixed (however, no big new features as project slowly reaches End of Life)

### Planned features

* A real documentation (currently there's none, but the code is well commented)
  
### Live-Demo <a name="live-demo"></a>

See a [live demo of older 3.0 version here](http://104.131.8.128) and [the server's phpinfo() here](104.131.8.128/info.php).

### Support the project <a name="support"></a>

There is a lot of work behind this project. I might save you hundreds, maybe thousands of hours of work (calculate that
in developer costs). So when you are earning money by using HUGE, be fair and give something back to open-source.
HUGE is totally free to private and commercial use.

Support the project by renting a server at [1&1](http://www.jdoqocy.com/click-8225473-12015878-1477926464000) or
at [DigitalOcean](https://www.digitalocean.com/?refcode=40d978532a20). Thanks! :)

Also feel free to contribute to this project.

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

Make avatar folder writable (make sure it's the correct path!)
```bash
sudo chown -R www-data "/var/www/html/public/avatars"
```
If this doesn't work for you, then you might try the hard way by setting alternatively
```bash
sudo chmod 0777 -R "/var/www/html/public/avatars"
```

Remove Apache's default demo file
```bash
sudo rm "/var/www/html/index.html"
```

Edit the application's config in application/config/config.development.php and put in your database credentials.

Last part (not needed for a first test): Set your SMTP credentials in the same file and set EMAIL_USE_SMTP to true, so
you can send proper emails. It's highly recommended to use SMTP for mail sending! Native sending via PHP's mail() will
not work in nearly every case (spam blocking). I use [SMTP2GO](http://www.smtp2go.com/?s=devmetal).

Then check your server's IP / domain. Everything should work fine.

#### NGINX setup: <a name="nginx-setup"></a>

This is an untested NGINX setup. Please comment [on the ticket](https://github.com/panique/huge/issues/622) if you see 
issues.
 
```
server {
    # your listening port
    listen 80;

    # your server name
    server_name example.com;

    # your path to access log files
    access_log /srv/www/example.com/logs/access.log;
    error_log /srv/www/example.com/logs/error.log;

    # your root
    root /srv/www/example.com/public_html;

    # huge
    index index.php;

    # huge
    location / {
        try_files $uri /index.php?url=$uri&$args;
    }

    # your PHP config
    location ~ \.php$ {
        try_files $uri  = 401;
        include /etc/nginx/fastcgi_params;
        fastcgi_pass unix:/var/run/php-fastcgi/php-fastcgi.socket;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

#### IIS setup: <a name="iis-setup"></a>

Big thanks to razuro for this fine setup: Put this inside your root folder, but don't put any web.config in your public 
folder.

```
<?xml version="1.0" encoding="UTF-8"?><configuration>
    <system.webServer>
        <rewrite>
            <rules>
			
                <rule name="Imported Rule 1" stopProcessing="true">
                    <match url="^(.*)$" ignoreCase="false" />
					<conditions logicalGrouping="MatchAll">
                        <add input="{REQUEST_FILENAME}" matchType="IsDirectory" ignoreCase="false" negate="true" />
                        <add input="{REQUEST_FILENAME}" matchType="IsFile" ignoreCase="false" negate="true" />
                    </conditions>
                    <action type="Rewrite" url="public/index.php?url={R:1}" />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
```

Find the original [ticket here](https://github.com/panique/huge/issues/788).

#### Testing with demo users

By default there are two demo users, a normal user and an admin user. For more info on that please have a look on the
user role part of the small documentation block inside this readme.
 
Normal user: Username is `demo2`, password is `12345678`. The user is already activated.
Admin user (can delete and suspend other users): Username is `demo`, password is `12345678`. The user is already activated.

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

### Documentation <a name="documentation"></a>

A real documentation is in the making. Until then, please have a look at the code and use your IDE's code completion 
features to get an idea how things work, it's quite obvious when you look at the controller files, the model files and
how data is shown in the view files. A big sorry that there's no documentation yet, but time is rare and we are all
doing this for free in our free time :)
 
 - TODO: Full documentation
 - TODO: Basic examples on how to do things
 
#### How to use the different user roles <a name="user_roles"></a>

Currently there are two types of users: Normal users and admins. There are exactly the same, but...
 
1. Admin users can delete and suspend other users, they have an additional button "admin" in the navigation. Admin users
have a value of `7` inside the database table field `user_account_type`. They cannot upgrade or downgrade their accounts 
(as this wouldn't make sense).

2. Normal users don't have admin features for sure. But they can upgrade and downgrade their accounts (try it out via
/user/changeUserRole), which is basically a super-simple implementation of the basic-user / premium-user concept. 
Normal users have a value of `1` or `2` inside the database table field `user_account_type`. By default all new 
registered users are normal users with user role 1 for sure.

See the "Testing with demo users" section of this readme for more info.

There's also a very interesting [pull request adding user roles and user permissions](https://github.com/panique/huge/pull/691),
which is not integrated into the project as it's too advanced and complex. But, this might be exactly what you need,
feel free to try.

#### How to use the CSRF feature <a name="csrf"></a>
 
To prevent [CSRF attacks](https://en.wikipedia.org/wiki/Cross-site_request_forgery), HUGE does this in the most common 
way, by using a security *token* when the user submits critical forms. This means: When PHP renders a form for the user, 
the application puts a "random string" inside the form (as a hidden input field), generated via Csrf::makeToken() 
(application/core/Csrf.php), which also saves this token to the session. When the form is submitted, the application 
checks if the POST request contains exactly the form token that is inside the session.
  
This CSRF prevention feature is currently implemented on the login form process (see *application/view/login/index.php*)
and user name change form process (see *application/view/user/editUsername.php*), most other forms are not security-
critical and should stay as simple as possible.

So, to do this with a normal form, simply: At your form, before the submit button put:
`<input type="hidden" name="csrf_token" value="<?= Csrf::makeToken(); ?>" />`
Then, in the controller action validate the CSRF token submitted with the form by doing:
```
// check if csrf token is valid
if (!Csrf::isTokenValid()) {
    LoginModel::logout();
    Redirect::home();
    exit();
}
```

A big thanks to OmarElGabry for implementing this!

#### Can a user be logged in from multiple devices ?

In theory: Yes, but this feature didn't work in my tests. As it's an external feature please have a look into the 
[according ticket](https://github.com/panique/huge/pull/693) for more.

#### Troubleshooting & Glitches

* In 3.0 and 3.1 a user could log into the application from different devices / browsers / locations. This was intended
  behaviour as this is standard in most web applications these days. In 3.2 still feature is "missing" by default, a 
  user will only be able to log in from one browser at the same time. This is a security improvement, but for sure not 
  optimal for many developers. The plan is to implement a config switch that will allow / disallow logins from multiple 
  browsers.
* Using this on a sub-domain ? You might get problems with the cookies in IE11. Fix this by replacing "/" with "./" of 
  the cookie location COOKIE_PATH inside application/config/config.xxx.php! 
  Check [ticket #733](https://github.com/panique/huge/issues/733) for more info. Thanks to jahbiuabft for figuring this
  out. Update: There's another ticket focusing on the same issue: [ticket #681](https://github.com/panique/huge/issues/681)
 
### Community-provided features & feature discussions <a name="community"></a>

There are some awesome features or feature ideas build by awesome people, but these features are too special-interest
to go into the main version of HUGE, but have a look into these tickets if you are interested:

 - [Caching system](https://github.com/panique/huge/issues/643)
 - [ReCaptcha as captcha](https://github.com/panique/huge/issues/665)
 - [Internationalization feature](https://github.com/panique/huge/issues/582)
 - [Using controller A inside controller B](https://github.com/panique/huge/issues/706)
 - [HTML mails](https://github.com/panique/huge/issues/738)
 - [Deep user roles / user permission system](https://github.com/panique/huge/pull/691)
 
### Future of HUGE: Announcing "soft End Of Life" <a name="future"></a>
 
The idea of this project is and was to provide a super-simple barebone application with a full user authentication
system inside that just works fine and stable. Due to the highly security-related nature of this script any changes 
mean a lot of work, lots of testing, catching edge cases etc., and in the end I spent 90% of the time testing and fixing
new features or new features break existing stuff, and doing this is really not what anybody wants to do for free in
the rare free-time :)

To keep the project stable, clean and maintainable, I would kindly announce the "soft-End of Life" for this project, 
meaning:

A. HUGE will not get any new features in the future, but ...
B. bugfixes and corrections will be made, probably for years

### Coding guideline behind HUGE

While HUGE was in development, there were 3 main rules that helped me (and probably others) to write minimal, clean
 and working code. Might be useful for you too:

1. Reduce features to the bare minimum.
2. Don't implement features that are not needed by most users.
3. Only build everything for the most common use case (like MySQL, not PostGre, NoSQL etc).

As noted in the intro of this README, there are also some powerful concepts that might help you when developing cool 
stuff: [KISS](http://en.wikipedia.org/wiki/KISS_principle), 
[YAGNI](http://en.wikipedia.org/wiki/You_aren%27t_gonna_need_it), [Feature Creep](https://en.wikipedia.org/wiki/Feature_creep),
[Minimum viable product](https://en.wikipedia.org/wiki/Minimum_viable_product).
 
#### List of features / ideas provided in tickets / pull requests

To avoid unnecessary work for all of us I would kindly recommend everybody to use HUGE for simple project that only
need the features that already exist, and if you really need a RESTful architecture, migrations, routing, 2FA etc,
then it's easier, cleaner and faster to simply use Laravel, Symfony or Zend.

However, here are the community-suggested possible features, taken from lots of tickets. Feel free to implement them
into your forks of the project: 

* OAuth2 implementation (let your users create accounts and login via 3rd party auth, like Facebook, Twitter, GitHub, 
  etc). As this is a lot of work and would make the project much more complicated it might make sense to do this in a 
  fork or totally skip it. (see [Ticket #528](https://github.com/panique/huge/issues/528))
* Router (map all URLs to according controller-methods inside one file), [Ticket 727](https://github.com/panique/huge/issues/727)
* RESTful architecture (see [ticket #488](https://github.com/panique/huge/issues/488) for discussion)
* Horizontal MySQL scaling (see [ticket #423](https://github.com/panique/huge/issues/423) for discussion)
* Modules / middleware
* Logging
* Two-Factor-Authentication (see [ticket #732](https://github.com/panique/huge/issues/732))
* Controller-less URLs (see [ticket #704](https://github.com/panique/huge/issues/704))
* Email-re-validation after email change (see [ticket #705](https://github.com/panique/huge/issues/705))
* Connect to multiple databases (see [ticket #702](https://github.com/panique/huge/issues/702))
* A deeper user role system (see [ticket #701](https://github.com/panique/huge/issues/701), 
[pull-request #691](https://github.com/panique/huge/pull/691)), 
[ticket #603](https://github.com/panique/huge/issues/603)
* How to run without using Composer [ticket #826](https://github.com/panique/huge/issues/826)

### Why is there no support forum (anymore) ? <a name="why-no-support-forum"></a>

There were two (!) support forums for v1 and v2 of this project (HUGE is v3), and both were vandalized by people who
didn't even read the readme and / or the install guidelines. Most asked question was "script does not work plz help"
without giving any useful information (like code or server setup or even the version used). While I'm writing these 
lines somebody just asked via Twitter "how to install without Composer". You know what I mean :) - 99% of the questions 
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
   fact that they get serious software totally for free, saving thousands of dollars. If you don't like it, then don't 
   use it. If you want a feature, try to take part in the process, maybe even build it by yourself and add it to the 
   project! Be nice and respectful. Constructive criticism is for sure always welcome!
   
2. Don't bash, don't hate, don't spam, don't vandalize. Please don't ask for personal free support, don't ask if 
   somebody could do your work for you. Before you ask something, make sure you've read the README, followed every 
   tutorial, double-checked the code and tried to solve the problem by yourself.

Trolls and very annoying people will get a permanent ban / block. GitHub has a very powerful anti-abuse team.

### Contribute <a name="contribute"></a>

Please commit only in *develop* branch. The *master* branch will always contain the stable version.

### Code-Quality scanner links <a name="code-quality"></a>

[Scrutinizer (master branch)](https://scrutinizer-ci.com/g/panique/huge/?branch=master),
[Scrutinizer (develop branch)](https://scrutinizer-ci.com/g/panique/huge/?branch=develop),
[Code Climate](https://codeclimate.com/github/panique/huge),
[Codacy](https://www.codacy.com/public/panique/phplogin/dashboard?bid=789836), 
[SensioLabs Insight](https://insight.sensiolabs.com/projects/d4f4e3c0-1445-4245-8cb2-d75026c11fa7/analyses/2).

### Found a bug (Responsible Disclosure) ? <a name="bug-report"></a>

Due to the possible consequences when publishing a bug on a public open-source project I'd kindly ask you to send really
big bugs to my email address, not posting this here. If the bug is not interesting for attackers: Feel free to create
an normal GitHub issue.

### Current and further development

See active issues here:
https://github.com/panique/huge/issues?state=open

### Why you should use a favicon.ico in your project :)

Interesting issue: When a user hits your website, the user's browser will also request one or more (!) favicons 
(different sizes). If these static files don't exist, your application will start to generate a 404 response and a 404 
page for each file. This wastes a lot of server power and is also useless, therefore make sure you always have favicons
or handle this from Apache/nginx level.

HUGE tries to handle this by sending an empty image in the head of the view/_templates/header.php !

More inside this ticket: [Return proper 404 for missing favicon.ico, missing images etc.](https://github.com/panique/huge/issues/530)

More here on Stackflow: [How to prevent favicon.ico requests?](http://stackoverflow.com/questions/1321878/how-to-prevent-favicon-ico-requests),
[Isn't it silly that a tiny favicon requires yet another HTTP request? How to make favicon go into a sprite?](http://stackoverflow.com/questions/5199902/isnt-it-silly-that-a-tiny-favicon-requires-yet-another-http-request-how-to-mak?lq=1).

### Useful links

- [How long will my session last?](http://stackoverflow.com/questions/1516266/how-long-will-my-session-last/1516338#1516338)
- [How to do expire a PHP session after X minutes?](http://stackoverflow.com/questions/520237/how-do-i-expire-a-php-session-after-30-minutes/1270960#1270960)
- [How to use PDO](http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)
- [A short guideline on how to use the PHP 5.5 password hashing functions and its PHP 5.3 & 5.4 implementations](http://www.dev-metal.com/use-php-5-5-password-hashing-functions/)
- [How to setup latest version of PHP 5.5 on Ubuntu 12.04 LTS](http://www.dev-metal.com/how-to-setup-latest-version-of-php-5-5-on-ubuntu-12-04-lts/)
- [How to setup latest version of PHP 5.5 on Debian Wheezy 7.0/7.1 (and how to fix the GPG key error)](http://www.dev-metal.com/setup-latest-version-php-5-5-debian-wheezy-7-07-1-fix-gpg-key-error/)
- [Notes on password & hashing salting in upcoming PHP versions (PHP 5.5.x & 5.6 etc.)](https://github.com/panique/huge/wiki/Notes-on-password-&-hashing-salting-in-upcoming-PHP-versions-%28PHP-5.5.x-&-5.6-etc.%29)
- [Some basic "benchmarks" of all PHP hash/salt algorithms](https://github.com/panique/huge/wiki/Which-hashing-&-salting-algorithm-should-be-used-%3F)
- [How to prevent PHP sessions being shared between different apache vhosts / different applications](http://www.dev-metal.com/prevent-php-sessions-shared-different-apache-vhosts-different-applications/)

## Interesting links regarding user authentication and application security

- [interesting article about password resets (by Troy Hunt, security expert)](http://www.troyhunt.com/2012/05/everything-you-ever-wanted-to-know.html)
- Password-Free Email Logins: [Ticket & discussion](https://github.com/panique/huge/issues/674), [article](http://techcrunch.com/2015/06/30/blogging-site-medium-rolls-out-password-free-email-logins/?ref=webdesignernews.com)
- Logging in via QR code: [Ticket & discussion](https://github.com/panique/huge/issues/290), [english article](https://www.grc.com/sqrl/sqrl.htm), 
  [german article](http://www.phpgangsta.de/sesam-oeffne-dich-sicher-einloggen-im-internetcafe), 
  [repo](https://github.com/PHPGangsta/Sesame), [live-demo](http://sesame.phpgangsta.de/). Big thanks to *PHPGangsta* for writing this!
  
### My blog

I'm also blogging at **[Dev Metal](http://www.dev-metal.com)**.
