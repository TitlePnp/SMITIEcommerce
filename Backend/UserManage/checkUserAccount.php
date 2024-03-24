<?php
require "../../Components/ConnectDB.php";

function checkUsername($username) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT UserName FROM `customer_account` WHERE UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'username exists';
    } else {
        echo 'username not exists';
    }
}

function checkEmail($email) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Email FROM `customer_account` WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'email exists';
    } else {
        echo 'email not exists';
    }
}

$action = $_POST['action'];

switch ($action) {
    case 'checkUsername':
        if (!isset($_POST['username'])) {
            echo 'No username provided';
            exit;
        }
        $username = $_POST['username'];
        checkUsername($username);
        break;
    case 'checkEmail':
        if (!isset($_POST['email'])) {
            echo 'No email provided';
            exit;
        }
        $email = $_POST['email'];
        checkEmail($email);
        break;
}