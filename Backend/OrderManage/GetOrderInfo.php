<?php
function getInvoiceInfo($InvoiceID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM INVOICE_ORDER WHERE InvoiceID = ?");
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
    FROM INVOICE_LIST AS ol
    JOIN PRODUCT AS p ON ol.ProID = p.ProID
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
    $stmt = $connectDB->prepare("SELECT COUNT(ProID) FROM INVOICE_LIST WHERE InvoiceID = ?");
    $stmt->bind_param("s", $InvoiceID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getReceiverInfo($ReceiverID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM RECEIVER WHERE RecvID = ?");
    $stmt->bind_param("s", $ReceiverID);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getTodayOrder()
{
    global $connectDB;
    $startDateTime = date('Y-m-d') . ' 00:00:00';
    $endDateTime = date('Y-m-d') . ' 23:59:59';
    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM INVOICE_ORDER WHERE StartDate BETWEEN ? AND ?");
    $stmt->bind_param("ss", $startDateTime, $endDateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getAllOrder()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM INVOICE_ORDER");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getPendingStatus()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(RecID) FROM RECEIPT WHERE Status = 'Pending'");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getPaidAndCOD()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(RecID) FROM RECEIPT WHERE Status = 'Paid' OR Status = 'COD'");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getWeekOrders()
{
    global $connectDB;
    $orders = [];

    // Loop over each day of the week
    for ($i = 0; $i < 7; $i++) {
        $startDateTime = date('Y-m-d', strtotime('-' . $i . ' days last Sunday')) . ' 00:00:00';
        $endDateTime = date('Y-m-d', strtotime('-' . $i . ' days last Sunday')) . ' 23:59:59';
        $stmt = $connectDB->prepare("SELECT Sum(TotalPrice + Vat) FROM INVOICE_ORDER WHERE StartDate >= ? AND StartDate < ? AND Status != 'Cancel';");
        $stmt->bind_param("ss", $startDateTime, $endDateTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // Add the result to the orders array
        $orders[date('l', strtotime($startDateTime))] = $result->fetch_assoc()["Sum(TotalPrice + Vat)"];
    }

    return $orders;
}
