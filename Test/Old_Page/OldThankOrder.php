<?php
session_start();
date_default_timezone_set('Asia/Bangkok');
require '../components/ConnectDB.php';
require '../components/HeaderStore.html';

$userID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMITI Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Sarabun&display=swap" rel="stylesheet">
    <style>
        .headbar {
            display: flex;
            justify-content: space-between;
            border-bottom: 5px solid black;
            border-radius: 2px;
            margin-top: 0px;
            margin-left: 450px;
            margin-right: 450px;
        }

        .orderDetail {
            display: flex;
            justify-content: space-between;
            margin-top: 0px;
            margin-left: 450px;
            margin-right: 450px;
        }

        .orderNo {
            margin-right: 0px;
            font-family: sarabun;
            font-size: 20px;
        }

        .orderInfo {
            margin-left: 0px;
            padding-bottom: 10px;
            color: grey;
            font-family: sarabun;
            font-size: 20px;
        }
    </style>
</head>

<body>
    <center>
        <img src="../pictures/Thank1.png" style="padding-top: 100px;" />

        <?php
        $HisID = $_SESSION['HisID'];
        $payment_Method = $_SESSION['paymentMethod'];
        $payerTaxID = $_SESSION['PayerTaxID'];

        $sql = "SELECT HisID, UpdateTime, CusID, Status FROM history WHERE HisID = '" . $HisID . "';";
        $result = mysqli_query($connectDB, $sql);
        $row = mysqli_fetch_array($result);
        $orderNo = $row['HisID'];
        $statusOrder = $row['Status'];
        $updateTime = $row['UpdateTime'];
        $time = date("h:i A", strtotime($updateTime));
        $date = date("d-m-Y", strtotime($updateTime));

        // if ($_SESSION['userType'] == "guest") {
        //     $custID = $_SESSION['userID'];

        //     $payer_FName = $_POST['payerFirstName'];
        //     $payer_LName = $_POST['payerLastname'];
        // } else if ($_SESSION['userType'] == "member") {
        //     $custID = $row['CusID'];

        //     $sql = "SELECT * FROM customer WHERE CusID = $custID;";
        //     $result = mysqli_query($connectDB, $sql);
        //     $row = mysqli_fetch_array($result);
        //     $payer_FName = $row['CusFName']; 
        //     $payer_LName = $row['CusLName'];
        // }

        $RecvID = $_SESSION['RecvID'];

        $sql = "SELECT * FROM history_list WHERE HisID = '" . $HisID . "';";
        $result = mysqli_query($connectDB, $sql);
        $row = mysqli_fetch_array($result);

        foreach ($result as $row) {
            $productQTY[$row['ProID']]['qty'] = $row['Qty'];
        }

        foreach ($productQTY as $ProID => $details) {
            $sql = "SELECT * FROM product WHERE ProID = " . $ProID . ";";
            $result = mysqli_query($connectDB, $sql);
            $row = mysqli_fetch_array($result);
            $product[$row['ProName']]['qty'] = $details['qty'];
            $product[$row['ProName']]['price'] = $row['PricePerUnit'];
        }

        $totalPrice = 0;
        foreach ($product as $productName => $details) {
            $totalPrice += $details['qty'] * $details['price'];
        }
        $vat = $totalPrice * 0.07;
        $totalPrice = $totalPrice + $vat;
        $totalPrice = number_format($totalPrice, 2);

        $productTotal = 0;
        foreach ($product as $productName => $details) {
            $productTotal += $details['qty'];
        }

        // $sql = "SELECT * FROM payer WHERE TaxID = '$payerTaxID';";
        // $result = mysqli_query($connectDB, $sql);
        // $row = mysqli_fetch_array($result);

        // $payerFname = $row['PayerFName'];
        // $payerLname = $row['PayerLName'];
        // $payerTel = $row['Tel'];
        // $payerAddr = $row['Address'];

        $sql = "SELECT * FROM receiver WHERE RecvID = $RecvID;";
        $result = mysqli_query($connectDB, $sql);
        $row = mysqli_fetch_array($result);

        $receiverFname = $row['RecvFName'];
        $receiverLname = $row['RecvLName'];
        $receiverTel = $row['Tel'];
        $receiverAddr = $row['Address'];

        echo "<div class='headbar'>
            <b class='orderInfo' style='font-family:sarabun; font-size:30px'>Order</b>
            <b class='orderNo' style='font-family:sarabun; font-size:30px'> #" .  $orderNo  . "</b>
        </div>";
        echo "<h1 style='font-family:sarabun; padding-top: 20px'>ขอบคุณที่ใช้บริการ</h1>";
        echo "<h3 style='font-family:sarabun;'>ได้รับคำสั่งซื้อเรียบร้อยแล้ว</h3>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>วันที่ทำการสั่งซื้อ</b>
            <b class='orderNo'>" . $date . "</b>
            </div>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>เวลา</b>
            <b class='orderNo'>" . $time . "</b>
            </div>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>ชื่อ-นามสกุลผู้รับ</b>
            <b class='orderNo'>" . $receiverFname . " " . $receiverLname . "</b>
            </div>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>ที่อยู่การจัดส่ง</b>
            <b class='orderNo'>" . $receiverAddr . "</b>
            </div>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>เบอร์โทรศัพท์</b>
            <b class='orderNo'>" . $receiverTel . "</b>
            </div>";
        echo "<div class='orderDetail'>
            <b class='orderInfo'>จำนวนสินค้า</b>
            <b class='orderNo'>" . $productTotal . " รายการ</b>
            </div>";
        foreach ($product as $productName => $details) {
            echo "<div class='orderDetail'>";
            echo "<b class='orderInfo'></b>";
            echo "<b class='orderNo'>• " . $productName . " " . $details['qty'] . " เล่ม</b>";
            echo "</div>";
        }
        echo "<div class='orderDetail'>";
        echo "<b class='orderInfo'>Vat 7%</b>";
        echo "<b class='orderNo'>" . $vat . " บาท</b>";
        echo "</div>";
        echo "<div class='orderDetail'>";
        echo "<b class='orderInfo'>ราคาสุทธิ</b>";
        echo "<b class='orderNo'>" . $totalPrice . " บาท</b>";
        echo "</div>";
        echo "<div class='orderDetail'>";
        echo "<b class='orderInfo'>ช่องทางการชำระเงิน</b>";
        echo "<b class='orderNo'>" . $payment_Method . "</b>";
        echo "</div>";
        echo "<div class='orderDetail'>";
        echo "<b class='orderInfo'>สถานะคำสั่งซื้อ</b>";
        echo "<b class='orderNo'>" . $statusOrder . "</b>";
        echo "</div>";
        ?>
        <br>
        <?php
        echo "<div class='d-flex justify-content-center'>
        <a href='Store.php' class='btn btn-danger' style='font-family:sarabun; margin-right: 10px; font-size:20px;'><b>🧺 กลับไปหน้าร้านค้า</b></a>
        <form id='show-receipt' method='POST' action='InsertReceipt.php'>
            <input type='hidden' name='recvID' value='" . $RecvID . "'>";
        // echo "<button type='button' class='btn btn-primary' style='font-family:sarabun; font-size:20px;' onclick='insertLog()'><b>🧾 ดูใบเสร็จ</b></button>";
        echo "<button type='submit' class='btn btn-primary' style='font-family:sarabun; font-size:20px;'><b>🧾 ดูใบเสร็จ</b></button>";
        echo "</form>
    </div>";
        ?>

        <br>

    </center>
</body>
<!-- <script>
    function insertLog() {
        var userID = <?php //echo $userID ?>;
        var insertType = "Show Receipt";
        $.ajax({
            type: "POST",
            url: "Insert_log.php",
            data: {
                userID: userID,
                insertType: insertType
            },
            success: function(response) {
                document.getElementById('show-receipt').submit();
            }
        });
    }
</script> -->

</html>