<?php
// loads models and handles any form submissions (e.g. login)
require_once 'core/models.php';
require_once 'core/handleForms.php';

// redirect to home if user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Funeral Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- centered login card layout -->
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1>Login</h1>

            <!-- show session message if login failed -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- login form - submits to handleForms.php -->
            <form action="core/handleForms.php" method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="submit" name="loginUserBtn" value="Login">
            </form>

            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>

</body>

</html>