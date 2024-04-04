<?php
session_start();
require_once "../Log/LogManage.php";
date_default_timezone_set('Asia/Bangkok');
if (isset($_SESSION['tokenJWT'])) {
    insertLog("JWT Logout", date("Y-m-d H:i:s"));

} else if (isset($_SESSION['tokenGoogle'])) {
    insertLog("Google Logout", date("Y-m-d H:i:s"));
} else {
    insertLog("Loginout Role? ", date("Y-m-d H:i:s"));
}

session_unset();
session_destroy();
header("Location: ../../Frontend/SignIn_Page/SignIn.php");
?>