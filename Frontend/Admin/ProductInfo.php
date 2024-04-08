<?php
  session_start();
  require '../../Backend/Authorized/AdminAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Admin/Product/ProductList.php';
  $pType = showProductType();
  $proName = '';
  $author = '';
  $des = '';
  $price = '';
  $cost = '';
  $stock = '';
  $pictures = '../../Pictures/Admin/book.jpg';
  $type = '';
  $status = '';
  $proID = '';
  if (isset($_POST['proID'])) {
    $product = productInfo($_POST['proID']);
    $row = $product->fetch_assoc();
    $proID = $row['ProID'];
    $proName = $row['ProName'];
    $author = $row['Author'];
    $des = $row['Description'];
    $price = $row['PricePerUnit'];
    $cost = $row['CostPerUnit'];
    $stock = $row['StockQty'];
    $pictures = $row['ImageSource'];
    $type = $row['ThaiType'];
    $status = $row['Status'];
  }
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
<body>
<div class="px-28 pb-12 flex">
  <div class="mx-auto grid max-w-2xl grid-cols-1 items-center gap-x-8 lg:max-w-7xl lg:grid-cols-2 lg:px-6">
    <div>
      <form class="max-w-md mx-auto">
        <div class="relative z-0 w-full mb-5 group">
          <p id="proID" class="hidden"><?php echo $proID;?></p>
          <p class="block text-base font-medium text-gray-900">ชื่อสินค้า</p>
          <input type="text" id="proName" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2.5" 
            placeholder="ชื่อสินค้า" value="<?php echo $proName;?>" required />
          <p id="nameError" class="text-xs font-normal text-red-600 mt-2"></p>
        </div>

        <div class="relative z-0 w-full mb-5 group">
          <p class="block text-base font-medium text-gray-900">ชื่อผู้แต่ง</p>
          <input type="text" id="author" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2" 
            placeholder="ชื่อผู้แต่ง" value="<?php echo $author;?>" required />
          <p id="authorError" class="text-xs font-normal text-red-600 mt-2"></p>
        </div>

        <div class="relative z-0 w-full mb-5 group">
          <p class="block text-base font-medium text-gray-900">รายละเอียด</p>
          <textarea id="des" rows="5" class="ps-5 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg my-2 p-3" 
            placeholder="รายละเอียดของสินค้า"> <?php echo $des;?></textarea>
        </div>

        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-5 group">
            <p class="block text-base font-medium text-gray-900">ราคาขาย (บาท / หน่วย) </p>
            <input type="text" id="price" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2" 
              placeholder="ราคาขายสินค้า" value="<?php echo $price;?>" required />
            <p id="priceError" class="text-xs font-normal text-red-600 mt-2"></p>
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <p class="block text-base font-medium text-gray-900">ราคาต้นทุน (บาท / หน่วย) </p>
            <input type="text" id="cost" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2" 
              placeholder="ต้นทุนสินค้า" value="<?php echo $cost;?>" required />
            <p id="costError" class="text-xs font-normal text-red-600 mt-2"></p>
          </div>
        </div>

        <div class="grid md:grid-cols-2 md:gap-6">
          <div class="relative z-0 w-full mb-5 group">
            <p class="block text-base font-medium text-gray-900">จำนวนสินค้า </p>
            <input type="text" id="qty" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2" 
              placeholder="จำนวนสินค้า" value="<?php echo $stock;?>" required />
            <p id="qtyError" class="text-xs font-normal text-red-600 mt-2"></p>
          </div>
          <div class="relative z-0 w-full mb-5 group">
            <p class="block text-base font-medium text-gray-900 mb-2">หมวดหมู่สินค้า </p>
            <div class="relative inline-flex">
              <svg class="w-2 h-2 absolute top-0 right-0 m-4 pointer-events-none" viewBox="0 0 412 232">
                <path d="M206 171.144L42.678 7.822c-9.763-9.763-25.592-9.763-35.355 0-9.763 9.764-9.763 25.592 0 35.355l181 181c9.763 9.763 25.592 9.763 35.355 0l181-181c9.763-9.764 9.763-25.592 0-35.355-9.763-9.763-25.592-9.763-35.355 0L206 171.144z" fill="#648299" fill-rule="nonzero"/>
              </svg>
              <select name="type" class="text-sm border border-gray-300 hover:border-gray-400 rounded-lg h-10 pl-5 pr-10 appearance-none">
            <?php if ($type != '') {?>
                <option value="<?php echo $type; ?>" selected><?php echo $type; ?> </option>
            <?php } while ($loopType = $pType->fetch_assoc()) { 
                if ($loopType['ThaiType'] != $type) { ?>
              <option value="<?php echo $loopType['TypeName'];?>"><?php echo $loopType['ThaiType'];?></option>
            <?php } } ?>
            </select>
            </div>
          </div>
        </div>

        <?php if ($proName != '') { ?>
        <p class="block text-base font-medium text-gray-900 mb-2">สถานะสินค้า </p>
        <div class="grid md:grid-cols-3 md:gap-6 text-sm mb-5">
          <label class="flex items-center ps-2 py-3 border border-gray-300 rounded cursor-pointer">
            <input type="radio" id="active" name="status" value="Active" <?php if ($status == 'Active') echo 'checked'; ?> class="mr-2">กำลังขายอยู่
          </label>
          <label class="flex items-center ps-2 py-3 border border-gray-300 rounded cursor-pointer">
            <input type="radio" id="outStock" name="status" value="OutStock" <?php if ($status == 'OutStock') echo 'checked'; ?> class="mr-2">สินค้าหมด
          </label>
          <label class="flex items-center ps-2 py-3 border border-gray-300 rounded cursor-pointer">
            <input type="radio" id="inactive" name="status" value="Inactive" <?php if ($status == 'Inactive') echo 'checked'; ?> class="mr-2">ยกเลิกการขาย
          </label>
        </div>
        <?php } ?>

        <?php if ($proName == '') { ?>
          <button type="button" onclick="validateForm()" class="text-white font-medium text-center bg-blue-600 hover:bg-blue-700 rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">เพิ่มสินค้าใหม่</button>
        <?php } else { ?>
          <button type="button" onclick="validateForm()" class="text-white font-medium text-center bg-blue-600 hover:bg-blue-700 rounded-lg text-sm w-full sm:w-auto px-5 py-2.5">แก้ไขสินค้า</button>
        <?php } ?>
      </form>
    </div>

    <div class="grid grid-cols-1 grid-rows-1 gap-4 sm:gap-6 lg:gap-8">
      <div class="flex justify-center">
        <img id="preview" src="<?php echo $pictures;?>" alt="book" class="ml-2 w-auto h-96 rounded-xl object-cover border border-gray-300">
      </div>
      <div class="relative w-full group">
        <p class="block text-base font-medium text-gray-900">เส้นทางรูปภาพ</p>
        <input type="text" id="picture" class="ps-5 text-gray-900 text-sm border border-gray-300 rounded-lg block w-full my-2 p-2.5" 
          placeholder="เส้นทางรูปภาพ เช่น <?php echo $pictures;?>" value="<?php if ($proName != '') { echo $pictures; } ?>" required oninput="updateImage()" />
        <p id="pictureError" class="text-xs font-normal text-red-600 mt-2"></p>
      </div>
    </div>
  </div>
