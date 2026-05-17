<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$logs = getAllLogs($pdo);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funeral Home – Activity Logs</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <!-- top navigation bar -->
    <nav class="navbar">
        <span class="nav-brand">Baban's Everlasting Funeral Home</span>
        <div class="nav-right">
            <span>Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></span>
            <a href="index.php">Directors</a>
            <a href="core/handleForms.php?logoutAUser=1"
                onclick="return confirm('Are you sure you want to logout?');">Logout</a>
        </div>
    </nav>

    <div class="page-wrapper">

        <h1>Activity Logs</h1>

        <?php if (count($logs) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Performed By</th>
                        <th>Action</th>
                        <th>Table Affected</th>
                        <th>Description</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($log['log_id']); ?></td>
                            <!-- the username of who performed this action -->
                            <td><strong><?php echo htmlspecialchars($log['performed_by']); ?></strong></td>
                            <td>
                                <?php
                                // pick a badge color based on the type of action
                                $action = strtolower($log['action']);
                                $badgeClass = 'badge-read';
                                if ($action === 'create')
                                    $badgeClass = 'badge-create';
                                elseif ($action === 'update')
                                    $badgeClass = 'badge-update';
                                elseif ($action === 'delete')
                                    $badgeClass = 'badge-delete';
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($log['action']); ?>
                                </span>
                            </td>
                            <!-- which db table was affected -->
                            <td><code
                                    style="font-size:0.82em; color:#555;"><?php echo htmlspecialchars($log['table_affected']); ?></code>
                            </td>
                            <td><?php echo htmlspecialchars($log['record_description']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($log['date_performed']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <!-- shown when no logs exist yet -->
            <div class="empty-state" style="padding:60px 0;">
                <p style="font-size:1.1em;">📋 No activity logs recorded yet.</p>
                <p style="margin-top:6px;">Logs will appear here as users perform actions in the system.</p>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>