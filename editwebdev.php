<?php require_once 'core/handleForms.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php require_once 'core/dbConfig.php'; ?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funeral Home – Edit Director</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- top navigation bar -->
    <nav class="navbar">
        <span class="nav-brand">Baban's Everlasting Funeral Home</span>
        <div class="nav-right">
            <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="index.php">Directors</a>
            <a href="activity_logs.php">Activity Logs</a>
            <a href="core/handleForms.php?logoutAUser=1"
                onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <div class="page-wrapper">

        <?php $director = getDirectorByID($pdo, $_GET['director_id']); ?>

        <h1>Edit Director</h1>

        <div class="card">
            <form action="core/handleForms.php?director_id=<?php echo $_GET['director_id']; ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <!-- pre-filled with existing value, htmlspecialchars prevents xss -->
                        <input type="text" id="firstName" name="firstName"
                            value="<?php echo htmlspecialchars($director['first_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName"
                            value="<?php echo htmlspecialchars($director['last_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth"
                            value="<?php echo htmlspecialchars($director['date_of_birth']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization / Role</label>
                        <input type="text" id="specialization" name="specialization"
                            value="<?php echo htmlspecialchars($director['specialization']); ?>" required>
                    </div>
                </div>
                <div class="form-row" style="margin-top:10px;">
                    <input type="submit" name="editDirectorBtn" value="Save Changes">
                    <a href="index.php" class="btn btn-secondary" style="padding:8px 16px; margin-left:10px;">Cancel</a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>