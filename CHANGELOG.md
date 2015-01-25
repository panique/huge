# CHANGE LOG

## 3.0

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
