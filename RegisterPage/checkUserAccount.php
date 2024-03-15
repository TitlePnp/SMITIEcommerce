<?php
require "../Components/connectDB.php";
$username = $_POST['username'];

$stmt = $connectDB->prepare("SELECT UserName FROM `customer_account` WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

// Get the result of the SQL query
$result = $stmt->get_result();

// Check if the username exists
if ($result->num_rows > 0) {
    echo 'exists';
} else {
    echo 'not exists';
}
