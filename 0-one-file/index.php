<?php

/**
 * A simple, clean and secure PHP Login Script
 *
 * SINGLE FILE FUNCTIONAL VERSION
 *
  * A simple PHP Login Script. Uses PHP SESSIONS, modern password-hashing
* and salting and gives the basic functions a proper login system needs.
* Please remember: this is just the minimal version of the login script,
* so if you need a more advanced version, have a look on the github repo.
* There are / will be better versions, including more functions and/or
* much more complex code / file structure.
* Keywords: MVC, dependency injected, one shared database connection,
* PDO, prepared statements, PSR-0/1/2 and documented in phpDocumentor style,
 *
 * To install this script, simply call index.php?a=install, a SQLite
 * one-file-database will then be created in your project folder.
 * This one-file script does not need a MySQL-database.
 *
 * @package php-login
 * @author Panique <panique@web.de>
 * @author Mark Constable <markc@renta.net>
 * @link https://github.com/panique/php-login/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// checking for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once("libraries/password_compatibility_library.php");
}

session_start();

// tools for debugging
//error_log('GET='.var_export($_GET, true));
//error_log('POST='.var_export($_POST, true));
//error_log('SESSION='.var_export($_SESSION, true));

echo page(init(cfg(array(
    'title' => 'Simple PHP Login',
    'admin' => 'admin',
    'email' => 'admin@localhost.lan',
    'passwd' => 'changeme',
    'db' => null,
    'dbconf' => array(
        'host' => 'localhost',
        'name' => 'users',
        'pass' => 'changeme',
        'path' => 'database/users.db',
        'port' => '3306',
        'type' => 'sqlite',
        'user' => 'root')))));

// public callable functions

function home()
{
    return isset($_SESSION['user_logged_in']) ? '
    <p>
      Hello, ' . $_SESSION['user_name'] . '. You are now logged in. Try to close
      this browser tab and open it again. Still logged in! ;)
    </p>
    <p>
      <a class="btn" href="?a=logout">Logout</a>
    </p>' : login_form();
}

function logout()
{
    $_SESSION = array();
    $_SESSION['msg'] = "You are now logged out";
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit(); // TODO: should we really use exit() ?
}

function login()
{
    if (!empty($_POST)) {
        $msg = '';
        $user = read_user($_POST['user_name']);
        if (isset($user['user_name'])) {
            if (password_verify($_POST['user_password'], $user['user_password_hash'])) {
                create_session($user);
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            } else $msg = 'Wrong password';
        } else $msg = 'User does not exist';
        $_SESSION['msg'] = $msg;
    }
    return login_form();
}

function register()
{
    if (!empty($_POST)) {
        $msg = '';
        if ($_POST['user_name']) {
            if ($_POST['user_password_new']) {
                if ($_POST['user_password_new'] === $_POST['user_password_repeat']) {
                    if (strlen($_POST['user_password_new']) > 5) {
                        if (strlen($_POST['user_name']) < 65 && strlen($_POST['user_name']) > 1) {
                            if (preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
                                $user = read_user($_POST['user_name']);
                                if (!isset($user['user_name'])) {
                                    if ($_POST['user_email']) {
                                        if (strlen($_POST['user_email']) < 65) {
                                            if (filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                                                create_user();
                                                $_SESSION['msg'] = 'You are now registered so please login';
                                                header('Location: ' . $_SERVER['PHP_SELF']);
                                                exit();
                                            } else $msg = 'You must provide a valid email address';
                                        } else $msg = 'Email must be less than 64 characters';
                                    } else $msg = 'Email cannot be empty';
                                } else $msg = 'Username already exists';
                            } else $msg = 'Username must be only a-z, A-Z, 0-9';
                        } else $msg = 'Username must be between 2 and 64 characters';
                    } else $msg = 'Password must be at least 6 characters';
                } else $msg = 'Passwords do not match';
            } else $msg = 'Empty Password';
        } else $msg = 'Empty Username';
        $_SESSION['msg'] = $msg;
    }
    return register_form();
}

function install()
{
    $dbc = cfg('dbconf');
    $dbh = cfg('db', db_init($dbc));
    $pri = $dbc['type'] === 'mysql'
        ? 'int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT'
        : 'INTEGER PRIMARY KEY';
    $ind = $dbc['type'] === 'mysql'
        ? ' ALTER TABLE `users` ADD UNIQUE (`user_name`);'
        : 'CREATE UNIQUE INDEX `user_name_UNIQUE` ON `users` (`user_name` ASC);';

    // uncomment below to reinstall tables while testing
    //$dbh->exec("DROP TABLE IF EXISTS `users`;");

    try {
        $dbh->exec("
        CREATE TABLE IF NOT EXISTS `users` (
        `user_id` $pri,
        `user_name` varchar(64),
        `user_password_hash` varchar(255),
        `user_email` varchar(64));
        $ind");
    } catch (PDOException $e) {
        die($e->getMessage()); // TODO: should we really use die() ?
    }
    $_POST['user_name'] = cfg('admin');
    $_POST['user_email'] = cfg('email');
    $_POST['user_password_new'] = cfg('passwd');
    create_user();
    $_SESSION = array();
    $_SESSION['msg'] = 'Database and default user are now installed, please login';
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// private support functions
// TODO @mark: this needs documentation/comments
function cfg($k = NULL, $v = NULL)
{
    static $stash = array();
    if (empty($k)) return $stash;
    if (is_array($k)) return $stash = array_merge($stash, $k);
    if ($v) $stash[$k] = $v;
    return isset($stash[$k]) ? $stash[$k] : NULL;
}

function init($cfg)
{
    if (!empty($_POST)) cfg('db', db_init($cfg['dbconf']));
    $action = isset($_REQUEST['a'])
        ? strtolower(str_replace(' ', '_', trim($_REQUEST['a'])))
        : 'home';
    return in_array($action, array('home', 'login', 'logout', 'register', 'install'))
        ? $action()
        : '<b>Error: action does not exist</b>';
}

function page($content)
{
    $msg = isset($_SESSION['msg']) ? '
    <p class="msg">' . $_SESSION['msg'] . '</p>' : '';
    unset($_SESSION['msg']);

    return '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>' . cfg('title') . '</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
body { margin: 0 auto; width: 42em; }
h1, h2, form, .msg { text-align: center; }
label { display: inline-block; width: 10em; text-align: right;  }
a { text-decoration: none; }
.msg, .btn { border: 1px solid #CFCFCF; padding: 0.25em 1em 0.5em 1em; border-radius: 0.3em; }
.msg { background-color: #FFEFEF; color: #DF0000; font-weight: bold; }
.btn { background-color: #EFEFEF; }
.btn:hover { background-color: #DFDFDF; }
    </style>
  </head>
  <body>
    <h1>' . cfg('title') . '</h1>
    <h2>(one-file version, with SQLite one-file database)</h2>
    ' . $msg . $content . '
  </body>
</html>
';
}

function login_form()
{
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    return '
    <form method="post" action="?a=login" name="loginform">
      <label for="login_input_username">Username</label>
      <input id="login_input_username" class="login_input" type="text" name="user_name" value="' . $user_name . '" required>
      <br>
      <label for="login_input_password">Password</label>
      <input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required>
      <br>
      <br>
      <input type="submit"  name="a" value="Login" />
      <br>
      <br>
      <a class="btn" href="?a=register">Register New Account</a>
    </form>';

}

function register_form()
{
    $user_name = isset($_POST['user_name']) ? $_POST['user_name'] : '';
    $user_email = isset($_POST['user_email']) ? $_POST['user_email'] : '';
    return '
    <form method="post" action="?a=register" name="registerform">
      <p>All fields are required. Username must be only letters and numbers from<br>
      2 to 64 characters long and the password has to be at least 6 characters.</p>
      <!-- the user name input field uses a HTML5 pattern check -->
      <label for="login_input_username">Username</label>
      <input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" value="' . $user_name . '" required>
      <br>
      <!-- the email input field uses a HTML5 email type check -->
      <label for="login_input_email">Email Address</label>
      <input id="login_input_email" class="login_input" type="email" name="user_email" value="' . $user_email . '" required>
      <br>
      <label for="login_input_password_new">Password</label>
      <input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{6,}" required autocomplete="off">
      <br>
      <label for="login_input_password_repeat">Confirm Password</label>
      <input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{6,}" required autocomplete="off">
      <br>
      <br>
      <input type="submit"  name="a" value="Register">
      <br>
      <br>
      <a class="btn" href="?a=login">&laquo; Back to Login Page</a>
    </form>';

}

// CRUD/database functions

function db_init($dbconf)
{
    extract($dbconf);
    $dsn = $type === 'mysql'
        ? 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $name
        : 'sqlite:' . $path;
    try {
        $db = new PDO($dsn, $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        die('DB Connection failed: ' . $e->getMessage());
    }
}

function create_user()
{
    $q = cfg('db')->prepare("
    INSERT INTO users (user_name, user_password_hash, user_email)
     VALUES(:user_name, :user_password_hash, :user_email)");

    $q->bindValue(":user_name", $_POST['user_name']);
    $q->bindValue(":user_password_hash", password_hash($_POST['user_password_new'], PASSWORD_DEFAULT));
    $q->bindValue(":user_email", $_POST['user_email']);
    if (!$q->execute()) throw new Exception(die($q->errorInfo()));
    $q->closeCursor();
}

function read_user($user)
{
    return cfg('db')->query("
 SELECT user_name, user_email, user_password_hash
   FROM users
  WHERE user_name = '$user'")->fetch(PDO::FETCH_ASSOC);
}

function update_user()
{
}

function delete_user()
{
}

function create_session($user)
{
    $_SESSION['user_name'] = $user['user_name'];
    $_SESSION['user_email'] = $user['user_email'];
    $_SESSION['user_logged_in'] = 1;
    $_SESSION['msg'] = $user['user_name'] . ' is now logged in';
}
