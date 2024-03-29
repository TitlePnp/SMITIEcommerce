<?php
session_start();
require_once "OrderManage.php";

if (isset($_SESSION['tokenJWT'])) {
    $jwt = $_SESSION['tokenJWT'];
} else if (isset($_SESSION['tokenGoogle'])) {
    $jwt = $_SESSION['tokenGoogle'];
}
else {
    echo "No Token<br>";
}

if (isset($_POST['RecvFName']) && isset($_POST['RecvLName']) && isset($_POST['sex']) && isset($_POST['RecvTel']) && isset($_POST['RecvAddr'])) {
    $RecvFName = $_POST['RecvFName'];
    $RecvLName = $_POST['RecvLName'];
    $RecvSex = $_POST['sex'];
    $RecvTel = $_POST['RecvTel'];
    $RecvAddr = $_POST['RecvAddr'];

    $invoiceID = getNewInvoiceID();
    // echo "getNewInvoiceID Success : " . $invoiceID . "<br>";
    insertReceiver($RecvFName, $RecvLName, $RecvSex, $RecvTel, $RecvAddr);
    // echo "Insett Recv Success";
    $receiverID = getRecvID();
    // echo "getRecvID Success : " . $receiverID . "<br>"; 
    insertReceiverList($receiverID);
    // echo "Insert RecvList Success<br>";
    insertInvoiceOrder($invoiceID);
    // echo "Insert InvoiceOrder Success<br>";
    $invoiceID = getInvoiceID();
    insertInvoice_list($invoiceID, $_SESSION['cart']);
    // echo "InsertInvoice list Success";
    $_SESSION['invoiceID'] = $invoiceID;
    header("Location: ../../frontend/MainPage/ThankOrder.php");
}