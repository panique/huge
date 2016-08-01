# CHANGE LOG

For the newest (und unstable) version always check the develop branch, for beta check 
master branch, for really stable stuff check the releases (the ones that have a real version number :))

## master branch

- [slaveek/panique] [PR](https://github.com/panique/huge/pull/773) [#770] fix for sending user back to last visited page after login
- [slaveek] [PR](https://github.com/panique/huge/pull/815) lots of code styling fixes 
- [panique] [#729] Fix, mail sending now returns true or false success status (https://github.com/panique/huge/issues/729)
- [OmarElGabry] [PR](https://github.com/panique/huge/pull/693) session id regeneration in certain situations
- [OmarElGabry] [PR](https://github.com/panique/huge/pull/693) encrypted cookies
- [OmarElGabry] [PR](https://github.com/panique/huge/pull/693) new encryption class
- [OmarElGabry] [PR](https://github.com/panique/huge/pull/693) anti-CSRF feature (used in login and username change forms) 
- [josh-bridge] [PR](https://github.com/panique/huge/pull/689) logged-in user can now change password
- [justincdotme] [PR](https://github.com/panique/huge/pull/684) better code for brute-force blocking when logging in
- [panique] soft autoinstaller improvements
- [panique] updated dependencies to current versions
- [Kent55/panique] XSS protection filter
- [FAlbanni] XSS protection with better session/cookie, now only allowed on used domain 
- [panique] there's now a simple Favicon and a fallback to avoid browsers hammering the application requesting favicons
- [panique] application has now a page title
- [panique] avatar upload feature can now handle jpg, png, gif
- [panique/tankerkiller125] avatars folder now does not run any PHP code (security improvement) 
- [tysonlist] [#657] send user back to last-visited page after successful login (when not being logged in first)
- [sandropons] anti-brute-force feature for login process
- [panique] removed old Facebook texts (as Login-via-Facebook feature was removed since 3.0)
- [oisian/ldmusic] [#608] Deletion / suspension of users, Admin menu
- [panique] [#654](https://github.com/panique/huge/issues/654) little frontend navi bug fixed
- [Dominic28] [PR](https://github.com/panique/huge/pull/645) added checkboxes to request class
- [Dominic28] [PR](https://github.com/panique/huge/pull/644) code style fixes
- [M0ritzWeide] [PR](https://github.com/panique/huge/pull/635) added browser caching
- [modInfo/panique] [PR](https://github.com/panique/huge/pull/647) added missing view table column  

## 3.1

Code Quality at Scrutinizer 9.7/10, at Code Climate 3.9/4

**February 2015**

- [panique] several code quality improvements (and line reductions :) ) all over the project
- [PR](https://github.com/panique/huge/pull/620) [owenr88] view rending now possible with multiple view files
- [panique] lots of code refactorings and simplifications all over the project
- [PR](https://github.com/panique/huge/pull/615) [Dominic28] Avatar can now be deleted by the user
- [panique] First Unit tests :)
- [panique] several code quality improvements all over the project
- [panique] avatarModel code improvements
- [panique] renamed AccountType stuff to UserRole, minor changes 

## 3.0

Code Quality at Scrutinizer 9.3/10, at Code Climate 3.9/4

**February 2015**

- [panique] removed duplicate code in AccountTypeModel
- [PR](https://github.com/panique/huge/pull/587) [upperwood] Facebook stuff completely removed from SQL
- [panique] tiny text changes

**January 2015**

- [panique] added static Text class (gets the messages etc)
- [panique] added static Environment class (get the environment)
- [panique] added static Config class (gets config easily and according to environment)
- [panique] new styling of the entire project: login/index has new look now 
- [panique] massive refactoring of all model classes: lots of methods have been organized into other model classes
- [panique] massive refactoring of all model classes: all methods are static now
- [panique] EXPERIMENTAL: added static database call / DatabaseFactory, rebuild NoteModel with static methods 
- [panique] massive refactoring of mail sending, (chose between PHPMailer, SwiftMailer, native / SMTP or no SMTP)

**December 2014**

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
