<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__, 'dbconfig.env');
$dotenv->load();

$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASS'];
$dbname = $_ENV['DB_NAME'];

// Create connection
$connectDB = new mysqli($servername, $username, $password, $dbname);

if ($connectDB->connect_error) {
  die("Connection failed: " . $connectDB->connect_error);
} else {
  // echo "Connected successfully";
}

/* Connect to the database แบบ Procedural */
//   $connectDB = mysqli_connect("localhost", "root", "", "myStore");

/* Specify connection */
// $servername = "localhost:3306";
// $username = "root";
// $password = "rootroot";
// $dbname = "user_jwt";

// /* Create connection แบบ OOP */
// $conn = new mysqli($servername, $username, $password, $dbname);
// /* Check connection */
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// } else {
//   echo "Connected successfully";
// }
