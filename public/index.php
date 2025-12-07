<?php
require __DIR__ . '/../vendor/autoload.php'; // Composer Autoloader

use App\Core\Router;
use App\Core\Session;

Session::start();

$router = new Router();

// Authentication Routes
$router->add( 'GET', '/', 'AuthController@showLogin' );
$router->add( 'GET', '/login', 'AuthController@showLogin' );
$router->add( 'POST', '/login', 'AuthController@login' );
$router->add( 'GET', '/signup', 'AuthController@showSignup' );
$router->add( 'POST', '/signup', 'AuthController@signup' );
$router->add( 'GET', '/logout', 'AuthController@logout' );

// Submission Routes
$router->add( 'GET', '/submission/form', 'SubmissionController@showForm' );
$router->add( 'POST', '/submission/ajax', 'SubmissionController@submitAjax' ); // AJAX endpoint

// Report Routes
$router->add( 'GET', '/report', 'ReportController@index' );

$router->dispatch();