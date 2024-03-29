<?php
  require '../../Backend/Authorized/UserAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  include '../../Backend/MainPage/CartDetail.php';
  $invoiceID = "กรุณากรอกหมายเลขคำสั่งซื้อ";
  if (isset($_POST['invoiceID'])) {
    $invoiceID = $_POST['invoiceID'];
  }
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
    <?php if(isset($_SESSION['success'])) { ?>
      <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100" role="alert">
        <img src="../../Pictures/info-green.png" class="w-4 h-4" alt="info" />
        <div>
          <span class="font-medium pl-3"> 
            <?php echo $_SESSION["success"]; unset($_SESSION["success"]); ?>
          </span>
        </div>
      </div>
    <?php } elseif (isset($_SESSION['error'])) { ?>
      <div class="flex items-center p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100" role="alert">
        <img src="../../Pictures/info-red.png" class="w-4 h-4" alt="info" />
        <div>
          <span class="font-medium pl-3"> 
            <?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?>
          </span>
        </div>
      </div>
    <?php } ?>
    <div class='lg:max-w-7xl max-w-4xl grid items-start grid-cols-1 lg:grid-cols-2 gap-12'>
      <div>
        <form action="../../Backend/UploadPage/Upload.php" method="POST" enctype="multipart/form-data">
          <p class="font-medium p-2">หมายเลขคำสั่งซื้อ</p>
          <input type="text" name="invoiceID" class="w-full h-12 px-3 py-2 text-sm placeholder-gray-400 border rounded-lg" placeholder="<?php echo $invoiceID?>" />
          <p class="font-normal pt-2 pl-5 text-red-500 text-sm" id="error-invoice"></p>
          <p class="font-medium pl-2 mt-8 mb-3">อัปโหลดรูปภาพการชำระเงิน</p>
          <div class="flex items-center justify-center w-full">
            <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-400 border-dashed rounded-lg cursor-pointer bg-white hover:bg-gray-100">
            <div class="flex flex-col items-center justify-center">
              <img src="../../Pictures/upload.png" class="w-12 h-12" alt="upload" />
              <p class="mb-2 text-sm text-gray-500 dark:text-gray-400"><span class="font-semibold">คลิกเพื่ออัปโหลด</span> หรือ วางไฟล์ไว้ที่นี่</p>
              <p class="text-xs text-gray-500 dark:text-gray-400">PNG หรือ JPG</p>
            </div>
            <input id="dropzone-file" name="receipt" type="file" class="hidden" />
            </label>
          </div> 
          <button type="submit" class="w-full h-12 mt-8 text-white bg-blue-500 rounded-lg">ยืนยันการชำระเงิน</button>
        </form>
      </div>
      <div style="border-left: 1px solid #ddd;">
        <p class="font-medium p-5">รูปภาพที่อัปโหลด</p>
        <img class="h-96 rounded-lg mx-auto mb-14" id="previewImg">
      </div>
    </div>
  </div>

  <script>
  let input = document.getElementById('dropzone-file');
  let previewImg = document.getElementById('previewImg');

  input.onchange = evt => {
    const [file] = input.files;
    if (file) {
      previewImg.src = URL.createObjectURL(file);
    }
  }
</script>

</body>
</html>