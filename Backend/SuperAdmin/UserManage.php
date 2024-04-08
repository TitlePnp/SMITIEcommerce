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
