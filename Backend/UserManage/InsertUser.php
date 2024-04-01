<?php
    require "../../Components/ConnectDB.php";
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $username = $_POST['username'];
    $password = $_POST['userpassword'];

    if (isset($_POST['useremail'])) {
        $email = $_POST['useremail'];
    } else {
        $email = "";
    }

    $stmt = $connectDB->prepare("INSERT INTO customer(CusFName, CusLName, Sex, Tel, Address) VALUES 
    ('', '', '', '', '')");
    $stmt->execute();

    $sql = "SELECT CusID FROM customer ORDER BY CusID DESC LIMIT 1";
    $result = $connectDB->query($sql);
    $row = $result->fetch_assoc();
    $cusID = $row['CusID'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    $stmt = $connectDB->prepare("INSERT INTO customer_account(UserName, Email, Password, GoogleId, Role, CusID)
    VALUES (?, ?, ?, '', 'User', ?)");

    $stmt->bind_param("ssss", $username, $email, $hashedPassword, $cusID);
    $stmt->execute();

    header('Location: ../../Frontend/SignIn_Page/SignIn.php')
?>