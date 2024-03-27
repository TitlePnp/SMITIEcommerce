<?php
require_once "../../Components/ConnectDB.php";

function selectProduct($name)
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT * FROM product, product_type WHERE Proname = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}

function selectDiscountProduct()
{
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.ProID, p.ProName, p.Description, p.PricePerUnit, p.ImageSource, pt.TypeName FROM product p JOIN product_type pt ON p.TypeID = pt.TypeID LIMIT 7");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
