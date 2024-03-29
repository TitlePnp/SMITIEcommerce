<?php

function getInvoiceInfo($InvoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE InvoiceID = ?");
    $stmt->bind_param("s", $InvoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getOrderList($InvoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("
    SELECT ol.InvoiceID, ol.NumID, ol.ProID, ol.Qty, p.ProName, p.PricePerUnit
    FROM Invoice_List AS ol
    JOIN Product AS p ON ol.ProID = p.ProID
    WHERE ol.InvoiceID = ?
");
    $stmt->bind_param("s", $InvoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getCountInvoiceList($InvoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(ProID) FROM invoice_list WHERE InvoiceID = ?");
    $stmt->bind_param("s", $InvoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getReceiverInfo($ReceiverID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM receiver WHERE RecvID = ?");
    $stmt->bind_param("s", $ReceiverID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}
