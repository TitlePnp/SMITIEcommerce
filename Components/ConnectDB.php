<?php
  $servername = "localhost:3306";
  $username = "root";
  $password = "";
  $dbname = "myStore";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
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
?>
