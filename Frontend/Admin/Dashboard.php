<?php
  require '../../Backend/Authorized/AdminAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
require '../../Backend/OrderManage/GetOrderInfo.php';
require '../../Backend/ProductQuery/ProductDetail.php';
require '../../Backend/OrderManage/OrderQuery.php';
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

    td {
      text-align: center;
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
            $totalOrderResult = getAllOrder();
            $totalOrder = $totalOrderResult['COUNT(InvoiceID)'];
            echo "<p class='text-2xl font-medium text-orange-500'>" . $totalOrder . "</p>";
            echo "<p class='text-base font-medium ml-2'>รายการ</p>";
            ?>
          </div>
        </div>
      </div>
    </div>

    <div class="flex my-5">
      <div class="h-full w-8/12  flex flex-col">

        <div class=" mb-5 p-5 bg-white rounded-lg shadow-lg flex">
          <div class="w-8/12">
            <div class="flex items-center">
              <p class="font-semibold text-xl">ภาพรวมคำสั่งซื้อ</p>
              <i class='bx bxs-pie-chart-alt-2 text-2xl ml-2 text-yellow-500'></i>
            </div>
            <hr class="border-t-4 rounded-lg border-yellow-400 my-5 mr-2">
            <div id="piechart" class="flex justify-center">

            </div>

          </div>
          <div class="w-4/12 border-l-2 px-5 flex flex-col">
            <div class="flex justify-center">
              <p class="font-semibold text-xl">สถิติคำสั่งซื้อ</p>
              <i class='bx bxs-bar-chart-alt-2 text-2xl ml-2 text-yellow-500'></i>
            </div>
            <div class="flex flex-col mt-5 items-center">
              <div class="flex">
                <p>คำสั่งซื้อทั้งหมด <?php echo $totalOrder ?> รายการ</p>
              </div>
              <?php
              $statOrderResult = getOverallStatus();
              while ($row = $statOrderResult->fetch_assoc()) {
                echo '<div class="flex">';
                echo '<p>สถานะ ' . $row['Status'] . '</p>';
                echo '<p class="ml-2">' . $row['count'] . ' รายการ</p>';
                echo '</div>';
              }
              ?>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-lg shadow-lg">
          <div class="mb-5 flex justify-between ">
            <div>
              <p class="font-semibold text-xl py-2">รายการสินค้าที่ต้องจัดส่ง</p>
            </div>
            <div class="flex items-center">
              <button class="text-blue-400 hover:text-blue-500">ทั้งหมด</i></button>
              <i class='bx bx-chevron-right text-blue-400 hover:text-blue-500'></i>
            </div>
          </div>
          <hr class="border-t-4 border-purple-500">
          <div class="w-full flex flex-col">
            <div class="flex flex w-full p-5">
              <div class="w-full flex justify-center">
                <p class="font-semibold">รหัสสินค้า</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">ชื่อสินค้า</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">จำนวนสินค้าในคลัง</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">จำนวนสินค้าที่ถูกสั่งซื้อ</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">รหัสคำสั่งซื้อ</p>
              </div>
            </div>
            <?php
            $result = getQtyPaidStatusCompareStockQty();
            $testResult = $result->fetch_assoc();
            while ($row = $result->fetch_assoc()) {
              echo '<div class="flex w-full p-5 rounded-lg hover:bg-purple-200">';
              echo '<div class="w-full flex justify-center">';
              echo '<p>' . $row['ProID'] . '</p>';
              echo '</div>';
              echo '<div class="w-full flex justify-center">';
              $proName = $row['ProName'];
              if (mb_strlen($proName) > 20) {
                $proName = mb_substr($proName, 0, 20) . '...';
              }
              echo '<p>' . $proName . '</p>';
              echo '</div>';
              echo '<div class="w-full flex justify-center">';
              echo '<p>' . $row['StockQty'] . '</p>';
              echo '</div>';
              echo '<div class="w-full flex justify-center">';
              echo '<p>' . $row['Qty'] . '</p>';
              echo '</div>';
              echo '<div class="w-full flex justify-center">';
              echo '<p>' . $row['InvoiceID'] . '</p>';
              echo '</div>';
              echo '</div>';
            }
            ?>
          </div>
        </div>
      </div>
      <div class="ml-5 h-full w-4/12 bg-white p-5 rounded-lg shadow-lg ">
        <div class="flex mb-5 items-center">
          <i class='bx bxs-error text-2xl text-red-500 mr-2'></i>
          <p class="font-semibold text-xl">จำนวนสินค้าในคลังที่ใกล้หมด</p>
          <!-- <div class="flex items-center justify-center items-center">
          </div> -->
        </div>
        <hr class="border-t-4 border-red-500">

        <div class="w-full flex flex-col">
          <div>
            <div class="flex w-full p-5">
              <div class="w-full flex justify-center">
                <p class="font-semibold">รหัสสินค้า</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">ชื่อสินค้า</p>
              </div>
              <div class="w-full flex justify-center">
                <p class="font-semibold">จำนวน</p>
              </div>
            </div>
            <?php
            $result = getQtyWarningProduct();
            while ($row = $result->fetch_assoc()) {
              echo '<div class="flex w-full p-2 rounded-lg hover:bg-gray-200 hover:text-blue-500 hover:cursor-pointer">';
              echo '<div class="w-full flex justify-center">';
              echo '<p>' . $row['ProID'] . '</p>';
              echo '</div>';
              echo '<div class="w-full flex">';
              $proName = $row['ProName'];
              if (mb_strlen($proName) > 20) {
                $proName = mb_substr($proName, 0, 20) . '...';
              }
              echo '<p>' . $proName . '</p>';
              echo '</div>';
              echo '<div class="w-full flex justify-center">';
              if ($row['StockQty'] < 5) {
                echo '<p class="text-red-500">' . $row['StockQty'] . '</p>';
              } else {
                echo '<p>' . $row['StockQty'] . '</p>';
              }
              // echo '<p>' . $row['StockQty'] . '</p>';
              echo '</div>';
              echo '</div>';
            }
            ?>
          </div>
        </div>


      </div>
    </div>
  </div>

  <?php
  $result = getOverallStatus();
  $data = [];
  $labels = [];
  $series = [];
  $colors = [];
  while ($row = $result->fetch_object()) {
    $labels[] = $row->Status;
    $series[] = $row->count;
    switch ($row->Status) {
      case 'Cancel':
        $colors[] = '#F44336'; // Red
        break;
      case 'Completed':
        $colors[] = '#4CAF50'; // Green
        break;
      case 'Ordered':
        $colors[] = '#FF9800'; // Orange
        break;
      default:
        $colors[] = '#9E9E9E'; // Grey
        break;
    }
  }
  ?>

  <script>
    var options = {
      chart: {
        type: 'donut',
        width: '450',
        height: '300'
      },
      series: <?php echo json_encode($series); ?>,
      labels: <?php echo json_encode($labels); ?>,
      colors: <?php echo json_encode($colors); ?>
    }

    var chart = new ApexCharts(document.querySelector("#piechart"), options);

    chart.render();
  </script>

</body>

</html>