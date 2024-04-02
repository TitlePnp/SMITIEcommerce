<?php
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../vendor/autoload.php';
require_once "../../Backend/ProductQuery/ProductInfo.php";
require_once "../../Backend/OrderManage/OrderQuery.php";


use Firebase\JWT\Key;
use \Firebase\JWT\JWT;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Document</title>

    <style>
        * {
            font-family: 'Kodchasan';
            margin: 0px;
            padding: 0px;
        }
    </style>
</head>

<body class="bg-gray-200">
    <?php
    $orderID = $_POST['invoiceID'];
    ?>
    <div class="px-28 py-8">
        <a href="../Profile/UserProfile.php" class="hover:text-blue-800 text-blue-500 font-semibold"><i class='bx bx-arrow-back mr-2'></i>ย้อนกลับ</a>
        <div>
            <p class="font-bold text-3xl">Order #<?php echo "{$orderID}" ?> </p>
        </div>
        <div class="flex flex-col shadow-lg rounded-lg h-full my-5 bg-white">
            <?php
            $orderList = getOrderListDetail($orderID);
            $orderList = $orderList->fetch_assoc();
            $ProductInfo = selectProduct($orderList['ProName']);
            $ProductInfo = $ProductInfo->fetch_assoc();

            ?>
            <div class='flex p-5'>
                <div class='flex justify-center w-2/12'>
                    <img src='<?php echo "{$ProductInfo['ImageSource']}" ?>' class='w-32 h-46 object-cover rounded-md   '>
                </div>
                <div class='w-6/12'>
                    <p class='font-bold text-xl'><?php echo "{$ProductInfo['ProName']}" ?></p>
                    <p class='text-gray-500'><?php echo "{$ProductInfo['Description']}" ?></p>
                    <div class="flex">
                        <p class='text-black font-semibold'>ราคา: </p>
                        <p class='text-black ml-2'><?php echo "{$ProductInfo['PricePerUnit']}" ?> บาท</p>
                    </div>
                    <div class="flex">
                        <p class='text-black font-semibold'>จำนวน:</p>
                        <p class='text-black ml-2'><?php echo "{$orderList['Qty']}" ?> เล่ม</p>
                    </div>
                    <div class="flex">
                        <p class='text-black font-semibold'>ราคาสุทธิ:</p>
                        <?php
                        $totalPrice = $orderList['Qty'] * $ProductInfo['PricePerUnit'];
                        $vat = $totalPrice * 0.07;
                        $totalPrice += $vat;
                        $totalPriceFormat = number_format($totalPrice, 2);
                        ?>
                        <p class='text-black ml-2'><?php echo "{$totalPriceFormat}" ?> บาท</p>
                    </div>
                </div>
                <?php
                $orderDetail = getAddressAndPriceOrder($orderID);
                $orderDetail = $orderDetail->fetch_assoc();
                ?>
                <div class="flex w-4/12">
                    <div class="flex flex-col ml-10 mr-5 w-full">
                        <p class="text-md font-semibold">ที่อยู่จัดส่ง</p>
                        <p><?php echo "{$orderDetail['RecvAddress']}, {$orderDetail['RecvProvince']} {$orderDetail['RecvPostcode']}" ?></p>
                    </div>
                    <div class="flex flex-col w-full">
                        <p class="text-md font-semibold">ที่อยู่ผู้จ่าย</p>
                        <p><?php echo "{$orderDetail['PayerAddress']}, {$orderDetail['PayerProvince']} {$orderDetail['PayerPostcode']}" ?></p>

                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>