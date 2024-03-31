<?php
session_start();
require_once "OrderManage.php";

if ($_POST['taxInvoice'] == 'Yes') {
    
} else if ($_POST['taxInvoice'] == 'No') {

} else {
    echo "Error";
}
