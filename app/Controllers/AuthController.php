<?php

namespace App\Controllers;

use App\Core\Session;
use App\Models\User;

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin() {
        if ( Session::check() ) {
            header( 'Location: /submission/form' );
            exit;
        }
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function login() {
        if ( Session::check() ) {
            header( 'Location: /submission/form' );
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userModel->findByEmail( $email );

        if ( $user && password_verify( $password, $user['password'] ) ) {
            Session::set( 'user_id', $user['id'] );
            Session::set( 'user_name', $user['name'] );
            header( 'Location: /submission/form' );
        } else {
            Session::set( 'error', 'Invalid email or password.' );
            header( 'Location: /login' );
        }
        exit;
    }

    public function showSignup() {
        if ( Session::check() ) {
            header( 'Location: /submission/form' );
            exit;
        }
        include __DIR__ . '/../../views/auth/signup.php';
    }

    public function signup() {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if ( empty( $name ) || !filter_var( $email, FILTER_VALIDATE_EMAIL ) || strlen( $password ) < 6 ) {
            Session::set( 'error', 'Invalid input for signup.' );
            header( 'Location: /signup' );
            exit;
        }

        if ( $this->userModel->findByEmail( $email ) ) {
            Session::set( 'error', 'Email already in use.' );
            header( 'Location: /signup' );
            exit;
        }

        if ( $this->userModel->create( $name, $email, $password ) ) {
            Session::set( 'success', 'Signup successful. Please log in.' );
            header( 'Location: /login' );
        } else {
            Session::set( 'error', 'Signup failed.' );
            header( 'Location: /signup' );
        }
        exit;
    }

    public function logout() {
        Session::destroy();
        header( 'Location: /login' );
        exit;
    }
}