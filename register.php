<?php
require_once 'core/models.php';
require_once 'core/handleForms.php';

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
    <title>Register – Funeral Home</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- centered registration card layout -->
    <div class="auth-wrapper">
        <div class="auth-card" style="max-width:480px;">
            <h1>Register</h1>

            <!-- show session message if registration failed -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($_SESSION['message']);
                    unset($_SESSION['message']); ?>
                </div>
            <?php endif; ?>

            <!-- registration form - onsubmit runs js password check before sending -->
            <form action="core/handleForms.php" method="POST" id="registerForm" onsubmit="return validatePasswords()">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <!-- oninput triggers the live match check -->
                    <input type="password" id="password" name="password" oninput="checkMatch()" required>
                    <small style="color:#888; font-size:0.8em;">Min. 8 characters, must include uppercase, lowercase,
                        and a number.</small>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" oninput="checkMatch()" required>
                    <small id="matchMsg" style="font-size:0.82em;"></small>
                </div>
                <div class="form-group">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="1" required>
                </div>
                <input type="submit" name="registerUserBtn" value="Register">
            </form>

            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>

    <script>
        function checkMatch() {
            var pw = document.getElementById('password').value;
            var cpw = document.getElementById('confirmPassword').value;
            var msg = document.getElementById('matchMsg');
            var confirmInput = document.getElementById('confirmPassword');

            if (cpw === '') {
                msg.textContent = '';
                confirmInput.style.borderColor = '';
            } else if (pw === cpw) {
                msg.textContent = '✔ Passwords match';
                msg.style.color = '#1e8449';
                confirmInput.style.borderColor = '#27ae60';
            } else {
                msg.textContent = '✖ Passwords do not match';
                msg.style.color = '#c0392b';
                confirmInput.style.borderColor = '#e74c3c';
            }
        }

        function validatePasswords() {
            var pw = document.getElementById('password').value;
            var cpw = document.getElementById('confirmPassword').value;
            if (pw !== cpw) {
                alert('Passwords do not match. Please try again.');
                return false;
            }
            return true;
        }
    </script>

</body>

</html>