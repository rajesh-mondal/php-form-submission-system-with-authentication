<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            border-bottom: none;
            padding-top: 2rem;
            padding-bottom: 0.5rem;
        }
        .login-card {
            border: none;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card login-card shadow-lg">
                    <div class="card-header bg-white text-center">
                        <h4 class="fw-bold text-primary">Log In to Your Account</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <?php
// Display error messages from session
if ( $message = App\Core\Session::get( 'error' ) ) {
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'error', null );
}
// Display success messages from session
if ( $message = App\Core\Session::get( 'success' ) ) {
    echo '<div class="alert alert-success" role="alert">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'success', null );
}
?>
                        <form id="loginForm" action="/login" method="POST" class="needs-validation" novalidate>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">Please enter a valid email address.</div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback">Password is required.</div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Log In</button>
                        </form>

                        <p class="mt-4 text-center text-muted">
                            Don't have an account? <a href="/signup" class="text-decoration-none">Sign Up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
    (function () {
        'use strict'
        const form = document.getElementById('loginForm');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false)
    })()
    </script>
</body>
</html>