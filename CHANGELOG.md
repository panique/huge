# CHANGE LOG

## 2.1

**December 2014**

- [panique] EXPERIMENTAL: added static database call / DatabaseFactory, rebuild NoteModel with static methods 
- [panique] massive refactoring of mail sending, (chose between PHPMailer, SwiftMailer, native / SMTP or no SMTP)
- [panique] lots of refactorings
- [panique] refactored LoginModel'S login() method / LoginController's login() method 
- [panique] removed COOKIE_DOMAIN (cookie is now valid on the domain/IP it has been created on)
- [panique] Abstracting super-globals like $_POST['x'] into Request::post('x')
- [panique] entirely removed all the Facebook stuff [will be replaced by new proper Oauth2 solution soon]
- [panique] lots of code refactorings and cleaning, deletions of duplicate code
- [panique] moving nearly all hardcoded values to config
- [panique] new View handling: you'll have to pass vars to the view renderer now
- [panique] completely removed Facebook login process from controller (incomplete) [will be replaced by new solution]
- [panique] less config, URL/IP is auto-detected now
- [panique] added loadConfig() to load a specific config according to environment setting (fallback: development)
- [panique] added getEnvironment() to fetch (potential) environment setting
- [panique] replaced native super-globals access by wrapper access (Session:get instead of $_SESSION)
- [panique] complete frontend rebuilding (incomplete yet)
- [panique] massive cleaning of all controllers 
- [panique] added Session::add() to allow stacking of elements (useful for collecting feedback, errors etc)
- [panique] complete rebuild of model handling
- [panique] View can now render(), renderWithoutHeaderFooter() and renderJSON
- [panique] using Composer's PSR-4 autoloader (in a very basic way currently)
- [panique] DB construction needs now port by default 
- [panique] removed (semi-optional) hashing cost factor (as it's redundant usually)
- [panique] email max limit increased to 254/255 (official number)
- [panique] simpler and improved core
- [panique] improved architecture, controllers are now named like "IndexController"
- [panique] moved index.php to /public folder, new .htaccess, new installation guideline
- [panique] MVC naming fixes
- [nerdalertdk] betters paths, automatic paths
- [panique] removed legacy PHP stuff: 5.5.x is now the minimum
- [PR](https://github.com/panique/php-login/pull/503) [Malkleth] allow users to request password reset by inputting email as well as user names
- [PR](https://github.com/panique/php-login/pull/516) [pein0119] cookie runtime calculation fix 

## 2.0

**June 2014**
- [oakwilliams] Assigned correct values to FEEDBACK_EMAIL_FIELD_EMPTY and FEEDBACK_EMAIL_AND_PASSWORD_FIELDS_EMPTY
- [panique] smaller stuff in readme, changelogs etc.
- [panique] moved .htaccess from views to application folder to prevent access to anything inside application/
- [devplanete] database field optimizations

**June 6th 2014**
- started 3.0 version of the project (currently only in the develop branch), 2.0 can be found in the master branch.
  clean releases and clean tags will come soon.

**May 17th 2014**
- better description and tags in the composer.json
- .htaccess movement to application folder to prevent access to anything inside application/

**April 20th 2014**
- new header pictures, including a new donate-banner
- better default gravatar avatar (without JPEG artifacts)
- fix for #380: no broken avatars for facebook anymore when using gravatar

**April 18th 2014**
- composer.json got better dependency version definition (to avoid too new / incompatible versions being loaded)
- the captcha can be reloaded on the fly now

**March 1st 2014**
- changed link to new support forum

**February 28th 2014**
- placeholder picture and _tutorial folder added (for upcoming graphical quickstart, like on
  https://github.com/panique/php-mvc)

**February 25th 2014**
- fixed the broken logout (guys, check your commits!), introduced with last commit
- removed the last-visited-page feature
- quick fix for avatar size in .css
- added new links to new 2.0 live demo

**February 22th 2014**
- fixed #364 (Cookie deletion bug)

**February 1st 2014**
- when facebook-provided username already exists, a new one will be created, thanks to atdotslashdot for the feature
- cleaning of input is now strip_tags, not htmlentities() #341

**January 18th 2014**
- better avatar size check (issue #344), thanks to Yacine-krk for the fix

**January 4th 2014**
- fixed mod_rewrite issue when having a controller named index (which makes route problems)

**December 30th 2013**
- fixed case-sensitive model file loading
- changed ChangeLog layout

**December 29th 2013**
- beta-release into master branch
- added to packagist / Composer

**December 28th 2013**
- full installation guideline
- much improved readme

**December 22nd 2013**
- removed the self-built captcha, implemented external captcha lib (loaded via Composer)
- lots of smaller improvements

**December 15st 2013**
- other versions (one-file, minimal, advanced) of the project have been deleted and moved to own repositories

**December 1st 2013**
- massive rewrite: new folder structure
- massive rewrite: new index, new model structure
- massive rewrite: controllers can now handle more than one model
- PHPMailer is now loaded via composer
- PHP password compatibility lib is now loaded via composer
- Facebook SDK is now loaded via composer
- minimum PHP version is now declared via composer (5.3.7+)
