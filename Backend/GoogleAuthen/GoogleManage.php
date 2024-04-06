<?php
require_once "../UserManage/SessionManage.php";
clearSession();

session_start();
require '../../Components/ConnectDB.php';
require 'Google_OAuth.php';
require '../../Backend/Profile/GetInfo.php';



if (isset($_GET['code'])) {

    if (isset($token['error'])) {
        // Redirect to login page
        header('Location: http://localhost/SmitiShop/Frontend/SignIn_Page/SignIn.php');
        exit;
    }

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    $google_oauth = new Google_Service_Oauth2($client);

    $google_oauth_account_info = $google_oauth->userinfo->get();
    $email = $google_oauth_account_info->email;
    $name = $google_oauth_account_info->name;
    $userId = $google_oauth_account_info->id;

    $stmt = $connectDB->prepare("SELECT * FROM `customer_account` WHERE `GoogleId` = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['Role'] == 'SuperAdmin' || $row['Role'] == 'UserAdmin') {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['tokenGoogle'] = $userId;
            require_once "../Log/LogManage.php";
            insertLog("Login with Google" . $row['Role'] . "", date("Y-m-d H:i:s"));
            header('Location: http://localhost/SmitiShop/Frontend/Admin/AdminPage.php');
            exit();
        } else if ($row['Role'] == 'User') {
            $_SESSION['email'] = $email;
            $_SESSION['name'] = $name;
            $_SESSION['tokenGoogle'] = $userId;
            $CusID = $row['CusID'];
            $noData = getInfo($CusID);
            while ($row = $noData->fetch_assoc()) {
                if ($row['CusFName'] == null || $row['CusLName'] == null) {
                    require_once "../Log/LogManage.php";
                    insertLog("Login with Google", date("Y-m-d H:i:s"));
                    header('Location: http://localhost/SmitiShop/Frontend/Profile/Information.php');
                    exit();
                } else {
                    require_once "../Log/LogManage.php";
                    insertLog("Login with Google", date("Y-m-d H:i:s"));
                    header('Location: http://localhost/SmitiShop/Frontend/MainPage/Home.php');
                    exit();
                }
            }
        }

        // header('Location: http://localhost/SmitiShop/Frontend/MainPage/Home.php');
        // print_r($_SESSION);
    } else {
        $stmt = $connectDB->prepare("INSERT INTO customer(CusID, CusFName, CusLName, Sex, Tel, Address) VALUES 
    ('',?,'','','','')");
        $stmt->bind_param("s", $name);
        $stmt->execute();

        $sql = "SELECT CusID FROM customer ORDER BY CusID DESC LIMIT 1";
        $result = $connectDB->query($sql);
        $row = $result->fetch_assoc();
        $cusID = $row['CusID'];

        $stmt = $connectDB->prepare("INSERT INTO customer_account(UserName, Email, Password, GoogleId, Role, CusID)
    VALUES (?, ?, '', ?, 'User', ?)");

        $stmt->bind_param("ssss", $name, $email, $userId, $cusID);
        $stmt->execute();

        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        $_SESSION['tokenGoogle'] = $userId;
        require_once "../Log/LogManage.php";
        insertLog("Login with Google", date("Y-m-d H:i:s"));
        header('Location: http://localhost/SmitiShop/Frontend/MainPage/Home.php');
        // print_r($_SESSION);

    }
}
