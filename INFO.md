
## What's new ?

- professional file/folder structure
- implemented the "always return something" rule, with default return
- IN PROGRESS: keep if/else nesting as flat as possible
- implemented dependency injected database connection (we open just one connection, use it for all models)
- multiple models allowed per controller
- everything is "as manual as possible"

## Security improvements

1. All PDO and MySQLi connect statements have a charset (more here in the "Connecting to MySQL section":
http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers)
2.

## How the facebook login process works

https://github.com/facebook/facebook-php-sdk
https://developers.facebook.com/docs/facebook-login/checklist
https://developers.facebook.com/docs/user_registration/flows/

## Used packages (via composer)

PHPMailer
https://packagist.org/packages/phpmailer/phpmailer

PHP password compatibility library
https://packagist.org/packages/ircmaxell/password-compat

Facebook SDK
https://packagist.org/packages/facebook/php-sdk

## To test facebook login LOCALLY:

When you register a new application on facebook (to qualify for facebook login) you'll have to provide the URL
of your project. This gets tricky when developing locally, but this is how it works:

For "App Domain", use "localhost".
For Sandbox mode, use "deactivated".
For Page URL, use "http://localhost/".
