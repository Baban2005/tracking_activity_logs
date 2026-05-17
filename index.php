<?php require_once 'core/dbConfig.php'; ?>
<?php require_once 'core/models.php'; ?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Search logic
$searchKeyword = '';
$searchDirectors = [];
$searchServices = [];
$didSearch = false;

if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $searchKeyword = trim($_GET['search']);
    $didSearch = true;
    // search both parent (directors) and child (services) tables
    $searchDirectors = searchDirectors($pdo, $searchKeyword);
    $searchServices = searchServices($pdo, $searchKeyword);
}

// fetch all directors for the default listing
$getAllDirectors = getAllDirectors($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funeral Home – Directors</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- top navigation bar with logged-in user info and links -->
    <nav class="navbar">
        <span class="nav-brand">Baban's Everlasting Funeral Home</span>
        <div class="nav-right">
            <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="activity_logs.php">Activity Logs</a>
            <a href="core/handleForms.php?logoutAUser=1"
                onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <div class="page-wrapper">

        <h1>Funeral Directors</h1>

        <!-- Add Director Form -->
        <div class="card">
            <h2>Add New Director</h2>
            <form action="core/handleForms.php" method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
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
                        <label for="dateOfBirth">Date of Birth</label>
                        <input type="date" id="dateOfBirth" name="dateOfBirth" required>
                    </div>
                    <div class="form-group">
                        <label for="specialization">Specialization / Role</label>
                        <input type="text" id="specialization" name="specialization" required>
                    </div>
                    <div class="form-group" style="flex:0; align-self:flex-end;">
                        <input type="submit" name="insertDirectorBtn" value="Add Director">
                    </div>
                </div>
            </form>
        </div>

        <!-- Search -->
        <div class="search-bar">
            <form action="index.php" method="GET" style="display:flex; gap:10px; flex:1;">
                <input type="search" name="search" placeholder="Search directors or services…"
                    value="<?php echo htmlspecialchars($searchKeyword); ?>" style="flex:1;">
                <input type="submit" value="Search">
                <?php if ($didSearch): ?>
                    <!-- clear button resets the search -->
                    <a href="index.php" class="btn btn-secondary" style="padding:8px 14px;">Clear</a>
                <?php endif; ?>
            </form>
        </div>

        <?php if ($didSearch): ?>

            <!-- show search results when a keyword was submitted -->
            <h2 style="margin-bottom:8px;">Search results for: "<em><?php echo htmlspecialchars($searchKeyword); ?></em>"
            </h2>

            <!-- directors search results table -->
            <h2 class="mt-10">Directors</h2>
            <?php if (count($searchDirectors) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Specialization</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchDirectors as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['director_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                                <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                                <td class="action-links">
                                    <a href="viewprojects.php?director_id=<?php echo $row['director_id']; ?>">Services</a>
                                    <a href="editwebdev.php?director_id=<?php echo $row['director_id']; ?>">Edit</a>
                                    <a href="deletewebdev.php?director_id=<?php echo $row['director_id']; ?>"
                                        class="delete-link">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty-state">No directors found matching "<em><?php echo htmlspecialchars($searchKeyword); ?></em>".
                </p>
            <?php endif; ?>

            <!-- services search results table (child entity) -->
            <h2 class="mt-20">Services</h2>
            <?php if (count($searchServices) > 0): ?>
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
                        <?php foreach ($searchServices as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['service_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['package_type']); ?></td>
                                <td><?php echo htmlspecialchars($row['assigned_director']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                                <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_updated_by']); ?></td>
                                <td class="action-links">
                                    <a
                                        href="editproject.php?service_id=<?php echo $row['service_id']; ?>&director_id=<?php echo $row['director_id']; ?>">Edit</a>
                                    <a href="deleteproject.php?service_id=<?php echo $row['service_id']; ?>&director_id=<?php echo $row['director_id']; ?>"
                                        class="delete-link">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty-state">No services found matching "<em><?php echo htmlspecialchars($searchKeyword); ?></em>".
                </p>
            <?php endif; ?>

        <?php else: ?>

            <!-- default view: list all directors in a table -->
            <h2>All Directors</h2>
            <?php if (count($getAllDirectors) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Date of Birth</th>
                            <th>Specialization</th>
                            <th>Date Added</th>
                            <th>Added By</th>
                            <th>Last Updated By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($getAllDirectors as $row): ?>
                            <tr>
                                <!-- htmlspecialchars is used on all output to prevent xss -->
                                <td><?php echo htmlspecialchars($row['director_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                                <td><?php echo htmlspecialchars($row['specialization']); ?></td>
                                <td><?php echo htmlspecialchars($row['date_added']); ?></td>
                                <td><?php echo htmlspecialchars($row['added_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_updated_by']); ?></td>
                                <td class="action-links">
                                    <a href="viewprojects.php?director_id=<?php echo $row['director_id']; ?>">Services</a>
                                    <a href="editwebdev.php?director_id=<?php echo $row['director_id']; ?>">Edit</a>
                                    <a href="deletewebdev.php?director_id=<?php echo $row['director_id']; ?>"
                                        class="delete-link">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="empty-state">No directors added yet. Use the form above to get started.</p>
            <?php endif; ?>

        <?php endif; ?>

    </div><!-- /page-wrapper -->

</body>

</html>