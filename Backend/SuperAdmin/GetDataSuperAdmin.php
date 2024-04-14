<?php
require "../../Components/ConnectDB.php";

function sumPRODUCTOnOrder($proID)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(Qty) FROM INVOICE_LIST il JOIN INVOICE_ORDER iv ON il.InvoiceID = iv.InvoiceID WHERE il.ProID = ? AND iv.Status = 'Ordered'");
    $stmt->bind_param("i", $proID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc();
    $stmt->close();
    return $result['SUM(Qty)'];
}

function getQtyWarningPRODUCT()
{
    //get Qty of each PRODUCT orderby less qty
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT ProID, ProName, StockQty FROM PRODUCT WHERE StockQty < 5  ORDER BY StockQty ASC LIMIT 10");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getQtyPaidStatusCompareStockQty()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.StockQty, r.InvoiceID, SUM(il.Qty) as Qty FROM PRODUCT p JOIN INVOICE_LIST il ON p.ProID = il.ProID JOIN RECEIPT r ON r.InvoiceID = il.InvoiceID WHERE r.Status = 'COD' OR r.Status = 'Paid' GROUP BY p.ProID ORDER BY r.InvoiceID;");
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


function getOverallStatus()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Status, COUNT(InvoiceID) as count FROM INVOICE_ORDER GROUP BY Status");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getSumAllPRODUCTSell()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(TotalPrice + Vat) AS Income FROM RECEIPT WHERE Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getSumQtyAllPRODUCTSell()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(Qty) AS Qty FROM RECEIPT_LIST JOIN RECEIPT ON RECEIPT_LIST.RecID = RECEIPT.RecID WHERE RECEIPT.Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllPRODUCTType()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT TypeID, COUNT(*) as count FROM PRODUCT GROUP BY TypeID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllPRODUCTThaiType()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT TypeID, TypeName, ThaiType FROM PRODUCT_TYPE");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getReportByDay($startDate, $endDate)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT InvoiceID, StartDate, Channel, TotalPrice, Vat, Status FROM INVOICE_ORDER WHERE StartDate >= ? AND EndDate <= ? ORDER BY StartDate DESC");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}


function getAllUserReport()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT c.CusID, ca.UserName, ca.Email, c.CusFName, c.CusLName, c.Sex, c.Tel From CUSTOMER c JOIN CUSTOMER_ACCOUNT ca ON c.CusID = ca.CusID WHERE ca.Role = 'User'");
    $result = $stmt->get_result();
    return $result;
}

function getSellReportFilterByDate($StartDate, $EndDate)
{
    global $connectDB;
    if ($StartDate == $EndDate) {
        $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum(p.PricePerUnit - p.CostPerUnit) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost 
        FROM RECEIPT r 
        JOIN RECEIPT_LIST rl ON r.RecID = rl.RecID 
        JOIN PRODUCT p ON rl.ProID = p.ProID 
        WHERE DATE(r.PayTime) = ? 
        GROUP BY r.RecID");
        $stmt->bind_param("s", $StartDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    } else {
        $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum(p.PricePerUnit - p.CostPerUnit) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost FROM RECEIPT r JOIN RECEIPT_LIST rl ON r.RecID = rl.RecID JOIN PRODUCT p ON rl.ProID = p.ProID WHERE r.PayTime >= ? AND r.PayTime <= ? GROUP BY r.RecID");
        $stmt->bind_param("ss", $StartDate, $EndDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}

function getBestSellPRODUCT()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, SUM(rl.Qty) as Qty FROM RECEIPT_LIST rl JOIN PRODUCT p ON rl.ProID = p.ProID GROUP BY rl.ProID ORDER BY Qty DESC LIMIT 5");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllUser()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Role, COUNT(CusID) as Count FROM CUSTOMER_ACCOUNT WHERE Role IN ('Admin', 'User') GROUP BY Role");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
