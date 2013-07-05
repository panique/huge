<?php

class Model {

    /**
     * creates a PDO database connection when a model is constructed
     * We are using the try/catch error/exception handling here
     */
    function __construct() {

        try {

            $this->db = new Database();

        } catch (PDOException $e) {

            die('Database connection could not be established.');

        }

    }

}