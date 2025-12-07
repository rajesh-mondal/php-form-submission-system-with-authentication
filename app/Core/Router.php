<?php

namespace App\Core;

class Router {
    private $routes = [];

    public function add( $method, $uri, $controllerAction ) {
        $this->routes[$method][$uri] = $controllerAction;
    }

    public function dispatch() {
        $uri = strtok( $_SERVER['REQUEST_URI'], '?' );
        $method = $_SERVER['REQUEST_METHOD'];

        if ( isset( $this->routes[$method][$uri] ) ) {
            $controllerAction = $this->routes[$method][$uri];
            list( $controllerName, $action ) = explode( '@', $controllerAction );

            $controllerClass = "App\\Controllers\\" . $controllerName;

            if ( class_exists( $controllerClass ) ) {
                $controller = new $controllerClass();
                if ( method_exists( $controller, $action ) ) {
                    $controller->$action();
                    return;
                }
            }
        }

        // Handle 404
        http_response_code( 404 );
        echo "404 Not Found";
    }
}