<?php
require "../../Components/ConnectDB.php";

function sumProductOnOrder($proID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(Qty) FROM invoice_list il JOIN invoice_order iv ON il.InvoiceID = iv.InvoiceID WHERE il.ProID = ? AND iv.Status = 'Ordered'");
    $stmt->bind_param("i", $proID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result['SUM(Qty)'];
}

function getQtyWarningProduct()
{
    //get Qty of each product orderby less qty
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT ProID, ProName, StockQty FROM product WHERE StockQty < 5  ORDER BY StockQty ASC LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getQtyPaidStatusCompareStockQty()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.StockQty, r.InvoiceID, SUM(il.Qty) as Qty FROM product p JOIN invoice_list il ON p.ProID = il.ProID JOIN receipt r ON r.InvoiceID = il.InvoiceID WHERE r.Status = 'COD' OR r.Status = 'Paid' GROUP BY p.ProID ORDER BY r.InvoiceID;");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getTodayOrder()
{
    global $connectDB;
    $startDateTime = date('Y-m-d') . ' 00:00:00';
    $endDateTime = date('Y-m-d') . ' 23:59:59';
    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM invoice_order WHERE StartDate BETWEEN ? AND ?");
    $stmt->bind_param("ss", $startDateTime, $endDateTime);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getAllOrder()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(InvoiceID) FROM invoice_order");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getPendingStatus()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(RecID) FROM receipt WHERE Status = 'Pending'");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc();
}

function getPaidAndCOD()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(RecID) FROM receipt WHERE Status = 'Paid' OR Status = 'COD'");
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
        $stmt = $connectDB->prepare("SELECT Sum(TotalPrice + Vat) FROM invoice_order WHERE StartDate >= ? AND StartDate < ? AND Status != 'Cancel';");
        $stmt->bind_param("ss", $startDateTime, $endDateTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();

        // Add the result to the orders array
        $orders[date('l', strtotime($startDateTime))] = $result->fetch_assoc()["Sum(TotalPrice + Vat)"];
    }

    return $orders;
}


function getOverallStatus()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Status, COUNT(InvoiceID) as count FROM invoice_order GROUP BY Status");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getSumAllProductSell() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(TotalPrice + Vat) AS Income FROM receipt WHERE Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getSumQtyAllProductSell() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(Qty) AS Qty FROM receipt_list JOIN receipt ON receipt_list.RecID = receipt.RecID WHERE receipt.Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllProduct() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(ProID) AS AllProduct FROM product");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllProductType() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT TypeID, COUNT(*) as count FROM Product GROUP BY TypeID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
