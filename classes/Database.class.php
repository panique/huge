<?php

/**
 * class DB (database)
 * creates a database connection (that can be passed to all classes/objects that need this database connection)
 * 
 * @author Panique <panique@web.de>
 * @version 1.1
 */
class Database
{
    
    /**
     * The database connection. This variable/object contains the connection to your database.
     * This connection can be passed to everything in your app that needs database access.
     * Afaik this is currently the most sexy and professional way to handle a database connection.
     * @access private
     * @var object
     */
    private $connection = null;

    
    /**
     * the function "__construct()" automatically starts whenever an object of this class is created,
     * you know, when you do "$connection = new Database();"
     * @return boolean  returns true if we have a database connection, false if not
     */
    public function __construct()
    {
        // does db connection already exist (or is it null) ?
        if (!$this->connection) {
            // create db connection, using the constants from config/db.php
            $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);                        
            // if no connection errors: return true else false
            return (!$this->connection->connect_errno ? true : false);
        }
    }

    
    /**
     * get the database connection. this is needed by all the classes that need a database connection.
     * @return object   returns an object that contains the database connection
     */
    public function getDatabaseConnection()
    {
        return $this->connection;
    }
    
    
    /**
     * gets the error code (or nothing of everything runs good)
     * @see http://www.php.net/manual/en/mysqli.connect-errno.php
     * @return int  database connection error code
     */    
    public function getDatabaseError()
    {
        return $this->connection->connect_errno;
    }
    
}
