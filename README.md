# Sexy simple PHP Login #

Always find the latest version, a documention and other stuff on the project's home page:
http://www.php-login.net

*A sexy, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script*

## HOW TO INSTALL ##

* 1a. import sql/users.sql into a mySQL database called "demo".
OR
* 1b. import sql/users.sql into a mySQL database and change the database constant in config/db.php ("DB_NAME").
OR
* 1c. create database "demo" and table "users" via the sql statements in sql/create_database_and_table.txt
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").

###HOW TO USE

* Demo user 1 is "chris" with password "123".
* Demo user 2 is "tom" with password "123".

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###REQUIREMENTS / TROUBLESHOOTING

* needs PHP 5.3+ or PHP 5.4+
* needs mySQL 5.1+
* needs the PHP mysqli (last letter is an "i") extension activated (standard on nearly all modern servers) ?
* are the database connection infos in config/db.php correct ?
* have you created a database named "demo" like mentioned above ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?