<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../../Components/ConnectDB.php';
    if ($_POST['action'] == 'Add') {
        try {
            $Email = $_POST['Email'];
            $UserName = $_POST['UserName'];
            $Role = $_POST['Role'];
            $stmt = $connectDB->prepare("UPDATE CUSTOMER_ACCOUNT SET Role = ? WHERE Email = ? AND UserName = ?");
            $stmt->bind_param("sss", $Role, $Email, $UserName);
            $stmt->execute();
            $stmt->close();
            $connectDB->close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    else if ($_POST['action'] == 'Delete') {
        try {
            $Email = $_POST['Email'];
            $UserName = $_POST['UserName'];
            $stmt = $connectDB->prepare("UPDATE CUSTOMER_ACCOUNT SET Role = 'User'  WHERE Email = ? AND UserName = ?");
            $stmt->bind_param("ss", $Email, $UserName);
            $stmt->execute();
            $stmt->close();
            $connectDB->close();
        } catch (Exception $e) {
            echo $e->getMessage();
        }

    }

}
