<?php
  require '../../Backend/Authorized/UserAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  include '../../Backend/ProductQuery/ProductDetail.php';
  /* รับข้อมูล */
  $proID = $_POST['proID'];
  $type = $_POST['typeName'];

  $stockOnOrder = sumProductOnOrder($proID);
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
  <title>Book Detail</title>
  <style>
    * {
      font-family: Kodchasan;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    input[type="number"] {
      -moz-appearance: textfield;
    }
  </style>
</head>
<body>
  <!-- กำหนดระยะห่างขอบข้าง + ขอบล่าง -->
  <div class='px-28 pb-12'>
  <?php
    /* Detail Product */
    $mainProduct = mainProduct($proID);
    while ($row = $mainProduct->fetch_assoc()) { ?>
      <div class='lg:max-w-7xl max-w-4xl grid items-start grid-cols-1 lg:grid-cols-3 gap-12'>
        <div class='flex justify-center items-center px-4 rounded-xl relative'>
          <img src='<?php echo $row['ImageSource'];?>' alt='product image' class='w-full rounded-xl object-cover'>
        </div>

        <div class='lg:col-span-2 py-5'>
          <span class='bg-blue-900 text-white text-sm font-medium rounded px-2.5 py-1'>ชื่อเรื่อง</span>
          <h1 class='text-3xl font-bold mt-3 mb-5 underline md:underline-offset-4 decoration-red-600 indent-8 leading-relaxed'><?php echo $row['ProName'];?></h1>
          <form action="../Product/OnSearch.php" method="post" class="pb-3">
            <input type="hidden" name="search" value="<?php echo $row['Author'];?>">
            <button type="submit" class="text-left" style="width: 100%; height: 100%; padding: 0; border: none; background: none;">
            ผู้เขียน: <span class='text-base font-normal hover:text-blue-900 hover:underline'><?php echo $row['Author'];?></span>
            </button>
          </form>
          <form action="../Product/<?php echo $row['TypeName'];?>.php" method="post" class="pb-3">
            <input type="hidden" name="search" value="<?php echo $row['Author'];?>">
            <button type="submit" class="text-left" style="width: 100%; height: 100%; padding: 0; border: none; background: none;">
            หมวดหมู่: <span class='text-base font-normal hover:text-blue-900 hover:underline'><?php echo $row['TypeName'];?></span>
            </button>
          </form>
          <div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'>
            <span class='text-3xl font-bold'><?php echo $row['PricePerUnit'];?></span> บาท
          </div>
          <div class='quantity-controls'>
            <div class='flex items-center'>
              <p class='text-base font-normal mr-3'>จำนวน: </p>
              <button type='button' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>
              <input type='number' min='1' max='<?php echo $row['StockQty'] - $stockOnOrder;?>' value='1' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>
              <button type='button' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>
            </div>
            <p class='text-sm font-normal ml-14 mt-3 text-neutral-600'>มีสินค้าทั้งหมด <?php echo $row['StockQty'] - $stockOnOrder;?> เล่ม</p>
            <div class='flex'>
              <input type='hidden' name='proID' value='<?php echo $proID;?>'>
              <input type='hidden' name='quantityHidden' value=''>
              <button id='add-to-cart-button' class='bg-red-500/25 hover:bg-red-500/50 text-red-700 text-base font-normal py-2 px-4 rounded mt-3 border border-red-700 flex items-center'>
                เพิ่มลงในตะกร้า <img src='../../Pictures/shopping-cart.png' alt='cart icon' class='w-6 h-6 ml-2'
              style='filter: grayscale(100%) contrast(0);'></button>
            </div>
          </div>
        </div>
      </div>

      <div class='pt-8'>
        <p class='text-base font-medium mr-3 pb-2'>รายละเอียด <?php echo $row['ProName'];?></p>
        <p class='text-sm font-normal mr-3 indent-8 leading-8'><?php echo $row['Description'];?></p>
      </div>

      <p class='pt-8 text-base font-medium mr-3 pb-2'>แนะนำสำหรับคุณ</p>
    <?php } ?>
    <div class='flex flex-wrap gap-x-5 sm:grid-cols-2 lg:grid-cols-5 justify-center items-center'>
    <?php
    /* Recommend Product */
    $recommendProduct = recommendProduct($proID, $type);
    $count = 0;
      while ($row = $recommendProduct->fetch_assoc()) {
        if ($count >= 5) {
          break;
        }
        $proName = mb_strlen($row['ProName']) > 15 ? mb_substr($row['ProName'], 0, 15) . '...' : $row['ProName'];?>
        <form method='post' id='myForm<?php echo $count?>' style='display: inline;'>
          <input type='hidden' name='proID' value='<?php echo $row['ProID'];?>'>
          <input type='hidden' name='typeName' value='<?php echo $row['TypeName'];?>'>
          <button type='submit'>
            <div class='group relative flex flex-col'>
              <div class='pt-2 overflow-hidden group-hover:opacity-75' style='height: 190px; width: 190px;'>
                <img src='<?php echo $row['ImageSource'];?>' alt='' class='h-full w-full object-cover rounded-lg object-center'>
              </div>
              <div class='mt-4 flex justify-between'>
                <h3 class='text-sm text-gray-700'>
                  <span aria-hidden='true' class='absolute inset-0'></span><?php echo $proName;?>
                </h3>
                <p class='text-sm font-medium text-gray-900'><?php echo $row['PricePerUnit'];?></p>
              </div>
            </div>
          </button>
        </form>
        <?php $count++;
      } ?>
    </div>
  </div>
</body>
<script>
  document.querySelectorAll('.quantity-controls').forEach(function(control) {
    var decreaseButton = control.querySelector('.decrease');
    var increaseButton = control.querySelector('.increase');
    var quantityInput = control.querySelector('.quantity');
    var quantityHidden = control.querySelector('input[name="quantityHidden"]');

    decreaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
        quantityHidden.value = quantityInput.value;
      }
    });

    increaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      var maxQuantity = parseInt(quantityInput.max, 10);
      if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
        quantityHidden.value = quantityInput.value;
      }
    });

    quantityInput.addEventListener('input', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      if (isNaN(currentQuantity)) {
        quantityInput.value = "1";
      } else {
        var maxQuantity = parseInt(quantityInput.max, 10);
        if (currentQuantity > maxQuantity) {
          quantityInput.value = maxQuantity;
          quantityHidden.value = quantityInput.value;
        } else if (currentQuantity === 0) {
          quantityInput.value = "1";
          quantityHidden.value = quantityInput.value;
        }
      }
      quantityHidden.value = quantityInput.value;
    });
    quantityHidden.value = quantityInput.value;
  });

  $(document).ready(function() {
    $('#add-to-cart-button').click(function() {
      $.ajax({
        type: 'POST',
        url: '../../Backend/CartQuery/AddToCart.php',
        data: {
          proID: $('input[name="proID"]').val(),
          quantityHidden: $('input[name="quantityHidden"]').val()
        },
        success: function() {
          window.location.href = '../../Frontend/MainPage/Cart.php';
        }
      });
    });
  });
</script>
</html>