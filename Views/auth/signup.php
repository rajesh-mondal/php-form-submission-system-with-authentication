<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4>User Signup</h4>
                    </div>
                    <div class="card-body">
                        <?php
// Display error messages from session
if ( $message = App\Core\Session::get( 'error' ) ) {
    echo '<div class="alert alert-danger">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'error', null );
}
?>
                        <form action="/signup" method="POST">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <small class="form-text text-muted">Frontend validation required here (JS).</small>
                            </div>
                            <div class="form-group">
                                <label for="email">Email address (Unique)</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <small class="form-text text-muted">Password must be hashed securely (done in PHP backend).</small>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Register</button>
                        </form>
                        <p class="mt-3 text-center">
                            Already have an account? <a href="/login">Log In here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>