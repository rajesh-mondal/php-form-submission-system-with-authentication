<?php

namespace App\Models;

use App\Core\Database;

class User {
    private $db;

    public function __construct() {
        $dbInstance = new Database();
        $this->db = $dbInstance->getConnection();
    }

    public function findByEmail( string $email ): ?array {
        $email = $this->db->real_escape_string( $email );
        $result = $this->db->query( "SELECT * FROM users WHERE email = '$email'" );

        if ( $result && $result->num_rows > 0 ) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function create( string $name, string $email, string $password ): bool {
        // Hashing password securely
        $hashedPassword = password_hash( $password, PASSWORD_DEFAULT );

        $name = $this->db->real_escape_string( $name );
        $email = $this->db->real_escape_string( $email );

        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashedPassword')";

        return $this->db->query( $sql );
    }
}