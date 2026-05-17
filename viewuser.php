<?php 
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		body {
			font-family: "Arial";
		}
		input {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
		}
		table, th, td {
			border:1px solid black;
		}
	</style>
</head>
<body>
	<?php $getUserByID = getUserByID($pdo, $_GET['user_id']); ?>
	<h1>Username: <?php echo htmlspecialchars($getUserByID['username']); ?></h1>
	<h1>First Name: <?php echo htmlspecialchars($getUserByID['first_name']); ?></h1>
	<h1>Last Name: <?php echo htmlspecialchars($getUserByID['last_name']); ?></h1>
	<h1>Address: <?php echo htmlspecialchars($getUserByID['address']); ?></h1>
	<h1>Age: <?php echo htmlspecialchars($getUserByID['age']); ?></h1>
	<h1>Date Joined: <?php echo htmlspecialchars($getUserByID['date_added']); ?></h1>
</body>
</html>