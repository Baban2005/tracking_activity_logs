<?php
// loads db connection and model functions
require_once 'core/models.php';
require_once 'core/dbConfig.php';

// redirect to login if no session is active
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
    <title>Funeral Home – Delete Director</title>
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

        <!-- fetch director details to show a confirmation summary -->
        <?php $director = getDirectorByID($pdo, $_GET['director_id']); ?>

        <h1>Delete Director</h1>

        <div class="card">
            <!-- warn the user that deleting a director also removes all their services -->
            <div class="alert alert-danger" style="margin-bottom:16px;">
                ⚠ Warning: Deleting this director will also permanently remove all their associated services.
            </div>

            <!-- show director info before confirming deletion -->
            <table style="margin-top:0; box-shadow:none;">
                <tr>
                    <th style="width:180px; background:#f8f9fa; color:#333;">Username</th>
                    <td><?php echo htmlspecialchars($director['username']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">First Name</th>
                    <td><?php echo htmlspecialchars($director['first_name']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Last Name</th>
                    <td><?php echo htmlspecialchars($director['last_name']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Date of Birth</th>
                    <td><?php echo htmlspecialchars($director['date_of_birth']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Specialization</th>
                    <td><?php echo htmlspecialchars($director['specialization']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Date Added</th>
                    <td><?php echo htmlspecialchars($director['date_added']); ?></td>
                </tr>
            </table>

            <!-- confirm or cancel deletion -->
            <div style="margin-top:20px; display:flex; gap:12px;">
                <form action="core/handleForms.php?director_id=<?php echo $_GET['director_id']; ?>" method="POST">
                    <input type="submit" name="deleteDirectorBtn" value="Yes, Delete Director"
                        style="background-color:#d63031;"
                        onclick="return confirm('This action cannot be undone. Confirm delete?');">
                </form>
                <a href="index.php" class="btn btn-secondary" style="padding:8px 18px;">Cancel</a>
            </div>
        </div>

    </div>

</body>

</html>