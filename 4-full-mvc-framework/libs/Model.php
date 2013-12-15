<?php

class Model {

    /**
     * creates a PDO database connection when a model is constructed
     * We are using the try/catch error/exception handling here
     */
    function __construct() {

        try {
            
            //We verify that it has never been instantiated.
            if(! isset($this->db)){
				$this->db = new Database();
			}

        } catch (PDOException $e) {

            die('Database connection could not be established.');

        }

    }

}
