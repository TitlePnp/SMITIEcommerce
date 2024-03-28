<?php
require_once "../../Components/ConnectDB.php";
require_once "../../vendor/autoload.php";

use Firebase\JWT\Key;
use \Firebase\JWT\jwt;

function insertPayer($TaxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO Payer (TaxID, PayerFName, PayerLName, PayerSex, PayerTel, PayerAddr) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $TaxID, $PayerFName, $PayerLName, $PayerSex, $PayerTel, $PayerAddr);
    $stmt->execute();
    $stmt->close();
}

function insertPayerList($CusID, $TaxID)
{
    global $connectDB;
    if (!isset($_SESSION['tokenJWT']) && !isset($_SESSION['tokenGoogle'])) {
        $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM payer_list");
        $stmt->execute();
        $result = $stmt->get_result();
        $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
    } else if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
        $NumID = 1;
    }
    $stmt = $connectDB->prepare("INSERT INTO payer_list (CusID, NumID, TaxID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $CusID, $NumID, $TaxID);
    $stmt->execute();
    $stmt->close();
}

function insertReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr)
{
    global $connectDB;
    $stmt = $connectDB->prepare("INSERT INTO Receiver (RecvFName, RecvLName, RecvSex, RecvTel, RecvAddr) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr);
    $stmt->execute();

    $stmt = $connectDB->prepare("SELECT MAX(RecvID) AS LastRecvID FROM receiver");
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result->fetch_assoc()['LastRecvID'];
}

function insertReceiverList()
{
    global $connectDB;
    if (!isset($_SESSION['tokenJWT']) && !isset($_SESSION['tokenGoogle'])) {
        $stmt = $connectDB->prepare("SELECT MAX(NumID) AS MaxNumID FROM receiver_list");
        $stmt->execute();
        $result = $stmt->get_result();
        $NumID = $result->fetch_assoc()['MaxNumID'] + 1;
    } else if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
        $NumID = 1;
    }
    $stmt = $connectDB->prepare("INSERT INTO receiver_list (CusID, NumID, RecvID) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $CusID, $NumID, $TaxID);
    $stmt->execute();
    $stmt->close();
}

function insertInvoiceOrder()
{
    global $connectDB, $jwt, $key;
    if (isset($_SESSION['tokenJWT'])) {
        $token = $_SESSION['tokenJWT'];
        $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
    }
}
