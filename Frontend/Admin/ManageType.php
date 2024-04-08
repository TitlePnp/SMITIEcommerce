<?php
  session_start();
  require '../../Backend/Authorized/AdminAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Admin/Product/ProductList.php';
  $type = showProductType();
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
  <div class="px-28 pb-12 flex">
    <a class="sticky top-24 flex-1 max-w-sm max-h-min p-6 bg-white border border-gray-200 rounded-lg shadow text-black mr-10">
      <p class="text-xl font-semibold tracking-tight text-black">สร้างหมวดหมู่สินค้าใหม่</p>
      <p id="error" class="text-xs font-normal text-red-600 mt-2"></p>
      <form class="max-w-sm mx-auto">
        <div class="my-5">
          <div class="flex items-center">
            <p class="block text-base font-medium text-gray-900">ชื่อภาษาไทย</p>
            <img src="../../Pictures/Admin/thailand.png" class="ml-1 h-6">
          </div>
          <input type="text" id="thaiType" class="text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2.5" placeholder="ชื่อหมวดหมู่ภาษาไทย" required />
          <p id="thaiError" class="text-xs font-normal text-red-600 mt-2"></p>
        </div>
        <div class="my-5">
          <div class="flex items-center">
            <p class="block text-base font-medium text-gray-900">ชื่อภาษาอังกฤษ</p>
            <img src="../../Pictures/Admin/america.png" class="ml-1 h-6">
          </div>
          <input type="text" id="typeName" class="text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2.5" placeholder="ชื่อหมวดหมู่อังกฤษ" required />
          <p id="typeError" class="text-xs font-normal text-red-600 mt-2"></p>
        </div>
        <button type="button" onclick="validateForm()" class="text-white font-medium text-center bg-blue-600 hover:bg-blue-700  rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">สร้างหมวดหมู่สินค้า</button>
      </form>
    </a>


    <a class="flex-2 w-8/12 p-6 bg-white border border-gray-200 rounded-lg text-black shadow">
      <p class="text-xl font-semibold tracking-tight text-black">หมวดหมู่สินค้า</p>
      <?php while ($row = $type->fetch_assoc()) { ?>
      <div class="mt-4 p-3 bg-white border border-gray-200 rounded-lg shadow">
        <div class="flex items-center justify-between">
          <p class="block text-sm font-medium text-gray-900"><?php echo "{$row['ThaiType']} / {$row['TypeName']}"?></p>
          <div class="flex">
            <img src="../../Pictures/Admin/pen.png" class="mx-2 h-6" onClick="toggleInputFields(this)">
            <img src="../../Pictures/Admin/trash.png" class="mx-2 h-6" onClick="deleteType(<?php echo $row['TypeID']?>)">
          </div>
        </div>
        <!-- when click pen -->
        <div class="additional-fields" style="display: none;">
          <div class="flex items-center justify-between">
            <input type="text" id="hideThai<?php echo $row['TypeID']?>" class="text-gray-900 text-sm border border-gray-300 rounded-lg block w-9/12 mt-4 mb-2 mx-5 p-1.5" placeholder="ชื่อหมวดหมู่ภาษาไทย" value="<?php echo $row['ThaiType']?>" required />
            <button type="button" onclick="checkThai(<?php echo $row['TypeID']?>)" class="text-white font-medium text-center bg-blue-600 hover:bg-blue-700 rounded-lg text-sm w-full sm:w-auto px-5 py-1.5 mx-5">ตกลง</button>
          </div>
          <p id="hideThaiError<?php echo $row['TypeID']?>" class="text-xs font-normal text-red-600 mx-8 mb-2"></p>

          <div class="flex items-center justify-between">
            <input type="text" id="hideType<?php echo $row['TypeID']?>" class="text-gray-900 text-sm border border-gray-300 rounded-lg block w-9/12 mb-2 mx-5 p-1.5" placeholder="ชื่อหมวดหมู่ภาษาไทย" value="<?php echo $row['TypeName']?>" required />
            <button type="button" onclick="checkType(<?php echo $row['TypeID']?>)" class="text-white font-medium text-center bg-blue-600 hover:bg-blue-700 rounded-lg text-sm w-full sm:w-auto px-5 py-1.5 mx-5">ตกลง</button>
          </div>
          <p id="hideTypeError<?php echo $row['TypeID']?>" class="text-xs font-normal text-red-600 mx-8 mb-2"></p>
        </div>
      </div>
      <?php } ?>
    </a>
  </div>
  <script>
    function validateForm() {
      const thaiType = document.getElementById('thaiType');
      const thaiError = document.getElementById('thaiError');

      const typeName = document.getElementById('typeName');
      const typeError = document.getElementById('typeError');

      const error = document.getElementById('error');
      thaiStatus = false;
      var thaiRegex = /^[\u0E00-\u0E7F]+$/;
      console.log(thaiRegex.test(thaiType.value));
      if (!thaiRegex.test(thaiType.value)) {
        thaiError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        thaiType.style.border = '1px solid red';
      } else if (thaiType.value.trim() == '') {
        thaiError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        thaiType.style.border = '1px solid red';
      } else {
        thaiError.innerHTML = '';
        thaiType.style.border = '1px solid green';
        thaiStatus = true;
      }

      typeStatus = false;
      var englishRegex = /^[A-Za-z\s]+$/;
      console.log(englishRegex.test(typeName.value));
      if (!englishRegex.test(typeName.value)) {
        console.log("Check Type Error");
        typeError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาอังกฤษเท่านั้น';
        typeName.style.border = '1px solid red';
      } else if (typeName.value.trim() == '') {
        console.log("Check Type Empty");
        typeError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาอังกฤษเท่านั้น';
        typeName.style.border = '1px solid red';
      } else {
        console.log("Check Type Success");
        typeError.innerHTML = '';
        typeName.style.border = '1px solid green';
        typeStatus = true;
      }

      if (thaiStatus && typeStatus) {
        $(document).ready(function() {
          $.ajax({
            url: '../../Backend/Admin/Product/CheckType.php',
            type: 'POST',
            data: {
              check: 'both',
              thaiType: thaiType.value,
              typeName: typeName.value
            },
            success: function(response) {
              console.log("Response from server:", response);
              if (response.trim() !== "have type") {
                $.ajax({
                  url: '../../Backend/Admin/Product/UpdateType.php',
                  type: 'POST',
                  data: {
                    check: 'both',
                    thaiType: thaiType.value,
                    typeName: typeName.value
                  },
                  success: function(response) {
                    location.reload();
                  }
                });
              } else {
                error.innerHTML = 'ชื่อหมวดหมู่นี้สินค้ามีการใช้งานแล้ว โปรดตรวจสอบอีกครั้ง';
                thaiType.style.border = '1px solid red';
                typeName.style.border = '1px solid red';
              }
            }
          });
        });
      }
    }
    
    function toggleInputFields(button) {
      $(button).closest('.mt-4').find('.additional-fields').toggle();
    }

    function checkThai(typeID) {
      console.log(typeID);
      const hideThai = document.getElementById('hideThai' + typeID);
      const hideThaiError = document.getElementById('hideThaiError' + typeID);

      var thaiRegex = /^[\u0E00-\u0E7F]+$/;
      console.log(thaiRegex.test(hideThai.value));
      hideThaiStatus = false;
      if (!thaiRegex.test(hideThai.value)) {
        console.log("Check Thai Error");
        hideThaiError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        hideThai.style.border = '1px solid red';
      } else if (hideThai.value.trim() == '') {
        console.log("Check Thai Empty");
        hideThaiError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        hideThai.style.border = '1px solid red';
      } else {
        console.log("Check Thai Success");
        hideThaiError.innerHTML = '';
        hideThai.style.border = '1px solid green';
        hideThaiStatus = true;
      }

      if (hideThaiStatus) {
        $(document).ready(function() {
          console.log("Check Thai Ajax");
          $.ajax({
            url: '../../Backend/Admin/Product/CheckType.php',
            type: 'POST',
            data: {
              check: 'thai',
              typeID: typeID,
              thaiType: hideThai.value
            },
            success: function(response) {
              console.log("Response from server:", response);
              if (response.trim() !== "have type") {
                $.ajax({
                  url: '../../Backend/Admin/Product/UpdateType.php',
                  type: 'POST',
                  data: {
                    check: 'thai',
                    typeID: typeID,
                    thaiType: hideThai.value
                  },
                  success: function(response) {
                    location.reload();
                  }
                });
              } else {
                hideThaiError.innerHTML = 'ชื่อหมวดหมู่นี้สินค้ามีการใช้งานแล้ว โปรดตรวจสอบอีกครั้ง';
                hideThai.style.border = '1px solid red';
              }
            }
          });
        });
      }
    }

    function checkType(typeID) {
      console.log(typeID);
      const hideType = document.getElementById('hideType' + typeID);
      const hideTypeError = document.getElementById('hideTypeError' + typeID);

      var englishRegex = /^[A-Za-z\s]+$/;
      hideTypeStatus = false;
      if (!englishRegex.test(hideType.value)) {
        console.log("Check Thai Error");
        hideTypeError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        hideType.style.border = '1px solid red';
      } else if (hideType.value.trim() == '') {
        console.log("Check Thai Empty");
        hideTypeError.innerHTML = 'กรอกข้อมูลเฉพาะภาษาไทยเท่านั้น';
        hideType.style.border = '1px solid red';
      } else {
        console.log("Check Thai Success");
        hideTypeError.innerHTML = '';
        hideType.style.border = '1px solid green';
        hideTypeStatus = true;
      }

      if (hideTypeStatus) {
        $(document).ready(function() {
          console.log("Check Thai Ajax");
          $.ajax({
            url: '../../Backend/Admin/Product/CheckType.php',
            type: 'POST',
            data: {
              check: 'eng',
              typeID: typeID,
              typeName: hideType.value
            },
            success: function(response) {
              console.log("Response from server:", response);
              if (response.trim() !== "have type") {
                $.ajax({
                  url: '../../Backend/Admin/Product/UpdateType.php',
                  type: 'POST',
                  data: {
                    check: 'eng',
                    typeID: typeID,
                    typeName: hideType.value
                  },
                  success: function(response) {
                    location.reload();
                  }
                });
              } else {
                hideTypeError.innerHTML = 'ชื่อหมวดหมู่นี้สินค้ามีการใช้งานแล้ว โปรดตรวจสอบอีกครั้ง';
                hideType.style.border = '1px solid red';
              }
            }
          });
        });
      }
    }

    function deleteType(typeID) {
      $(document).ready(function() {
        $.ajax({
          url: '../../Backend/Admin/Product/UpdateType.php',
          type: 'POST',
          data: {
            check: 'delete',
            typeID: typeID
          },
          success: function(response) {
            location.reload();
          }
        });
      });
    }
  </script>
</body>
</html>