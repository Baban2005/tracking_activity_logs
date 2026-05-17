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
    <title>Funeral Home – Services</title>
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

        <h1>Services – <?php echo htmlspecialchars($director['first_name'] . ' ' . $director['last_name']); ?></h1>
        <p class="text-muted" style="margin-bottom:16px;">
            Username: <?php echo htmlspecialchars($director['username']); ?> &nbsp;|&nbsp;
            Specialization: <?php echo htmlspecialchars($director['specialization']); ?>
        </p>

        <!-- Add Service Form -->
        <div class="card">
            <h2>Add New Service</h2>
            <form action="core/handleForms.php?director_id=<?php echo $_GET['director_id']; ?>" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="serviceName">Service Name</label>
                        <input type="text" id="serviceName" name="serviceName" required>
                    </div>
                    <div class="form-group">
                        <label for="packageType">Package Type / Details</label>
                        <input type="text" id="packageType" name="packageType" required>
                    </div>
                    <div class="form-group" style="flex:0; align-self:flex-end;">
                        <input type="submit" name="insertNewServiceBtn" value="Add Service">
                    </div>
                </div>
            </form>
        </div>

        <!-- Services Table -->
        <h2>Services List</h2>
        <?php $services = getServicesByDirector($pdo, $_GET['director_id']); ?>
        <?php if (count($services) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Package Type</th>
                        <th>Assigned Director</th>
                        <th>Date Added</th>
                        <th>Added By</th>
                        <th>Last Updated By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $row): ?>
                        <tr>
                            <!-- htmlspecialchars on all output to prevent xss -->
                            <td><?php echo htmlspecialchars($row['service_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['package_type']); ?></td>
                            <td><?php echo htmlspecialchars($row['assigned_director']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                            <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                            <td><?php echo htmlspecialchars($row['last_updated_by']); ?></td>
                            <td class="action-links">
                                <a
                                    href="editproject.php?service_id=<?php echo $row['service_id']; ?>&director_id=<?php echo $_GET['director_id']; ?>">Edit</a>
                                <a href="deleteproject.php?service_id=<?php echo $row['service_id']; ?>&director_id=<?php echo $_GET['director_id']; ?>"
                                    class="delete-link">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="empty-state">No services added for this director yet.</p>
        <?php endif; ?>

    </div>

</body>

</html>