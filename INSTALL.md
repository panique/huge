# Installation

Full installation tutorial coming soon, but here's a quick guide for people who know what they are doing

1. Setup mod_rewrite on your server (described in the README)
2. Install composer, in linux:
`curl -sS https://getcomposer.org/installer | php` and then
`sudo mv composer.phar /usr/local/bin/composer`
In Win / Mac use the installers provided on the composer website.
2. Copy (or professionally deploy) the app to your server
3. Make the folder public/avatars writable via `chmod 775 public/avatars` or `chmod 777 public/avatars`
4. Run the three SQL statements in the application/_install/sql_statements folder
5. Enter your database credentials in application/config/config.php
6. Enter your project URL in application/config/config.php, including sub-folders. For local development, localhost or
127.0.0.1 will do too.
7. Change the domain in COOKIE_DOMAIN in application/config/config.php to your needs. Note: there needs to be a dot in front of it!
8. Enter your SMTP provider credentials and set EMAIL_USE_SMTP to true in application/config/config.php.
And please remember: No, you cannot simply send emails with PHP's mail() function, this does not really work due
to a lot of reasons.
9. change the RewriteBase in .htaccess to the sub-folder of your web/ht_docs/www directory (not path!, not URL!) where your app is in.
      If your app is in the root of your web folder, then please delete this line. If it's in var/www/myapp, then your line should look like
      RewriteBase /myapp/

10. Go into the base folder of your application (where composer.json is) and do "composer install" on the command line

10. OPTIONAL: Change the text, reply-mail-adress etc. of the EMAIL_PASSWORD_RESET_SUBJECT etc. in
application/config/config.php

## To use the (additional) facebook login

1. Go to https://developers.facebook.com/apps/ and create a new app.
1.1. The type needs to be "Website with Facebook-authentication" (or however it is translated)
1.2. In "App Domains" put the URL of your project, like example.com ! For local development "localhost" works.
Things like "127.0.0.1" don't seem work.
1.3. In sandbox mode, select "deactivated"
1.4. In "Site address", put your URL with the protocol in front, like "http://www.example.com". For local development,
"http://localhost/" works. Things like "http://127.0.0.1" don't seem work.
1.5. Can you see your facebook app id and the secret token now ? Perfect!

2. Set `define('FACEBOOK_LOGIN', false);` in application/config/config.php to  true
3. Put your facebook app id and the secret token in FACEBOOK_LOGIN_APP_ID and FACEBOOK_LOGIN_APP_SECRET

TODO: Is it possible to automate things here ?
NOTE: Currently the app shows the current content of $_SESSION in the footer via KINT, an excellent high-end version
of var_dump(). KINT is installed automatically via composer and called in the footer.php via d($_SESSION);
See https://github.com/raveren/kint for more...