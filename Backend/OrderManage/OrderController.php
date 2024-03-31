<?php
session_start();
require_once "OrderManage.php";

if ($_POST['taxInvoice'] == 'Yes') {
    var_dump("yes");
} else if ($_POST['taxInvoice'] == 'No') {
    var_dump("no");
} else {
    echo "Error";
}
