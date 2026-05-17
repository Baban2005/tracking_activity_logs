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
    <title>Funeral Home – Edit Service</title>
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

        <?php $service = getServiceByID($pdo, $_GET['service_id']); ?>

        <h1>Edit Service</h1>

        <!-- edit form - both service_id and director_id are passed via URL -->
        <div class="card">
            <form
                action="core/handleForms.php?service_id=<?php echo $_GET['service_id']; ?>&director_id=<?php echo $_GET['director_id']; ?>"
                method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="serviceName">Service Name</label>
                        <input type="text" id="serviceName" name="serviceName"
                            value="<?php echo htmlspecialchars($service['service_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="packageType">Package Type / Details</label>
                        <input type="text" id="packageType" name="packageType"
                            value="<?php echo htmlspecialchars($service['package_type']); ?>" required>
                    </div>
                </div>
                <div class="form-row" style="margin-top:10px;">
                    <input type="submit" name="editServiceBtn" value="Save Changes">
                    <a href="viewprojects.php?director_id=<?php echo $_GET['director_id']; ?>" class="btn btn-secondary"
                        style="padding:8px 16px; margin-left:10px;">Cancel</a>
                </div>
            </form>
        </div>

    </div>

</body>

</html>