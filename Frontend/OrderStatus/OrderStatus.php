<?php
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../vendor/autoload.php';
require_once "../../Backend/ProductQuery/ProductInfo.php";
require_once "../../Backend/OrderManage/OrderQuery.php";
require_once "../../Backend/OrderManage/GetOrderInfo.php";

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
    if (isset($_POST['invoiceID'])) {
        $orderID = $_POST['invoiceID'];
    } else if (isset($_SESSION['InvoiceID'])) {
        $orderID = $_SESSION['InvoiceID'];
        unset($_SESSION['InvoiceID']);
    }

    $haveReceipt = false;
    $orderListResult = getOrderListDetail($orderID);
    $Status = getReceiptStatus($orderID);
    if ($Status == "No Receipt") {
        $getOrderDetail = getInvoiceInfo($orderID);
        $Status = $getOrderDetail['Status'];
        $haveReceipt = false;
    } else {
        $getOrderDetail = getReceiptDetail($orderID);
        $haveReceipt = true;
    }

    // $Status = "Cancel";

    ?>
    <div class="px-28 py-8">
        <a href="../Profile/UserProfile.php" class="hover:text-blue-800 text-blue-500 font-semibold"><i class='bx bx-arrow-back mr-2'></i>ย้อนกลับ</a>
        <div class="my-3 flex justify-between">
            <p class="font-bold text-2xl ">คำสั่งซื้อ #<?php echo "{$orderID}" ?> </p>
            <div>
                <?php
                if ($Status == "Paid" || $Status == "Delivered" || $Status == "Completed" || $Status == "DI") {
                    $RecID = $getOrderDetail['RecID'];
                    CompleteStatus($RecID);
                } else if ($Status == "Ordered") {
                    OrderedStatus($orderID);
                } else if ($Status == "Pending") {
                    PendingStatus($orderID);
                } else if ($Status == "COD") {
                    CODStatus($orderID);
                } else if ($Status == "Cancel") {
                }
                ?>
            </div>
        </div>
        <?php
        // $orderList = $orderListResult->fetch_assoc();
        // $ProductInfoResutl = selectProduct($orderList['ProName']);
        // $ProductInfo = $ProductInfo->fetch_assoc();
        while ($orderList = $orderListResult->fetch_assoc()) {
            $ProductInfoResutl = selectProduct($orderList['ProName']);
            $ProductInfo = $ProductInfoResutl->fetch_assoc();
            echo "<div class='flex flex-col rounded-lg h-full my-5 bg-white'>";

            echo "<div class='flex p-5'>";
            echo "   <div class='flex justify-center w-2/12 items-center'>";
            echo "        <img src='{$ProductInfo['ImageSource']}'class='w-32 h-42 object-cover rounded-md'>";
            echo "   </div>";
            echo "   <div class='w-full'>";
            echo "      <p class='font-bold text-xl'>{$ProductInfo['ProName']}</p>";
            echo "       <p class='text-gray-500 text-md'>{$ProductInfo['Description']}</p>";
            echo "       <div class='flex'>";
            echo "           <p class='text-black font-semibold'>ราคา: </p>";
            echo "          <p class='text-black ml-2'>{$ProductInfo['PricePerUnit']} บาท</p>";
            echo "        </div>";
            echo "       <div class='flex'>";
            echo "            <p class='text-black font-semibold'>จำนวน:</p>";
            echo "          <p class='text-black ml-2'>{$orderList['Qty']} เล่ม</p>";
            echo "        </div>";
            // echo "        <div class='flex'>";
            // echo "            <p class='text-black font-semibold'>ราคาสุทธิ:</p>";
            // $totalPrice = $orderList['Qty'] * $ProductInfo['PricePerUnit'];
            // $vat = $totalPrice * 0.07;
            // $totalPrice += $vat;
            // $totalPriceFormat = number_format($totalPrice, 2);
            // echo "            <p class='text-black ml-2'>{$totalPriceFormat} บาท</p>";
            // echo "       </div>";
            echo "    </div>";
            $orderDetail = getAddressAndPriceOrder($orderID);
            $orderDetail = $orderDetail->fetch_assoc();
            echo "</div>";
            echo "</div>";
        };
        ?>
        <div class="bg-white p-5 rounded-lg">
            <?php
            if ($haveReceipt) {
                $OrderDate = date_create($getOrderDetail['PayTime']);
                $OrderDate = date_format($OrderDate, "d/m/Y");
                $OrderTime = date_create($getOrderDetail['PayTime']);
                $OrderTime = date_format($OrderTime, "H:i:s");
            } else {
                $OrderDate = date_create($getOrderDetail['StartDate']);
                $OrderDate = date_format($OrderDate, "d/m/Y");
                $OrderTime = date_create($getOrderDetail['StartDate']);
                $OrderTime = date_format($OrderTime, "H:i:s");
            }

            $vat = $getOrderDetail['Vat'];
            $OrderTotalPrice = $getOrderDetail['TotalPrice'];

            $vatFormat = number_format($vat, 2);
            $InvoiceTotalPriceFormat = number_format($OrderTotalPrice + $vat, 2);
            $SubtotalPrice = $OrderTotalPrice;
            $SubtotalPriceFormat = number_format($SubtotalPrice, 2);

            $PaymentMethod = $getOrderDetail['Channel'];
            ?>
            <div>
                <div>
                    <div class="flex">
                        <p class="font-semibold">สั่งซื้อเมื่อ: </p>
                        <p class="ml-2"><?php echo "{$OrderDate}" ?></p>
                    </div>
                    <div class="flex">
                        <p class="font-semibold">เวลา:</p>
                        <p class="ml-2"><?php echo "{$OrderTime}" ?></p>
                    </div>
                </div>
                <div class="flex bg-gray-300 h-4 mt-5 rounded-md">
                    <?php
                    // $PaymentMethod = "COD";
                    if ($PaymentMethod == "Transfer") {
                        if ($Status == "Pending") {
                            echo "<div class='bg-blue-600 h-4 w-5/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Paid") {
                            echo "<div class='bg-blue-600 h-4 w-8/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Delivered") {
                            echo "<div class='bg-blue-600 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end text-blue-600">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Ordered") {
                            echo "<div class='bg-blue-600 h-4 w-2/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center ">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center ">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Completed") {
                            echo "<div class='bg-blue-600 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end text-blue-600">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Cancel") {
                            echo "<div class='bg-red-500 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center ">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end ">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        }
                    } else if ($PaymentMethod == "COD") {
                        if ($Status == "COD") {
                            echo "<div class='bg-blue-600 h-4 w-5/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดเตรียมสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Ordered") {
                            echo "<div class='bg-blue-600 h-4 w-5/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดเตรียมสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Paid") {
                            echo "<div class='bg-blue-600 h-4 w-8/12 rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Delivered") {
                            echo "<div class='bg-blue-600 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end text-blue-600">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "DI") {
                            echo "<div class='bg-blue-600 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end text-blue-600">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Completed") {
                            echo "<div class='bg-blue-600 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full text-blue-600'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '       <p class="text-sm">กำลังจัดสินค้า</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center text-blue-600">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end text-blue-600">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        } else if ($Status == "Cancel") {
                            echo "<div class='bg-red-500 h-4 w-full rounded-md'>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='w-full flex'>";
                            echo "    <div class='my-5 w-full'>";
                            echo '       <p class="text-sm">ยืนยันคำสั่งซื้อ</p>';
                            echo '    </div>';
                            echo '    <div class="my-5 w-full mx-2 text-center ">';
                            echo '       <p class="text-sm">ตรวจสอบการชำระเงิน</p>';
                            echo '    </div>';
                            echo '  <div class="my-5 w-full mx-2 text-center">';
                            echo '      <p class="text-sm">กำลังจัดส่งสินค้า</p>';
                            echo '    </div>';
                            echo '   <div class="my-5 w-full text-end ">';
                            echo '        <p class="text-sm">จัดส่งสำเร็จ</p>';
                            echo '   </div>';
                            echo '</div>';
                        }
                    }

                    ?>
                    <!-- <div class="bg-blue-600 h-4 w-full rounded-md"> 2 5 8 full -->
                    <!-- This step is completed -->
                    <!-- </div> -->
                    <!-- </div>
                <div class="w-full flex">
                    <div class="my-5 w-full">
                        <p class="text-sm">ยืนยันคำสั่งซื้อ</p>
                    </div>
                    <div class="my-5 w-full mx-2 text-center">
                        <p class="text-sm">ตรวจสอบการชำระเงิน</p>
                    </div>
                    <div class="my-5 w-full mx-2 text-center">
                        <p class="text-sm">กำลังจัดส่งสินค้า</p>
                    </div>
                    <div class="my-5 w-full text-end">
                        <p class="text-sm">จัดส่งสำเร็จ</p>
                    </div>
                </div> -->


                    <div class="my-5 rounded-lg p-2 bg-gray-100 flex">
                        <div class="w-2/12 p-2 flex flex-col">
                            <div>
                                <p class="font-semibold">ช่องทางการชำระเงิน</p>
                            </div>
                            <div>
                                <p class="text-md "><?php
                                                    if ($PaymentMethod == "COD") {
                                                        $PaymentMethod = "เก็บเงินปลายทาง";
                                                    } else if ($PaymentMethod == "Transfer") {
                                                        $PaymentMethod = "โอนผ่านบัญชีธนาคาร";
                                                    }
                                                    echo "{$PaymentMethod}"
                                                    ?></p>
                            </div>
                        </div>
                        <div class="w-6/12 p-2">
                            <?php
                            echo "    <div class='flex w-full'>";
                            echo "       <div class='flex flex-col w-full'>";
                            echo "           <p class='text-md font-semibold'>ที่อยู่ผู้จ่าย</p>";
                            echo "           <p>{$orderDetail['PayerAddress']}, {$orderDetail['PayerProvince']} {$orderDetail['PayerPostcode']} </p>";
                            echo "        </div>";
                            echo "       <div class='flex flex-col ml-10 mr-5 w-full'>";
                            echo "           <p class='text-md font-semibold'>ที่อยู่จัดส่ง</p>";
                            echo "            <p> '{$orderDetail['RecvAddress']}, {$orderDetail['RecvProvince']} {$orderDetail['RecvPostcode']}</p>";
                            echo "       </div>";
                            echo "    </div>";
                            ?>
                        </div>
                        <div class="w-4/12 mx-5">
                            <div class="flex justify-between border-b py-2 border-gray-300">
                                <p>ราคาสินค้า</p>
                                <p><?php echo "{$SubtotalPriceFormat} บาท" ?></p>
                            </div>
                            <div class="flex justify-between my-2 border-b py-2 border-gray-300">
                                <p>ภาษี</p>
                                <p><?php echo "{$vatFormat} บาท" ?></p>
                            </div>
                            <div class="flex justify-between  my-2">
                                <p class="font-bold">รวมทั้งสิ้น</p>
                                <p class="font-bold"><?php echo "{$InvoiceTotalPriceFormat} บาท" ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

</body>

</html>

<?php
function OrderedStatus($orderID)
{
    echo '<form method="POST" action="../UploadPage/Upload.php" class="inline-block">';
    echo '<button class="py-2 bg-green-500 rounded-md px-4 text-white font-semibold hover:shadow-lg hover:bg-green-700"><i class="bx bx-image-alt mr-3"></i>แจ้งการชำระเงิน</button>';
    echo '<input type="hidden" name="invoiceID" value="' . $orderID . '">';
    echo '</form>';
    echo '<form method="POST" action="../MainPage/Payment.php" class="inline-block">';
    echo '<button class="py-2 bg-blue-500 rounded-md px-5 mx-5 text-white font-semibold hover:shadow-lg hover:bg-blue-700"><i class="bx bx-money mr-3"></i>ชำระเงิน</button>';
    echo '<input type="hidden" name="InvoiceID" value="' . $orderID . '">';
    echo '</form>';
    echo '<form method="POST" action="../PDF/Invoice.php" class="inline-block" target="_blank">';
    echo '<button class="py-2 bg-red-500 rounded-md px-5 text-white font-semibold hover:shadow-lg hover:bg-red-700"><i class="bx bxs-file-pdf mr-3"></i>ใบแจ้งหนี้</button>';
    echo '<input type="hidden" name="InvoiceID" value="' . $orderID . '">';
    echo '</form>';
}

function PendingStatus($orderID)
{
    echo '<form method="POST" action="../UploadPage/Upload.php" class="inline-block">';
    echo '<button class="mr-2 py-2 bg-green-500 rounded-md px-4 text-white font-semibold hover:shadow-lg hover:bg-green-700"><i class="bx bx-image-alt mr-3"></i>แจ้งการชำระเงิน</button>';
    echo '<input type="hidden" name="invoiceID" value="' . $orderID . '">';
    echo '</form>';
    echo '<form method="POST" action="../PDF/Invoice.php" class="inline-block" target="_blank">';
    echo '<button class="ml-2 py-2 bg-red-500 rounded-md px-5 text-white font-semibold hover:shadow-lg hover:bg-red-700"><i class="bx bxs-file-pdf mr-3"></i>ใบแจ้งหนี้</button>';
    echo '<input type="hidden" name="InvoiceID" value="' . $orderID . '">';
    echo '</form>';
}

function CompleteStatus($RecID)
{
    echo '<form method="POST" action="../PDF/Receipt.php" class="inline-block" target="_blank">';
    echo '<a class="mr-2" href=""><button class="py-2 bg-green-500 rounded-md px-8 text-white font-semibold hover:shadow-lg hover:bg-green-700">ใบเสร็จ</button></a>';
    echo '<input type="hidden" name="ReceiptID" value="' . $RecID . '">';
    echo '</form>';
}

function CODStatus($orderID)
{
    echo '<form method="POST" action="../PDF/Invoice.php" class="inline-block" target="_blank">';
    echo '<button class="ml-2 py-2 bg-red-500 rounded-md px-5 text-white font-semibold hover:shadow-lg hover:bg-red-700"><i class="bx bxs-file-pdf mr-3"></i>ใบแจ้งหนี้</button>';
    echo '<input type="hidden" name="InvoiceID" value="' . $orderID . '">';
    echo '</form>';
}

?>