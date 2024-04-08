<?php
function getReportInvoiceFromTime($StartTime, $EndTime, $Channel, $Status)
{
    global $connectDB;
    if ($StartTime == $EndTime) {
        $StartTime = $StartTime . ' 00:00:00';
        $EndTime = $EndTime . ' 23:59:59';
    } else {
        $StartTime = $StartTime . ' 00:00:00';
        $EndTime = $EndTime . ' 23:59:59';
    }

    if (is_array($Status) && count($Status) > 0 && $Channel == "None") {

        $statusParams = implode(" OR ", array_fill(0, count($Status), "Status = ?"));
        $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE StartDate BETWEEN ? AND ? AND ($statusParams) ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) ASC");
        $bindParams = array_merge([$StartTime, $EndTime], $Status);
        $stmt->bind_param(str_repeat("s", count($bindParams)), ...$bindParams);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else if ($Channel != "None" && is_array($Status) && count($Status) > 0) {

        $statusParams = implode(" OR ", array_fill(0, count($Status), "Status = ?"));
        $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE StartDate BETWEEN ? AND ? AND Channel = ? AND ($statusParams) ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) ASC");
        $bindParams = array_merge([$StartTime, $EndTime], is_array($Channel) ? $Channel : [$Channel], $Status);
        $stmt->bind_param(str_repeat("s", count($bindParams)), ...$bindParams);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else if ($Channel != "None") {

        $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE StartDate BETWEEN ? AND ? AND Channel = ? ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) ASC");
        $stmt->bind_param("sss", $StartTime, $EndTime, $Channel);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {

        $stmt = $connectDB->prepare("SELECT * FROM invoice_order WHERE StartDate BETWEEN ? AND ? ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) ASC");
        $stmt->bind_param("ss", $StartTime, $EndTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
}

function getReportAllTime()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM invoice_order ORDER BY CAST(SUBSTRING(InvoiceID, 3) AS UNSIGNED) ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function getCountAllProduct() {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(ProID) AS ProductQTY FROM product");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;

}

function getAllProdcutReport()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, tp.ThaiType, p.Author, p.PricePerUnit, p.CostPerUnit, p.StockQty, p.Status FROM product p JOIN product_type tp ON p.TypeID = tp.TypeID ORDER BY p.ProID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getProductReportFilter($ProductType, $Status)
{
    global $connectDB;
    if (is_array($Status) && count($Status) > 0 && is_array($ProductType) && count($ProductType) > 0) {
        $statusParams = implode(" OR ", array_fill(0, count($Status), "Status = ?"));
        $productTypeParams = implode(",", array_fill(0, count($ProductType), "?"));
        $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, tp.ThaiType, p.Author, p.PricePerUnit, p.CostPerUnit, p.StockQty, p.Status FROM product p JOIN product_Type tp ON p.TypeID = tp.TypeID WHERE p.TypeID IN ($productTypeParams) AND ($statusParams) ORDER BY ProID");
        $bindParams = array_merge($ProductType, $Status);
        $stmt->bind_param(str_repeat("s", count($bindParams)), ...$bindParams);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else if (is_array($Status) && count($Status) > 0) {
        $statusParams = implode(" OR ", array_fill(0, count($Status), "Status = ?"));
        $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, tp.ThaiType, p.Author, p.PricePerUnit, p.CostPerUnit, p.StockQty, p.Status FROM product p JOIN product_Type tp ON p.TypeID = tp.TypeID WHERE ($statusParams) ORDER BY ProID");
        $stmt->bind_param(str_repeat("s", count($Status)), ...$Status);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else if (is_array($ProductType) && count($ProductType) > 0) {
        $productTypeParams = implode(",", array_fill(0, count($ProductType), "?"));
        $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, tp.ThaiType, p.Author, p.PricePerUnit, p.CostPerUnit, p.StockQty, p.Status FROM product p JOIN product_type tp ON p.TypeID = tp.TypeID WHERE p.TypeID IN ($productTypeParams) ORDER BY ProID");
        $stmt->bind_param(str_repeat("s", count($ProductType)), ...$ProductType);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    } else {
        $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, tp.ThaiType, p.Author, p.PricePerUnit, p.CostPerUnit, p.StockQty, p.Status FROM product p JOIN product_type tp ON p.TypeID = tp.TypeID ORDER BY ProID");
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
}

function getSellReport()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum((p.PricePerUnit - p.CostPerUnit) * rl.Qty) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost FROM receipt r JOIN receipt_list rl ON r.RecID = rl.RecID JOIN product p ON rl.ProID = p.ProID GROUP BY r.RecID");
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function getSellReportByTime($StartDate, $EndDate)
{
    global $connectDB;
    if ($StartDate == $EndDate) {
        if ($StartDate == $EndDate) {
            $StartDate = $StartDate . ' 00:00:00';
            $EndDate = $EndDate . ' 23:59:59';
        } else {
            $StartDate = $StartDate . ' 00:00:00';
            $EndDate = $EndDate . ' 23:59:59';
        }
    }   
    $stmt = $connectDB->prepare("SELECT r.RecID, r.PayTime, SUM(rl.Qty) as Qty, r.TotalPrice, Sum((p.PricePerUnit - p.CostPerUnit) * rl.Qty) as Profit, r.Vat, Sum(p.CostPerUnit * rl.Qty) as Cost FROM receipt r JOIN receipt_list rl ON r.RecID = rl.RecID JOIN product p ON rl.ProID = p.ProID WHERE r.PayTime >= ? AND r.PayTime <= ? GROUP BY r.RecID");
    $stmt->bind_param("ss", $StartDate, $EndDate);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
