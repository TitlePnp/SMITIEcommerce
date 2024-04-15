<?php
require_once "../../Components/ConnectDB.php";

function getGoogleUserInfo($googleID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT 
        CUSTOMER_ACCOUNT.CusID, 
        CUSTOMER_ACCOUNT.UserName, 
        CUSTOMER_ACCOUNT.Role, 
        CUSTOMER_ACCOUNT.Email,
        CUSTOMER.CusFName, 
        CUSTOMER.CusLName, 
        CUSTOMER.Sex, 
        CUSTOMER.Tel, 
        CUSTOMER.Address, 
        CUSTOMER.Province,
        CUSTOMER.Postcode
    FROM 
        CUSTOMER_ACCOUNT 
    INNER JOIN 
        CUSTOMER 
    ON 
        CUSTOMER_ACCOUNT.CusID = CUSTOMER.CusID 
    WHERE 
        CUSTOMER_ACCOUNT.GoogleID = ?
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
    // Get user info from CUSTOMER
    $stmt = $connectDB->prepare("
    SELECT 
        CUSTOMER_ACCOUNT.CusID, 
        CUSTOMER_ACCOUNT.UserName, 
        CUSTOMER_ACCOUNT.Role,
        CUSTOMER_ACCOUNT.Email, 
        CUSTOMER.CusFName, 
        CUSTOMER.CusLName, 
        CUSTOMER.Sex, 
        CUSTOMER.Tel, 
        CUSTOMER.Address,
        CUSTOMER.Province,
        CUSTOMER.Postcode
    FROM 
        CUSTOMER_ACCOUNT 
    INNER JOIN 
        CUSTOMER 
    ON 
        CUSTOMER_ACCOUNT.CusID = CUSTOMER.CusID 
    WHERE 
        CUSTOMER_ACCOUNT.CusID = ?
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
    $stmt = $connectDB->prepare("SELECT CusID FROM CUSTOMER_ACCOUNT WHERE GoogleID = ?");
    $stmt->bind_param("s", $googleID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['CusID'];
}

function getAllAddress($CusID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT c.Address, c.Province, c.Postcode, p.PayerAddress, p.PayerProvince, p.PayerPostcode FROM CUSTOMER c JOIN Payer p ON c.CusID = ?");
    $stmt->bind_param("s", $CusID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
