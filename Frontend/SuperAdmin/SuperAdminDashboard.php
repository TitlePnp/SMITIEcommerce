<?php
//   require '../../Backend/Authorized/AdminAuthorized.php';
$role = "Admin";
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/OrderManage/GetOrderInfo.php';
require '../../Components/ConnectDB.php';

//set timezone
date_default_timezone_set('Asia/Bangkok');
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

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css">
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <title>SMITI SHOP: HOME</title>
  <style>
    * {
      font-family: Kodchasan;
    }

    .apexcharts-canvas {
      position: relative;
      user-select: none;
      /* this is just for the example */
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
          <div class="h-8/12 my-5 flex justify-center flex items-center">
            <?php
            $todayOrder = getTodayOrder();
            echo "<p class='text-2xl font-semibold text-green-400'>" . $todayOrder['COUNT(InvoiceID)'] . "</p>";
            echo "<p class='text-base font-medium ml-2'>รายการ</p>";
            ?>
          </div>
          <!-- <div class="flex">
            <i class='bx bx-chevron-up text-green-500'></i><p class="text-sm ml-2">Up500%</p>
          </div> -->
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg flex">
        <div class="h-full rounded-lg bg-purple-500 w-1.5"></div>
        <div class="flex items-center p-5">
          <i class='bx bxs-truck text-5xl'></i>
        </div>
        <div class="flex flex-col w-full p-5 justify-center items-center">
          <div class="h-4/12 flex justify-center">
            <p class="text-md text-base">รายการสั่งซื้อที่ต้องจัดส่ง</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center flex items-center">
            <?php
            $deliveryOrder = getPaidAndCOD();
            echo "<p class='text-2xl font-medium text-purple-500'>" . $deliveryOrder['COUNT(RecID)'] . "</p>";
            echo "<p class='text-base font-medium ml-2'>รายการ</p>";
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
            <p class="text-md text-base">รายการตรวจสอบการชำระ</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center flex items-center">
            <?php
            $PendingOrder = getPendingStatus();
            echo "<p class='text-2xl font-medium text-cyan-500'>" . $PendingOrder['COUNT(RecID)'] . "</p>";
            echo "<p class='text-base font-medium ml-2'>รายการ</p>";
            ?>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-lg flex">
        <div class="h-full rounded-lg bg-orange-500 w-1.5"></div>
        <div class="flex items-center p-5">
          <i class='bx bx-list-check text-5xl'></i>
        </div>
        <div class="flex flex-col w-full p-5">
          <div class="h-4/12 flex justify-center">
            <p class="text-md text-base">จำนวนคำสั่งซื้อทั้งหมด</p>
          </div>
          <div class="h-8/12 my-5 flex justify-center flex items-center">
            <?php
            $totalOrder = getAllOrder();
            echo "<p class='text-2xl font-medium text-orange-500'>" . $totalOrder['COUNT(InvoiceID)'] . "</p>";
            echo "<p class='text-base font-medium ml-2'>รายการ</p>";
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="flex my-5">
      <div class="h-full w-8/12 bg-white p-5 rounded-lg">
        <p>test</p>
      </div>
      <div class="ml-5 h-full w-4/12 bg-white p-5 rounded-lg ">
        <div class="flex justify-between mb-5">
          <p class="font-semibold text-lg ">ยอดขายต่อสัปดาห์</p>
          <div>test</div>
        </div>
        <div id="chart"></div>


      </div>
    </div>
  </div>
  <?php
  $result = getWeekOrders();
  $days = array_keys($result);
  $sales = array_values($result);
  ?>
  <script>
    var days = <?php echo json_encode($days); ?>;
    var sales = <?php echo json_encode($sales); ?>;

    var options = {
      chart: {
        type: 'bar'
      },
      plotOptions: {
        bar: {
          borderRadius: 5,
          columnWidth: '50%',
        }
      },
      series: [{
        name: 'sales',
        data: sales
      }],
      xaxis: {
        categories: days
      },
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
  </script>

</body>

</html>