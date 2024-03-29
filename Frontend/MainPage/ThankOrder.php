<?php
require_once "../../Backend/Authorized/UserAuthorized.php";
require_once "../../Backend/Authorized/ManageHeader.php";
require_once "../../Backend/OrderManage/GetOrderInfo.php";
use Farzai\PromptPay\Generator;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <title>SMITI Shop:ThankYou for order</title>
    <style>
        * {
            font-family: 'Kodchasan';
        }
    </style>
</head>

<body>
    <?php
    $invoiceInfo = getInvoiceInfo($_SESSION['InvoiceID']);
    $receiverInfo = getReceiverInfo($_SESSION['ReceiverID']);
    $orderList = getOrderList($_SESSION['InvoiceID']);
    ?>
    <div class="flex items-center flex-col mb-20">
        <div class="flex">
            <img src="../../Pictures/Thank.png" alt="">
        </div>

        <div class="flex items-center flex-col">
            <h1 class="text-4xl font-bold mt-3 mb-3">ขอบคุณที่ใช้บริการ</h1>
            <h1 class="text-xl font-semibold mb-3">ได้รับคำสั่งซื้อเรียบร้อยแล้ว</h1>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold">Order number: </p>
            </div>
            <div>
                <p class="text-xl font-semibold">#<?php echo $invoiceInfo['InvoiceID']; ?></p>
            </div>
        </div>

        <hr class="w-2/6 border-2 rounded-sm border-black my-2">

        <!-- <div class="flex items-center flex-col">
            <h1 class="text-4xl font-bold mt-3 mb-3">ขอบคุณที่ใช้บริการ</h1>
            <h1 class="text-xl font-semibold mb-3">ได้รับคำสั่งซื้อเรียบร้อยแล้ว</h1>
        </div> -->

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">วันที่ทำการสั่งซื้อ: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold">#<?php echo $invoiceInfo['StartDate']; ?></p>
            </div>
        </div>
        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ชื่อ-นามสกุล: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold"><?php echo $receiverInfo['RecvFName'] . ' ' . $receiverInfo['RecvLName']; ?></p>
            </div>
        </div>
        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ที่อยู่การจัดส่ง: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold"><?php echo $receiverInfo['Address']; ?></p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ที่อยู่การจัดส่ง: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold"><?php echo $receiverInfo['Address']; ?></p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">จำนวนสินค้า: </p>
            </div>
            <div class="w-full flex justify-end">
                <?php
                $ProductCount = getCountInvoiceList($_SESSION['InvoiceID']);
                ?>
                <p class="text-md font-semibold"><?php echo $ProductCount['COUNT(ProID)']; ?> รายการ</p>
            </div>
        </div>

        <hr class="w-2/6 rounded-sm border-black my-2">

        <div class="flex flex-col w-2/6 items-end">
            <?php
            $totlaPrice = 0;
            while ($row = $orderList->fetch_assoc()) {
                echo '<p class="text-md ">' . $row['ProName'] . ' จำนวน ' . $row['Qty'] . ' เล่ม</p>';
                $totlaPrice += $row['PricePerUnit'] * $row['Qty'];
            }
            $totlaPriceFormat = number_format($totlaPrice, 2);
            ?>
        </div>

        <hr class="w-2/6 rounded-sm border-black my-2">

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ราคารวม: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold"><?php echo $totlaPriceFormat ?> บาท</p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">Vat 7%: </p>
            </div>
            <div class="w-full flex justify-end">
                <?php
                $vat = $totlaPrice * 0.07;
                $vatFormat = number_format($vat, 2);
                ?>
                <p class="text-md font-semibold"><?php echo $vatFormat ?> บาท</p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ราคาสุทธิ: </p>
            </div>
            <div class="w-full flex justify-end">
                <?php
                $totlaPrice += $vat;
                $totlaPriceFormat = number_format($totlaPrice, 2);
                ?>
                <p class="text-md font-semibold"><?php echo $totlaPriceFormat ?> บาท</p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">สถานะคำสั่งซื้อ: </p>
            </div>
            <div class="w-full flex justify-end">
                <p class="text-md font-semibold"><?php echo $invoiceInfo['Status'] ?> </p>
            </div>
        </div>

        <div class="flex w-2/6">
            <div class="w-full flex justify-between">
                <p class="text-xl font-semibold text-gray-500">ช่องทางการชำระเงิน: </p>
            </div>
            <div class="w-full flex justify-end">
                <?php
                if ($invoiceInfo['Payment'] == "COD") {
                    $payMethod = "เก็บเงินปลายทาง";
                } else if ($invoiceInfo['Payment'] == "MobileBanking") {
                    $payMethod = "โอนผ่านบัญชีธนาคาร";
                }
                ?>
                <p class="text-md font-semibold"><?php echo $payMethod ?> </p>
            </div>
        </div>

        <hr class="w-2/6 border-2 rounded-sm border-black my-2">

        <?php
        if ($invoiceInfo["Payment"] == "MobileBanking") {
            $generator = new Generator();
            $qrCode = $generator->generate(
                target: "098-888-8888", 
                amount: $totlaPrice
            );
            
            // $qrCode->save('qrcode.png');
            
            // Or insert it directly into a template:
            echo '<img src="' . $qrCode->asDataUri() . '" />';
        }
        ?>


        <div class="flex w-2/6 mt-5 justify-center">
            <a href="Home.php"><button class="bg-red-500 p-3 rounded-md text-white font-semibold hover:shadow-lg mx-2">กลับไปหน้าร้านค้า</button></a>
            <?php
            if ($invoiceInfo['Payment'] == "MobileBanking") {
                echo '<a href="MobileBanking.php"><button class="bg-green-500 p-3 rounded-md text-white font-semibold hover:shadow-lg mx-2">แจ้งการชำระเงิน</button></a>';
            }
            ?>
        </div>
    </div>
</body>

</html>