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
    <title>Funeral Home – Delete Service</title>
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

        <!-- fetch service details to show a confirmation summary -->
        <?php $service = getServiceByID($pdo, $_GET['service_id']); ?>

        <h1>Delete Service</h1>

        <div class="card">
            <!-- warn the user before proceeding -->
            <div class="alert alert-danger" style="margin-bottom:16px;">
                ⚠ Are you sure you want to permanently delete this service?
            </div>

            <!-- show service info before confirming deletion -->
            <table style="margin-top:0; box-shadow:none;">
                <tr>
                    <th style="width:180px; background:#f8f9fa; color:#333;">Service Name</th>
                    <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Package Type</th>
                    <td><?php echo htmlspecialchars($service['package_type']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Assigned Director</th>
                    <td><?php echo htmlspecialchars($service['assigned_director']); ?></td>
                </tr>
                <tr>
                    <th style="background:#f8f9fa; color:#333;">Date Added</th>
                    <td><?php echo htmlspecialchars($service['date_added']); ?></td>
                </tr>
            </table>

            <!-- confirm or cancel deletion, both service_id and director_id passed via URL -->
            <div style="margin-top:20px; display:flex; gap:12px;">
                <form
                    action="core/handleForms.php?service_id=<?php echo $_GET['service_id']; ?>&director_id=<?php echo $_GET['director_id']; ?>"
                    method="POST">
                    <input type="submit" name="deleteServiceBtn" value="Yes, Delete Service"
                        style="background-color:#d63031;"
                        onclick="return confirm('This action cannot be undone. Confirm delete?');">
                </form>
                <!-- cancel goes back to the services list -->
                <a href="viewprojects.php?director_id=<?php echo $_GET['director_id']; ?>"
                    class="btn btn-secondary" style="padding:8px 18px;">Cancel</a>
            </div>
        </div>

    </div>

</body>
</html>
