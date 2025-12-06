<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>User Login</h4>
                    </div>
                    <div class="card-body">
                        <?php
// Display success/error messages from session
if ( $message = App\Core\Session::get( 'error' ) ) {
    echo '<div class="alert alert-danger">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'error', null );
}
if ( $message = App\Core\Session::get( 'success' ) ) {
    echo '<div class="alert alert-success">' . htmlspecialchars( $message ) . '</div>';
    App\Core\Session::set( 'success', null );
}
?>
                        <form action="/login" method="POST">
                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Log In</button>
                        </form>
                        <p class="mt-3 text-center">
                            Don't have an account? <a href="/signup">Sign Up here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>