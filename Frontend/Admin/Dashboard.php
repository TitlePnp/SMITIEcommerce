<?php
//   require '../../Backend/Authorized/AdminAuthorized.php';
$role = "Admin";
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/OrderManage/GetOrderInfo.php';
require '../../Components/ConnectDB.php';
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <title>SMITI SHOP: HOME</title>
  <style>
    * {
      font-family: Kodchasan;
    }
  </style>

<body class="bg-gray-200">
  <div class="flex flex-col px-10 py-3">
    <div class="grid grid-cols-4 gap-5">
      <div class="bg-white rounded-lg shadow-lg flex">
        <div class="h-full rounded-lg bg-green-500 w-1.5"></div>
        <div class="flex items-center p-5">
          <i class='bx bx-package text-5xl'></i>
        </div>
        <div class="flex flex-col w-full p-5">
          <div class="h-4/12 flex justify-center">
            <p class="text-md ">จำนวนคำสั่งซื้อวันนี้</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center">
            <?php
            $todayOrder = getTodayOrder();
            echo "<p class='text-2xl font-medium text-green-400'>" . $todayOrder['COUNT(InvoiceID)'] . "</p>";
            ?>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg flex">
        <div class="h-full rounded-lg bg-orange-500 w-1.5"></div>
        <div class="flex items-center p-5">
          <i class='bx bxs-truck text-5xl'></i>
        </div>
        <div class="flex flex-col w-full p-5">
          <div class="h-4/12 flex justify-center">
            <p class="text-md text-base" style="font-size: 14px">จำนวนรายการสั่งซื้อที่ต้องจัดส่ง</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center">
            <?php
            $deliveryOrder = getPaidAndCOD();
            echo "<p class='text-2xl font-medium text-orange-500'>" . $deliveryOrder['COUNT(RecID)'] . "</p>";
            ?>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg flex">
        <div class="h-full rounded-lg bg-cyan-500 w-1.5"></div>
        <div class="flex items-center p-5">
          <i class='bx bx-money text-5xl'></i>
        </div>
        <div class="flex flex-col w-full p-5">
          <div class="h-4/12 flex justify-center">
            <p class="text-md text-base" style="font-size: 14px">จำนวนรายการที่ต้องตรวจสอบการชำระ</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center">
            <?php
            $PendingOrder = getPendingStatus();
            echo "<p class='text-2xl font-medium text-cyan-500'>" . $PendingOrder['COUNT(RecID)'] . "</p>";
            ?>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg">
        <div class="flex flex-col p-5">
          <div class="h-4/12">
            <p class="text-md font-semibold">จำนวนรายการที่ต้องตรวจสอบการชำระ</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center">
            <?php
            $PendingOrder = getPendingStatus();
            echo "<p class='text-2xl text-cyan-500 font-medium'>" . $PendingOrder['COUNT(RecID)'] . "</p>";
            ?>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg">
        <div class="flex flex-col p-5">
          <div class="h-4/12">
            <p class="text-md font-semibold">จำนวนคำสั่งทั้งหมด</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center">
            <?php
            $totalOrder = getAllOrder();
            echo "<p class='text-2xl font-medium text-blue-500'>" . $totalOrder['COUNT(InvoiceID)'] . "</p>";
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php



  ?>

</body>

</html>