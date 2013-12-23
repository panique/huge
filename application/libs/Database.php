<?php

/**
 * Class Database
 * Creates a PDO database connection. This connection will be passed into the models (so we use
 * the same connection for all models and prevent to open multiple connections at once)
 */
class Database extends PDO
{
    /**
     * Construct this Database object, extending the PDO object
     * By the way, the PDO object is built into PHP by default
     */
    public function __construct()
    {
        /**
         * set the (optional) options of the PDO connection. in this case, we set the fetch mode to
         * "objects", which means all results will be objects, like this: $result->user_name !
         * For example, fetch mode FETCH_ASSOC would return results like this: $result["user_name] !
         * @see http://www.php.net/manual/en/pdostatement.fetch.php
         */
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);

        /**
         * Generate a database connection, using the PDO connector
         * @see http://net.tutsplus.com/tutorials/php/why-you-should-be-using-phps-pdo-for-database-access/
         * Also important: We include the charset, as leaving it out seems to be a security issue:
         * @see http://wiki.hashphp.org/PDO_Tutorial_for_MySQL_Developers#Connecting_to_MySQL says:
         * "Adding the charset to the DSN is very important for security reasons,
         * most examples you'll see around leave it out. MAKE SURE TO INCLUDE THE CHARSET!"
         */
        parent::__construct(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS, $options);
    }
}
