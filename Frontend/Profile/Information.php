<?php
  require '../../Backend/Authorized/NoGuestAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
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
  <title>Information</title>
  <!-- JQYERY THAILAND.js -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
  <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
  <link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
  <script type="text/javascript" src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
  <style>
    * {
      font-family: Kodchasan;
    }
  </style>
</head>
<body>
  <div class="px-28 pb-12 text-base">
    <form action="../../Backend/Profile/InsertInfo.php" method="POST">
      <input type="hidden" name="id" value="<?php echo $id; ?>">
      <h2 class="leading-7 mb-1">ข้อมูลส่วนตัวของฉัน</h2>
      <p class="text-sm/[17px] leading-6 text-gray-600 pl-5 pb-2">ยินดีต้อนรับคุณ <?php echo getUserName(); ?>! โปรดระบุข้อมูลของคุณ คุณสามารถยกเลิก หรือ กรอกข้อมูลภายหลังได้</p>
      <hr class="h-1 bg-gray-200 border-0 rounded mb-5">
      <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
          <p class="font-medium">ชื่อ</p>
          <input type="text" name="first-name" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกชื่อของคุณ" required>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">นามสกุล</p>
          <input type="text" name="last-name" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกนามสกุลของคุณ" required>
        </div>

        <div class="sm:col-span-3">
          <p class="font-medium mb-2">เพศ</p>
          <div class="flex whitespace-nowrap">
            <div class="flex items-center me-4">
              <input id="radio" type="radio" value="M" name="sex" class="w-6 h-4">
              <label for="radio" class="mx-2 text-sm">ชาย</label>
            </div>
            <div class="flex items-center me-4">
              <input id="radio-2" type="radio" value="F" name="sex" class="w-6 h-4">
              <label for="radio-2" class="mx-2 text-sm">หญิง</label>
            </div>
            <div class="flex items-center me-4">
              <input id="radio-3" type="radio" value="" name="sex" class="w-6 h-4" required>
              <label for="radio-3" class="mx-2 text-sm">ไม่ระบุ</label>
            </div>
          </div>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">เบอร์โทรศัพท์</p>
          <input type="text" name="phone" pattern="0[0-9]{9}" title="เบอร์โทรศัพท์ของคุณไม่ถูกต้อง" class="w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกเบอร์โทรศัพท์ของคุณ" required>
        </div>
      </div>

      <p class="font-medium mt-5 mb">ที่อยู่</p>
      <div class="mt-2">
        <input type="text" name="address" class="mb-5 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกที่อยู่ของคุณ" required>
      </div>

      <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
          <p class="font-medium">ตำบล / แขวง</p>
          <input type="text" id="district" name="district" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกตำบล / แขวง" required>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">อำเภอ / เขต</p>
          <input type="text" id="subdistrict" name="subdistrict" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกอำเภอ / เขต" required>
        </div>

        <div class="sm:col-span-3">
          <p class="font-medium">จังหวัด</p>
          <input type="text" id="province" name="province" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกจังหวัด" required>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">รหัสไปรษณีย์</p>
          <input type="text" id="postcode" name="postcode" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกรหัสไปรษณีย์" required>
        </div>
      </div>

      <hr class="h-1 bg-gray-200 border-0 rounded mt-10">
      <div class="mt-6 flex justify-between">
        <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded" id="later">ยกเลิก</button>
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-base font-normal py-2 px-4 rounded" id="save">บันทึกข้อมูล</button>
      </div>
    </form>
  </div>
  <script>
    $.Thailand({
      autocomplete_size: 5,
      $district: $('#district'),
      $amphoe: $('#subdistrict'),
      $province: $('#province'),
      $zipcode: $('#postcode'),
    });
    
    document.getElementById('later').addEventListener('click', function() {
      window.location.href = 'Test.html';
    });
  </script>
</body>
</html>