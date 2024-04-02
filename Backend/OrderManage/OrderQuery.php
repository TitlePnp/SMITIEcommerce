<?php

require_once "../../Components/ConnectDB.php";

function getOrderDetail($cusID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE CusID = ? ORDER BY FIELD(Status, 'Ordered') DESC, CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED)");
    $stmt->bind_param("s", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getOrderListDetail($orderID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT pd.ProName, il.Qty FROM invoice_list il JOIN product pd ON il.ProID = pd.ProID WHERE il.InvoiceID = ?");
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all results as an associative array
    return $result;
}

function countUserOrder($cusID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM invoice_order WHERE InvoiceID = ?");
    $stmt->bind_param("s", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['COUNT(InvoiceID)'];
}

function showOrderSplitPage($CusID, $offset, $limit)
{
    global $connectDB;
    $stmt = $connectDB->prepare(
        "SELECT * FROM invoice_order WHERE CusID = ? ORDER BY FIELD(Status, 'Ordered') DESC, CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) LIMIT ?, ?");
    $stmt->bind_param("ssi", $CusID, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
