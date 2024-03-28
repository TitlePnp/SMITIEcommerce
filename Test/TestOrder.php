<?php
session_start();
$_SESSION['cart'][1] = 5;
$_SESSION['cart'][2] = 3;
$_SESSION['cart'][3] = 2;
$_SESSION['cart'][4] = 1; 

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
    </head>

    <body>
        <a href="../Frontend/MainPage/SummaryOrder.php">test</a>    
    </body>
</html>