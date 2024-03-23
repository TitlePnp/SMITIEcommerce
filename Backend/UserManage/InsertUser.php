<?php
    require "../Components/ConnectDB.php";

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    } else {
        $email = "";
    }

    $stmt = $connectDB->prepare("INSERT INTO customer(CusID, Email, CusFName, CusLName, Sex, Tel, Address) VALUES 
    ('[value-1]','{$email}' ,'','','','','')");
    $stmt->execute();

    $sql = "SELECT CusID FROM customer ORDER BY CusID DESC LIMIT 1";
    $result = $connectDB->query($sql);
    $row = $result->fetch_assoc();
    $cusID = $row['CusID'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
    $stmt = $connectDB->prepare("INSERT INTO customer_account(UserName, Password, CusID, Role) 
    VALUES (?, ?, ?, 'User')");

    $stmt->bind_param("sss", $username, $hashedPassword, $cusID);
    $stmt->execute();

    header('Location: ../LoginPage/LoginCustomer.html')
?>