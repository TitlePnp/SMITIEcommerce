<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../../Components/ConnectDB.php';
    try {
        $Email = $_POST['Email'];
        $UserName = $_POST['UserName'];
        $Role = $_POST['Role'];
        $stmt = $connectDB->prepare("UPDATE Customer_account SET Role = ? WHERE Email = ? AND UserName = ?");
        $stmt->bind_param("sss", $Role, $Email, $UserName);
        $stmt->execute();
        $stmt->close();
        $connectDB->close();
        echo "Success";
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
