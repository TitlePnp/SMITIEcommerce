<?php
  session_start();
  require '../../Backend/Authorized/AdminAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Admin/Order/ReceiptList.php';
  require '../../Backend/Admin/Order/Search.php';
  if (isset($_SESSION['status'])) {
    $status = $_SESSION['status'];
    $status2 = $_SESSION['status2'];
  } else {
    $status = "Pending";
    $status2 = "";
  }

  if (isset($_POST['start_date']) && isset($_POST['end_date'])) {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
  }
  $statusT = ["ชำระเงิน แล้วรอการตรวจสอบ", "รอการจัดส่ง", "เก็บเงินปลายทาง รอการจัดส่ง", "จัดส่ง", "เสร็จสิ้น", "สินค้าถูกส่งคืน", "ยกเลิก"];
  $statusE = ["Pending", "Paid", "COD", "Delivered", "Completed", "Returned", "Cancel"];
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link type="text/css" rel="stylesheet" href="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.css">
  <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/gh/alumuko/vanilla-datetimerange-picker@latest/dist/vanilla-datetimerange-picker.js"></script>

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
<body>
  <div class="px-20 pb-12">
    <div class="flex flex-wrap justify-center items-center mb-5">
      <button type="button" value="Pending" class="head m-3 w-full sm:w-auto bg-white hover:bg-gray-400 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5">
        <img src="../../Pictures/Admin/salary.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">ชำระเงินแล้ว รอการตรวจสอบ</p>
      </button>

      <button type="button" value="Paid" class="head m-3 w-full sm:w-auto bg-white hover:bg-gray-400 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5">
        <img src="../../Pictures/Admin/pending-box.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">รอการจัดส่ง</p>
      </button>

      <button type="button" value="COD" class="head m-3 w-full sm:w-auto bg-white hover:bg-gray-400 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5">
        <img src="../../Pictures/Admin/express-delivery.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">เก็บเงินปลายทาง รอการจัดส่ง</p>
      </button>

      <button type="button" value="Delivered" class="head m-3 w-full sm:w-auto bg-white hover:bg-gray-400 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5">
        <img src="../../Pictures/Admin/truck.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">จัดส่งแล้ว</p>
        </button>

      <button type="button" value="Returned" class="head m-3 w-full sm:w-auto bg-white hover:bg-gray-400 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5">
        <img src="../../Pictures/Admin/cancelled.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">สินค้าถูกส่งคืน / ยกเลิก</p>
      </button>
    </div>

    <div class="flex flex-col sm:flex-row my-6">
      <div class="flex flex-col mr-10">
        <p class="font-medium my-2">ค้นหา</p>
        <form style="width: 100%" action="OrderOnSearch.php" method="post">
          <div class="relative">
            <input type="text" class="text-sm w-full placeholder:italic bg-white border rounded-md py-2 px-16 ps-3"
              placeholder="เลขที่ใบเสร็จ, ชื่อลูกค้า" name="search" required/>
            <input type="hidden" name="status" value="ok">
            <button type="submit" class="absolute right-0 top-0 bottom-0 bg-gray-400 text-white hover:bg-gray-500 rounded-r-md text-sm px-5 py-2">
              <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
            </button>
          </div>
        </form>
      </div>
        
      <div class="flex flex-col mr-10">
        <form style="width: 100%" method="post">
          <p class="font-medium my-2">ช่วงวันที่</p>
          <div date-rangepicker class="flex items-center">
            <div class="relative" style="width: 90%">
              <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                </svg>
              </div>
              <input id="datetimerange-input1" type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg block w-full ps-10 py-2" placeholder="วันที่เริ่มต้น" required>
              <input type="hidden" id="start_date" name="start_date">
              <input type="hidden" id="end_date" name="end_date">
            </div>
            <button type="submit" class="text-black bg-gray-400 hover:bg-gray-500 font-medium rounded-lg text-sm px-5 py-2 ml-3">
              <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
            </button>
          </div>
        </form>
      </div>

      <div class="flex flex-col">
        <form style="width: 100%" action="Order.php" method="post">
          <p class="font-medium my-2">ยกเลิก</p>
          <button type="submit" class="text-black bg-red-500 hover:bg-red-600 font-medium rounded-lg text-sm px-5 py-2">
            <img class="h-5 w-auto" src="../../Pictures/Admin/close.png" alt="search">
          </button>
        </form>
      </div>
    </div>

    <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100" role="alert">
      <img src="../../Pictures/info-green.png" class="w-4 h-4" alt="info" />
      <div>
        <span class="font-medium pl-3"> 
          <?php echo "ช่วงวันที่: {$_POST['start_date']} ถึง {$_POST['end_date']}" ?>
        </span>
      </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-md">
      <table class="w-full text-sm text-black">
        <thead class="text-sm bg-gray-100">
          <tr>
            <th scope="col" class="pl-3" style="letter-spacing: 0.1em;">เลขที่ใบเสร็จ</th>
            <th scope="col" style="letter-spacing: 0.1em;">วันที่ออกใบเสร็จ</th>
            <th scope="col" style="letter-spacing: 0.1em;">ลูกค้า</th>
            <th scope="col" style="letter-spacing: 0.1em;">ราคา</th>
            <th scope="col" style="letter-spacing: 0.1em;">การชำระเงิน</th>
            <th scope="col" style="letter-spacing: 0.1em;">สถานะ</th>
            <th scope="col" style="letter-spacing: 0.1em;">อัพเดทสถานะ</th>
            <th scope="col"></th>
            <th scope="col" class="p-4 border-r">
              <div class="flex items-center">
                <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600">
                <label for="checkbox-all" class="sr-only">checkbox</label>
              </div>
            </th>
            </tr>
        </thead>
        <tbody>
      <?php $receipt = searchByDate($start_date, $end_date, $status, $status2);
          while ($row = $receipt->fetch_assoc()) {
            $sum = $row['TotalPrice'] + $row['Vat'];
            $cus = cusDetail($row['CusID'])->fetch_assoc();
            $payer = payerDetail($row['PayerID'])->fetch_assoc();
            $recvID = getRecvID($row['InvoiceID'])->fetch_assoc();
      ?>
            <tr class="odd:bg-white even:bg-gray-50 border-b text-center">
              <th scope="row" class="px-3 py-4 font-semibold text-gray-900 whitespace-nowrap"><?php echo $row['RecID'];?></th>
              <td class="px-3 py-4"><?php echo $row['PayTime'];?></td>
      <?php if ($row['Channel'] == "Transfer" && $row['CusID'] != 1) {
              echo "<td class='px-3 py-4 text-left text-sm'>";
                echo "<p><span class='font-semibold'>ลูกค้า: </span>" . $cus['CusFName'] . " " . $cus['CusLName'] . "</p>";
                echo "<p><span class='font-semibold'>Tel. </span>" . $cus['Tel'] . "</p><br />";
                echo "<p><span class='font-semibold'>ผู้จ่าย: </span>" . $payer['PayerFName'] . " " . $payer['PayerLName'] . "</p>";
                echo "<p><span class='font-semibold'>Tel. </span>" . $payer['PayerTel'] . "</p>";
              echo "</td>";
      } else if ($row['Channel'] == "Transfer" && $row['CusID'] == 1) {
              echo "<td class='px-3 py-4 text-left text-sm'>";
                echo "<p><span class='font-semibold'>ผู้จ่าย: </span>" . $payer['PayerFName'] . " " . $payer['PayerLName'] . "</p>";
                echo "<p><span class='font-semibold'>Tel. </span>" . $payer['PayerTel'] . "</p>";
              echo "</td>";
      } else if ($row['Channel'] == "COD" && $row['CusID'] != 1) {
              echo "<td class='px-3 py-4 text-left text-sm'>";
                echo "<p><span class='font-semibold'>ลูกค้า: </span>" . $cus['CusFName'] . " " . $cus['CusLName'] . "</p>";
                echo "<p><span class='font-semibold'>Tel. </span>" . $cus['Tel'] . "</p>";
              echo "</td>";
      } else if ($row['Channel'] == "COD" && $row['CusID'] == 1) {
              echo "<td class='px-3 py-4 text-left text-sm'>";
                echo "<p><span class='font-semibold'>ผู้สั่ง: </span>" . $payer['PayerFName'] . " " . $payer['PayerLName'] . "</p>";
                echo "<p><span class='font-semibold'>Tel. </span>" . $payer['PayerTel'] . "</p>";
              echo "</td>";
      }?>

              <td class="px-3 py-4"><?php echo $sum;?></td>

      <?php if ($row['Channel'] == "Transfer") { ?>
              <td class="px-3 py-4">
                <div class="flex flex-col justify-center items-center">
                  <img class="h-6 w-auto my-2" src="../../Pictures/Admin/transfer.png" alt="transfer">
                  <form method="post" action="SlipTransfer.php" id="check-transfer-form">
                    <input type="hidden" name="recID" value="<?php echo $row['RecID'];?>">
                    <button type="submit" class="check-transfer text-xs font-medium text-blue-500 hover:underline">ตรวจสอบการโอนเงิน</button>
                  </form>
                </div>
              </td>
      <?php } else if ($row['Channel'] == "COD") { ?>
              <td class="px-3 py-4">
                <div class="flex flex-col justify-center items-center">
                  <img class="h-6 w-auto my-2" src="../../Pictures/Admin/truck.png" alt="COD">
                  <p class="text-xs font-medium text-blue-500">เก็บเงินปลายทาง</p>
                </div>
              </td>
      <?php } $count = 0;
            foreach ($statusE as $i) {
              if ($i == $status) { 
                $_SESSION['status'] = $i; ?>
                <td class="px-3 py-4">
                  <div class="flex justify-center text-center">
                    <p class="max-w-20 text-xs font-medium text-white rounded-lg bg-blue-500 px-2 py-2"><?php echo $statusT[$count];?></p>
                  </div>
                </td>

                <?php if ("Returned" == $status or "Cancel" == $status) { ?>
                <td class="px-3 py-4"></td>
                <?php } else if ("Paid" == $status) {?>
                <td class="px-3 py-4">
                  <button type="button" data-status="<?php $deli = 3; echo $statusE[$deli];?>" data-recID="<?php echo $row['RecID'];?>" class="update-status text-xs font-medium text-black rounded-lg bg-gray-100 border border-gray-300 hover:bg-gray-400 px-2 py-2">
                    <?php echo $statusT[$deli];?>
                  </button>
                </td>
                <?php } else { ?>
                <td class="px-3 py-4">
                  <button type="button" data-status="<?php echo $statusE[++$count];?>" data-recID="<?php echo $row['RecID'];?>" class="update-status text-xs font-medium text-black rounded-lg bg-gray-100 border border-gray-300 hover:bg-gray-400 px-2 py-2">
                    <?php echo $statusT[$count];?>
                  </button>
                </td>
        <?php } } else if ("DI" == $status) { 
                $_SESSION['status'] = "Delivered"; 
                $_SESSION['status2'] = "DI"; ?>
                <td class="px-3 py-4">
                  <div classs=" flex justify-center">
                    <p class="text-xs font-medium text-white rounded-lg bg-blue-500 px-2 py-2"><?php $index = 4; echo $statusT[$index];?></p>
                  </div>
                </td>
                <td class="px-3 py-4">
                  <button type="button" data-status="<?php echo $statusE[++$index];?>" data-recID="<?php echo $row['RecID'];?>" class="text-xs font-medium text-black rounded-lg bg-gray-100 border border-gray-300 hover:bg-gray-400 px-2 py-2">
                    <?php echo $statusT[$index];?>
                  </button>
                </td>
        <?php  } $count++; } /* end foreach */?>
              <td>
                <button type="button"><img class="h-6 w-auto" src="../../Pictures/Admin/search-normal.png" alt="search"></button>
                <button type="button"><img class="h-6 w-auto" src="../../Pictures/Admin/printer.png" alt="print"></button>
        <?php if ("Returned" == $status or "Cancel" == $status) { ?>
                <button type="button"><img class="h-6 w-auto" src="../../Pictures/Admin/pen.png" alt="edit"></button>
        <?php } ?>
              </td>
              <td class='px-3 py-4'>
                <div class='flex items-center'>
                  <input id='checkbox-product' type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded'>
                  <label for='checkbox-product' class='sr-only'>checkbox</label>
                </div>
              </td>
            </tr>
          <?php } /* end whileloop */?>
        </tbody>
      </table>
    </div>
  </div>
  <?php 
  ?>
  <script>
    var status = "<?php echo $status; ?>";
    $(document).ready(function() {
      $('.head').each(function() {
        if ($(this).val() === status) {
          $(this).removeClass('bg-white');
          $(this).addClass('bg-gray-400');
        }
      });
      $('.head').click(function() {
        var status = $(this).val();
        var status2 = "";
        if (status === "Delivered") {
          status = "Delivered";
          status2 = "DI";
        } else if (status === "Returned") {
          status = "Returned";
          status2 = "Cancel";
        }
        $.ajax({
          url: 'Order.php',
          type: 'POST',
          data: {
            status: status,
            status2: status2
          },
          success: function(data) {
            window.location = 'Order.php';
          }
        });
      });
    });

    window.addEventListener("load", function (event) {
      var tomorrow = new Date();
      tomorrow.setDate(tomorrow.getDate() + 1);
      var minDate = new Date('2023-07-01');
      new DateRangePicker('datetimerange-input1', {
        minDate: minDate,
        maxDate: tomorrow,
      });
    });

    window.addEventListener('apply.daterangepicker', function (ev) {
      var startDate = ev.detail.startDate.format('YYYY-MM-DD');
      var endDate = ev.detail.endDate.format('YYYY-MM-DD');
      document.getElementById('start_date').value = startDate;
      document.getElementById('end_date').value = endDate;
    });


    $(document).ready(function(){
      $(".update-status").click(function(){
        console.log($(this)); 
        var status = $(this).data('status');
        var recID = $(this).data('recid');
        console.log(status);
        console.log(recID);
        $.ajax({
          url: '../../Backend/Admin/Order/UpdateStatus.php',
          type: 'post',
          data: {
            status: status,
            recID: recID
          },
          success: function(response) {
            window.location = 'Order.php';
          }
        });
      });
    });
  </script>
</body>
</html>