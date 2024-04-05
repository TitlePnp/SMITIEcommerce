<?php
require_once "../../Components/ConnectDB.php";
$CusID = $_POST['cusID'];
$Username = $_POST['username'];
$Firstname = $_POST['firstname'];
$Lastname = $_POST['lastname'];
$Sex = $_POST['sex'];
$Tel = $_POST['tel'];

$stmt = $connectDB->prepare("UPDATE customer SET CusFName = ?, CusLName = ?, Sex = ?, Tel = ?, WHERE CusID = ?");
$stmt->bind_param("ssssi", $Username, $Firstname, $Lastname, $Sex, $Tel, $CusID);
$stmt->execute();
$stmt->close();
$connectDB->close();
?>

