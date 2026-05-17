<?php
// starts a session and connects to the database using pdo

session_start();

// database connection settings
$host     = "localhost";
$user     = "root";
$password = "";
$dbname   = "funeral_home_db";
$dsn      = "mysql:host={$host};dbname={$dbname}";

// create the pdo connection
$pdo = new PDO($dsn, $user, $password);

// set the timezone to ph time
$pdo->exec("SET time_zone = '+08:00';");
?>