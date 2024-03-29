<?php
require_once "../../Components/ConnectDB.php";

function getGoogleUserInfo($googleID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT 
        customer_account.CusID, 
        customer_account.UserName, 
        customer_account.Role, 
        customer_account.Email,
        customer.CusFName, 
        customer.CusLName, 
        customer.Sex, 
        customer.Tel, 
        customer.Address 
    FROM 
        customer_account 
    INNER JOIN 
        customer 
    ON 
        customer_account.CusID = customer.CusID 
    WHERE 
        customer_account.GoogleID = ?
");
    $stmt->bind_param("s", $googleID);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return null;
    }
}

function getUserInfoFromCusID($CusID)
{
    global $connectDB;
    // Get user info from customer
    $stmt = $connectDB->prepare("
    SELECT 
        customer_account.CusID, 
        customer_account.UserName, 
        customer_account.Role,
        customer_account.Email, 
        customer.CusFName, 
        customer.CusLName, 
        customer.Sex, 
        customer.Tel, 
        customer.Address 
    FROM 
        customer_account 
    INNER JOIN 
        customer 
    ON 
        customer_account.CusID = customer.CusID 
    WHERE 
        customer_account.CusID = ?
");
    $stmt->bind_param("s", $CusID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row;
}

function getCusID($googleID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT CusID FROM customer_account WHERE GoogleID = ?");
    $stmt->bind_param("s", $googleID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['CusID'];
}
