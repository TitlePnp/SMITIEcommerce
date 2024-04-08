<?php
require '../../Components/ConnectDB.php';

function getAllAdmin()
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT * 
    FROM Customer_account 
    WHERE Role = 'Admin' OR Role = 'SuperAdmin' 
    ORDER BY CASE Role 
        WHEN 'SuperAdmin' THEN 1
        WHEN 'Admin' THEN 2
        ELSE 3
    END
");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllUser()
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT ca.CusID, ca.Email, ca.UserName, c.CusFName, c.CusLName, c.Tel 
    FROM Customer_account ca JOIN Customer c ON ca.CusID = c.CusID 
    WHERE Role = 'User'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function searchUser($SearchVal)
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT ca.CusID, ca.Email, ca.UserName, c.CusFName, c.CusLName, c.Tel
    FROM Customer_account ca JOIN Customer c ON ca.CusID = c.CusID
    WHERE ca.Email LIKE ? OR ca.UserName LIKE ? OR ca.CusID LIKE ? OR c.CusFName LIKE ? OR c.CusLName LIKE ?
    ");
    $stmt->bind_param("sssss", $SearchVal, $SearchVal, $SearchVal, $SearchVal, $SearchVal);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllLog()
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT * 
    FROM Access_log 
    ORDER BY Period DESC
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function searchLog($SearchVal) {
    //Search Log from CusID or IPAddr or Action or Period
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT *
    FROM Access_log
    WHERE CusID LIKE ? OR IPAddr LIKE ? OR Action LIKE ? OR Period LIKE ?
    ");
    $stmt->bind_param("ssss", $SearchVal, $SearchVal, $SearchVal, $SearchVal);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
    
}
