<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Signup</title>
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
        .signup-card {
            border: none;
            border-radius: 1rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8">
                <div class="card signup-card shadow-lg">
                    <div class="card-header bg-white text-center">
                        <h4 class="fw-bold text-primary">Register New Account</h4>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <?php
// Display error messages from session
if ( $message = App\Core\Session::get( 'error' ) ) {
    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'error', null );
}
?>
                        <form id="signupForm" action="/signup" method="POST" class="needs-validation" novalidate>

                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Name is required and must be valid.</div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address (Unique)</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">A valid email address is required.</div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Password must be at least 8 characters.</div>
                                <div class="invalid-feedback">Password is required and must meet security criteria.</div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg w-100">Register</button>
                        </form>

                        <p class="mt-4 text-center text-muted">
                            Already have an account? <a href="/login" class="text-decoration-none">Log In here</a>
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
        const form = document.getElementById('signupForm');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            let customValid = true;

            const nameInput = document.getElementById('name');
            const nameRegex = /^[a-zA-Z\s]{2,}$/;
            if (!nameRegex.test(nameInput.value.trim())) {
                nameInput.setCustomValidity("Invalid name format. Use only text/spaces, minimum 2 characters.");
                customValid = false;
            } else {
                 nameInput.setCustomValidity("");
            }

            const passwordInput = document.getElementById('password');
            if (passwordInput.value.length < 8) {
                passwordInput.setCustomValidity("Password must be at least 8 characters long.");
                customValid = false;
            } else {
                passwordInput.setCustomValidity("");
            }

            if (!customValid) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        }, false)
    })()
    </script>
</body>
</html>