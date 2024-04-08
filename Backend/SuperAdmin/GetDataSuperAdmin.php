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

function getSumAllProductSell()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(TotalPrice + Vat) AS Income FROM receipt WHERE Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getSumQtyAllProductSell()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT SUM(Qty) AS Qty FROM receipt_list JOIN receipt ON receipt_list.RecID = receipt.RecID WHERE receipt.Status = 'Completed'");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

function getAllProductType()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT TypeID, COUNT(*) as count FROM Product GROUP BY TypeID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllProductThaiType()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT TypeID, TypeName, ThaiType FROM Product_Type");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getReportByDay($startDate, $endDate)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT InvoiceID, StartDate, Channel, TotalPrice, Vat, Status FROM invoice_order WHERE StartDate >= ? AND EndDate <= ? ORDER BY StartDate DESC");
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}


function getAllUserReport()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT c.CusID, ca.UserName, ca.Email, c.CusFName, c.CusLName, c.Sex, c.Tel From customer c JOIN customer_account ca ON c.CusID = ca.CusID WHERE ca.Role = 'User'");
    $result = $stmt->get_result();
    return $result;
}

function getSellReport()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum((p.PricePerUnit - p.CostPerUnit) * rl.Qty) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost FROM receipt r JOIN receipt_list rl ON r.RecID = rl.RecID JOIN product p ON rl.ProID = p.ProID GROUP BY r.RecID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getSellReportFilterByDate($StartDate, $EndDate)
{
    global $connectDB;
    if ($StartDate == $EndDate) {
        $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum(p.PricePerUnit - p.CostPerUnit) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost 
        FROM receipt r 
        JOIN receipt_list rl ON r.RecID = rl.RecID 
        JOIN product p ON rl.ProID = p.ProID 
        WHERE DATE(r.PayTime) = ? 
        GROUP BY r.RecID");
        $stmt->bind_param("s", $StartDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    } else {
        $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum(p.PricePerUnit - p.CostPerUnit) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost FROM receipt r JOIN receipt_list rl ON r.RecID = rl.RecID JOIN product p ON rl.ProID = p.ProID WHERE r.PayTime >= ? AND r.PayTime <= ? GROUP BY r.RecID");
        $stmt->bind_param("ss", $StartDate, $EndDate);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result;
    }
}

function getBestSellProduct()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, SUM(rl.Qty) as Qty FROM receipt_list rl JOIN product p ON rl.ProID = p.ProID GROUP BY rl.ProID ORDER BY Qty DESC LIMIT 5");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getAllUser()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT Role, COUNT(CusID) as Count FROM customer_account WHERE Role IN ('Admin', 'User') GROUP BY Role");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
