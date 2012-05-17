PHP-Login
=========

A sexy, clean, stylish, non-nerdy, well documented, object-oriented, totally free and reduced to the max PHP login script

###HOW TO INSTALL

* 1a. import sql/users.sql into a mySQL database called "demo".
OR
* 1b. import sql/users.sql into a mySQL database and change the database constant in config/db.php ("DB_NAME").
OR
* 1c. create database "demo" and table "users" via the sql statements in sql/create_database_and_table.txt
* 2. change mySQL user and or mySQL password in config/db.php ("DB_USER" and "DB_PASS").

###HOW TO USE

* nothing to explain here ;)
* Demo user 1 is "chris" with password "123".
* Demo user 2 is "tom" with password "123".

###CONFIGURE

* you can set the lifetime of a session (until you will be logged out automatically) by changing the value of session.gc_maxlifetime in the php.ini (in seconds, for example 3600 is a hour, 36000 are ten hours)

###TROUBLESHOOTING

* do you have PHP 5.3 ?
* do you have a modern version of mySQL ?
* do you have the php mysqli (last letter is an "i") extension activated (standard) ?
* are the database connection infos in config/db.php correct ?
* does the provided database user (standard is "root") have rights to read (and write) the database ?