</div>

  <script>
    function updateImage() {
      var pictureInput = document.getElementById('picture');
      var preview = document.getElementById('preview');
      preview.src = pictureInput.value;
    }

    function validateForm() {
      var proID = document.getElementById('proID').textContent;
      console.log(proID);

      const proName = document.getElementById('proName');
      const nameError = document.getElementById('nameError');

      const author = document.getElementById('author');
      const authorError = document.getElementById('authorError');

      const price = document.getElementById('price');
      const priceError = document.getElementById('priceError');

      const cost = document.getElementById('cost');
      const costError = document.getElementById('costError');

      const qty = document.getElementById('qty');
      const qtyError = document.getElementById('qtyError');

      var statusElement = document.querySelector('input[name="status"]:checked');
      var status = statusElement ? statusElement.value : "Active";
      console.log(status);

      var typeName = document.querySelector('select[name="type"]');
      var type = typeName.value;
      console.log(type);

      const picture = document.getElementById('picture');
      const pictureError = document.getElementById('pictureError');

      nameStatus = false;
      if (proName.value.trim() == '') {
        nameError.innerHTML = 'กรุณากรอกชื่อสินค้า';
        proName.style.border = '1px solid red';
      } else {
        nameError.innerHTML = '';
        proName.style.border = '1px solid green';
        nameStatus = true;
      }

      authorStatus = false;
      if (author.value.trim() == '') {
        authorError.innerHTML = 'กรุณากรอกชื่อผู้แต่ง';
        author.style.border = '1px solid red';
      } else {
        authorError.innerHTML = '';
        author.style.border = '1px solid green';
        authorStatus = true;
      }
      
      var numberRegex = /^\d+(\.\d{1,2})?$/;
      priceStatus = false;
      if (!numberRegex.test(price.value)) {
        priceError.innerHTML = 'ระบุจำนวนเต็ม / ทศนิยม 2 ตำแหน่ง';
        price.style.border = '1px solid red';
      } else if (price.value.trim() == '') {
        priceError.innerHTML = 'ระบุจำนวนเต็ม / ทศนิยม 2 ตำแหน่ง';
        price.style.border = '1px solid red';
      } else {
        priceError.innerHTML = '';
        price.style.border = '1px solid green';
        priceStatus = true;
      }
      
      costStatus = false;
      if (!numberRegex.test(cost.value)) {
        costError.innerHTML = 'ระบุจำนวนเต็ม / ทศนิยม 2 ตำแหน่ง';
        cost.style.border = '1px solid red';
      } else if (cost.value.trim() == '') {
        costError.innerHTML = 'ระบุจำนวนเต็ม / ทศนิยม 2 ตำแหน่ง';
        cost.style.border = '1px solid red';
      } else {
        costError.innerHTML = '';
        cost.style.border = '1px solid green';
        costStatus = true;
      }

      if (status == 'Active') {
        var integerRegex = /^[1-9]\d*$/;
      } else {
        var integerRegex = /^\d+$/;
      }

      qtyStatus = false;
      if (!integerRegex.test(qty.value)) {
        qtyError.innerHTML = 'ต้องเป็นจำนวนเต็มเท่านั้น';
        qty.style.border = '1px solid red';
      } else if (qty.value.trim() == '') {
        qtyError.innerHTML = 'ต้องเป็นจำนวนเต็มเท่านั้น';
        qty.style.border = '1px solid red';
      } else {
        qtyError.innerHTML = '';
        qty.style.border = '1px solid green';
        qtyStatus = true;
      }
      
      pictureStatus = false;
      if (picture.value.trim() == '') {
        pictureError.innerHTML = 'กรุณากรอกเส้นทางรูปภาพ';
        picture.style.border = '1px solid red';
      } else {
        pictureError.innerHTML = '';
        picture.style.border = '1px solid green';
        pictureStatus = true;
      }
      if (nameStatus && authorStatus && priceStatus && costStatus && qtyStatus && pictureStatus) {
        $(document).ready(function() {
          $.ajax({
            url: '../../Backend/Admin/Product/UpdateProduct.php',
            type: 'POST',
            data: {
              action: 'search',
              search: proName.value,
              id: proID
            },
            success: function(response) {
              console.log("Response from server:", response);
              if (response.trim() !== "have product") {
                console.log("No product");
                if (proID == '') {
                    console.log("Add product");
                  $.ajax({
                    url: '../../Backend/Admin/Product/UpdateProduct.php',
                    type: 'POST',
                    data: {
                      action: 'add',
                      proName: proName.value,
                      author: author.value,
                      des: des.value,
                      price: price.value,
                      cost: cost.value,
                      stock: qty.value,
                      image: picture.value,
                      type: type
                    },
                    success: function(response) {
                      <?php $_SESSION['proStatus'] = 'Active'?>
                      window.location.href = 'Product.php';
                    },
                  });
                } else {
                  $.ajax({
                    url: '../../Backend/Admin/Product/UpdateProduct.php',
                    type: 'POST',
                    data: {
                      action: 'update',
                      proName: proName.value,
                      author: author.value,
                      des: des.value,
                      price: price.value,
                      cost: cost.value,
                      stock: qty.value,
                      image: picture.value,
                      status: status,
                      id: proID
                    },
                    success: function(response) {
                      window.location.href = 'Product.php';
                    },
                  });
                }
              } else {
                nameError.innerHTML = 'ชื่อสินค้านี้มีอยู่แล้ว';
                proName.style.border = '1px solid red';
              }
            }
          });
        });
      }
    }
  </script>
</body>
</html>