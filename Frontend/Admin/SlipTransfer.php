<?php
  require '../../Backend/Authorized/AdminAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Admin/Order/ReceiptList.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
  <title>Cart Detail</title>
  <style>
    * {
      font-family: Kodchasan;
    }
  </style>
</head>
<body>
  <div class="px-28 pb-12">
    <div class='lg:max-w-7xl max-w-4xl grid items-start grid-cols-1 lg:grid-cols-2 gap-12'>
      <?php $receipt = getPayment($_POST['recID']);
        if ($row = $receipt->fetch_assoc()) {
          $payer = payerDetail($row['PayerID'])->fetch_assoc(); 
          $sum = $row['TotalPrice'] + $row['Vat'];
      ?> 
      <div>        
        <a class="block max-w-sm p-6 rounded-lg shadow-md bg-indigo-100 mb-10">
          <p class="mb-2 text-xl font-semibold tracking-tight text-black" style="letter-spacing: 1px;">ข้อมูลผู้จ่าย</p>
          <p class="font-normal text-gray-700 my-3 ps-5"><span class='font-semibold'>ผู้จ่าย: </span><?php echo "{$payer['PayerFName']} {$payer['PayerLName']}";?></p>
          <p class="font-normal text-gray-700 ps-5"><span class='font-semibold'>Tel. </span><?php echo $payer['PayerTel'];?></p>
        </a>

        <a class="block max-w-sm p-6 rounded-lg shadow-md bg-blue-100">
          <p class="mb-2 text-xl font-semibold tracking-tight text-black" style="letter-spacing: 1px;">ข้อมูลทางการเงิน</p>
          <p class="font-normal text-gray-700 mt-4 ps-5"><span class='font-semibold text-rose-700'>ยอดที่ต้องได้รับ: </span><?php echo $sum;?> บาท</p>
        </a>

        <form action="Order.php" class="my-10">
            <button type="submit" class="text-white font-medium bg-red-500 hover:bg-red-600 rounded-lg px-5 py-2.5">กลับไปหน้าคำสั่งซื้อ</button>
        </form>
      </div>
      <div>
        <p class="mb-2 text-xl font-semibold tracking-tight text-black" style="letter-spacing: 1px;">หลักฐานการชำระ</p>
        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['Payment']); ?>" class="h-96 rounded-lg mx-auto mb-14" id="silp">
      </div>
    </div>
    <?php } ?>
  </div>
</body>
</html>