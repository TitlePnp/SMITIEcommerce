<?php
  require '../../Backend/Authorized/NoGuestAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Profile/GetInfo.php';
  $info = getInfo($id);
  while ($row = $info->fetch_assoc()) {
    $fName = $row['CusFName'];
    $lName = $row['CusLName'];
    $sex = $row['Sex'];
    $phone = $row['Tel'];
    $province = $row['Province'];
    $postcode = $row['Postcode'];

    $address = $row['Address'];
    $indexDist = strpos($address, "ตำบล/แขวง");
    $indexSubdist = strpos($address, "อำเภอ/เขต");
    $district = trim(substr($address, $indexDist, $indexSubdist - $indexDist));
    $district = str_replace("ตำบล/แขวง", "", $district);
    $subdistrict = trim(substr($address, $indexSubdist));
    $subdistrict = str_replace("อำเภอ/เขต", "", $subdistrict);
    $address = trim(substr($address, 0, $indexDist));
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
      <input type="hidden" name="id" id="id" value="<?php echo $id;?>">
      <h1 class="leading-7 mb-1 font-semibold">ข้อมูลส่วนตัวของฉัน</h1>
      <p class="text-sm/[17px] leading-6 text-gray-600 pl-5 pb-2">ยินดีต้อนรับคุณ <strong class="text-base"><?php echo getUserName(); ?></strong>! โปรดระบุข้อมูลของคุณ <span style="color:red; text-decoration: underline;">คุณสามารถยกเลิก หรือ กรอกข้อมูลภายหลังได้</span></p>
      <hr class="h-1 bg-gray-200 border-0 rounded mb-5">
      <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
          <p class="font-medium">ชื่อ</p>
          <input type="text" id="fName" name="firstName" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกชื่อของคุณ" value="<?php echo $fName;?>" required>
          <p id="fNameError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">นามสกุล</p>
          <input type="text" id="lName" name="lastName" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกนามสกุลของคุณ" value="<?php echo $lName;?>" required>
          <p id="lNameError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>

        <div class="sm:col-span-3">
          <p class="font-medium mb-2">เพศ</p>
          <div class="flex whitespace-nowrap">
            <div class="flex items-center me-4">
              <input id="radio" type="radio" value="M" name="sex" class="w-6 h-4" <?php echo $sex == 'M' ? 'checked' : ''; ?>>
              <label for="radio" class="mx-2 text-sm">ชาย</label>
            </div>
            <div class="flex items-center me-4">
              <input id="radio-2" type="radio" value="F" name="sex" class="w-6 h-4" <?php echo $sex == 'F' ? 'checked' : ''; ?>>
              <label for="radio-2" class="mx-2 text-sm">หญิง</label>
            </div>
            <div class="flex items-center me-4">
              <input id="radio-3" type="radio" value="" name="sex" class="w-6 h-4" <?php echo $sex == '' ? 'checked' : ''; ?> required>
              <label for="radio-3" class="mx-2 text-sm">ไม่ระบุ</label>
            </div>
          </div>
          <p id="sexError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">เบอร์โทรศัพท์</p>
          <input type="text" id="phone" name="phone" pattern="0[0-9]{9}" title="เบอร์โทรศัพท์ของคุณไม่ถูกต้อง" class="w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกเบอร์โทรศัพท์ของคุณ" value="<?php echo $phone;?>" required>
          <p id="phoneError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
      </div>

      <p class="font-medium mt-5 mb">ที่อยู่</p>
      <div class="mt-2">
        <input type="text" id="address" name="address" class="mb-5 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกที่อยู่ของคุณ" value="<?php echo $address;?>" required>
        <p id="addressError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
      </div>

      <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
        <div class="sm:col-span-3">
          <p class="font-medium">ตำบล / แขวง</p>
          <input type="text" id="district" name="district" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกตำบล / แขวง" value="<?php echo $district;?>" required>
          <p id="distError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">อำเภอ / เขต</p>
          <input type="text" id="subdistrict" name="subdistrict" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกอำเภอ / เขต" value="<?php echo $subdistrict;?>" required>
          <p id="subdisError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>

        <div class="sm:col-span-3">
          <p class="font-medium">จังหวัด</p>
          <input type="text" id="province" name="province" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกจังหวัด" value="<?php echo $province;?>"required>
          <p id="provinceError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
        <div class="sm:col-span-3">
          <p class="font-medium">รหัสไปรษณีย์</p>
          <input type="text" id="postcode" name="postcode" class="mt-2 w-full h-9 px-3 py-2 text-sm placeholder-gray-400 border border-gray-300 shadow-sm rounded-lg" placeholder="กรุณากรอกรหัสไปรษณีย์" value="<?php echo $postcode;?>" required>
          <p id="postError" class="text-sm/[17px] font-normal text-red-600 mt-1"></p>
        </div>
      </div>

      <hr class="h-1 bg-gray-200 border-0 rounded mt-10">
      <div class="mt-6 flex justify-between">
        <button type="button" class="bg-red-500 hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded" id="later">ยกเลิก</button>
        <button type="button" onclick="validateForm()" class="bg-green-500 hover:bg-green-600 text-white text-base font-normal py-2 px-4 rounded" id="save">บันทึกข้อมูล</button>
      </div>
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
      window.location.href = '../MainPage/Home.php';
    });

    const cusID = document.getElementById('id');
    const fName = document.getElementById('fName');
    const fNameError = document.getElementById('fNameError');

    const lName = document.getElementById('lName');
    const lNameError = document.getElementById('lNameError');

    const radio = document.getElementById('radio');
    const radio2 = document.getElementById('radio-2');
    const radio3 = document.getElementById('radio-3');
    const sexError = document.getElementById('sexError');

    const phone = document.getElementById('phone');
    const phoneError = document.getElementById('phoneError');

    const address = document.getElementById('address');
    const addressError = document.getElementById('addressError');

    const district = document.getElementById('district');
    const distError = document.getElementById('distError');

    const subdistrict = document.getElementById('subdistrict');
    const subdisError = document.getElementById('subdisError');

    const province = document.getElementById('province');
    const provinceError = document.getElementById('provinceError');

    const postcode = document.getElementById('postcode');
    const postError = document.getElementById('postError');
    
    function validateForm() {
      fNameStatus = false;
      if (fName.value == '') {
        fNameError.innerHTML = 'กรุณากรอกชื่อของคุณ';
        fName.style.border = '1px solid red';
      } else {
        fNameError.innerHTML = '';
        fName.style.border = '1px solid green';
        fNameStatus = true;
      }

      lNameStatus = false;
      if (lName.value == '') {
        lNameError.innerHTML = 'กรุณากรอกนามสกุลของคุณ';
        lName.style.border = '1px solid red';
      } else {
        lNameError.innerHTML = '';
        lName.style.border = '1px solid green';
        lNameStatus = true;
      }

      sexStatus = false;
      if (radio.checked == false && radio2.checked == false && radio3.checked == false) {
        sexError.innerHTML = 'กรุณาเลือกเพศของคุณ';
      } else {
        sexStatus = true;
      }
      
      phoneStatus = false;
      if (phone.value == '') {
        phoneError.innerHTML = 'กรุณากรอกเบอร์โทรศัพท์ของคุณ';
        phone.style.border = '1px solid red';
      } else {
        phoneError.innerHTML = '';
        phone.style.border = '1px solid green';
        phoneStatus = true;
      }

      addressStatus = false;
      if (address.value == '') {
        addressError.innerHTML = 'กรุณากรอกที่อยู่ของคุณ';
        address.style.border = '1px solid red';
      } else {
        addressError.innerHTML = '';
        address.style.border = '1px solid green';
        addressStatus = true;
      }

      distStatus = false;
      if (district.value == '') {
        distError.innerHTML = 'กรุณากรอกตำบล / แขวง';
        district.style.border = '1px solid red';
      } else {
        distError.innerHTML = '';
        district.style.border = '1px solid green';
        distStatus = true;
      }

      subdisStatus = false;
      if (subdistrict.value == '') {
        subdisError.innerHTML = 'กรุณากรอกอำเภอ / เขต';
        subdistrict.style.border = '1px solid red';
      } else {
        subdisError.innerHTML = '';
        subdistrict.style.border = '1px solid green';
        subdisStatus = true;
      }

      provinceStatus = false;
      if (province.value == '') {
        provinceError.innerHTML = 'กรุณากรอกจังหวัด';
        province.style.border = '1px solid red';
      } else {
        provinceError.innerHTML = '';
        province.style.border = '1px solid green';
        provinceStatus = true;
      }

      postStatus = false;

      if (postcode.value == '') {
        postError.innerHTML = 'กรุณากรอกรหัสไปรษณีย์';
        postcode.style.border = '1px solid red';
      } else {
        postError.innerHTML = '';
        postcode.style.border = '1px solid green';
        postStatus = true;
      }

      const sexRadio = document.querySelector('input[name="sex"]:checked');
      const sex = sexRadio.value;
      if (fNameStatus && lNameStatus && sexStatus && phoneStatus && addressStatus && distStatus && subdisStatus && provinceStatus && postStatus) {
        $.ajax({
          type: 'POST',
          url: '../../Backend/Profile/InsertInfo.php',
          data: {
            id: cusID.value,
            firstName: fName.value,
            lastName: lName.value,
            sex: sex,
            phone: phone.value,
            address: address.value,
            district: district.value,
            subdistrict: subdistrict.value,
            province: province.value,
            postcode: postcode.value
          },
          success: function(response) {
            window.location.href = '../../Frontend/MainPage/Home.php';
          }
        });
      }
    }
  </script>
</body>
</html>