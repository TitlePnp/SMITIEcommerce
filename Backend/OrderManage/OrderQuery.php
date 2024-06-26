<?php

require_once "../../Components/ConnectDB.php";

function getOrderDetail($cusID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT * FROM INVOICE_ORDER WHERE CusID = ? ORDER BY FIELD(Status, 'Ordered') DESC, CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED)");
    $stmt->bind_param("s", $cusID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getOrderListDetail($orderID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT pd.ProName, il.Qty FROM INVOICE_LIST il JOIN PRODUCT pd ON il.ProID = pd.ProID WHERE il.InvoiceID = ?");
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch all results as an associative array
    return $result;
}

function countUserOrder($cusID)
{
    global $connectDB;

    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM INVOICE_ORDER WHERE InvoiceID = ?");
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
        "SELECT * FROM INVOICE_ORDER WHERE CusID = ? ORDER BY FIELD(Status, 'Ordered') DESC, CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) LIMIT ?, ?"
    );
    $stmt->bind_param("ssi", $CusID, $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getAddressAndPriceOrder($orderID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.PayerAddress, p.PayerProvince, p.PayerPostcode, r.RecvAddress, r.RecvProvince, r.RecvPostcode, iv.totalPrice, iv.channel, iv.vat FROM INVOICE_ORDER iv JOIN PAYER p ON iv.PayerID = p.PayerID JOIN RECEIVER r ON iv.RecvID = r.RecvID WHERE iv.InvoiceID = ?");
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getReceiptStatus($invoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Status FROM RECEIPT WHERE InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    if ($result === null || !isset($result['Status']) || $result['Status'] === null) {
        return 'No Receipt';
    } else {
        return $result['Status'];
    }
}

function getReceiptDetail($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM RECEIPT WHERE InvoiceID = ? ");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row;
}


function getOverallStatus() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Status, COUNT(InvoiceID) as count FROM INVOICE_ORDER GROUP BY Status");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}