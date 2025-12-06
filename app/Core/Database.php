<?php

namespace App\Core;

class Database {
    private $connection;
    private $host = 'localhost';
    private $user = 'root'; // Change these
    private $pass = ''; // Change these
    private $dbName = 'roxnor_db';

    public function __construct() {
        // Suppress errors for cleaner connection attempt
        $this->connection = @new \mysqli( $this->host, $this->user, $this->pass, $this->dbName );

        if ( $this->connection->connect_error ) {
            die( "Connection failed: " . $this->connection->connect_error );
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}