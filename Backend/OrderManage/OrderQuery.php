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

    $stmt = $connectDB->prepare("SELECT pd.ProName, il.Qty, r.Status FROM invoice_list il JOIN product pd ON il.ProID = pd.ProID JOIN receipt r ON r.InvoiceID = il.InvoiceID WHERE il.InvoiceID = ?");
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

function getAddressAndPriceOrder($orderID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.PayerAddress, p.PayerProvince, p.PayerPostcode, r.RecvAddress, r.RecvProvince, r.RecvPostcode, iv.totalPrice, iv.channel, iv.vat FROM invoice_order iv JOIN payer p ON iv.PayerID = p.PayerID JOIN receiver r ON iv.RecvID = r.RecvID WHERE iv.InvoiceID = ?");
    $stmt->bind_param("s", $orderID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getReceiptStatus($invoiceID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Status FROM receipt WHERE InvoiceID = ?");
    $stmt->bind_param("s", $invoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;

}