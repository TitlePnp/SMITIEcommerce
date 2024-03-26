<?php
require '../../Components/HeaderUser.html';
include '../../Backend/MainPage/ProductDetail.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com"></script>
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
  <?php
  /* รับข้อมูล */
  $proID = $_POST['proID'];
  $type = $_POST['typeName'];
  /* กำหนดระยะห่างขอบข้าง + ขอบล่าง */
  echo "<div class='px-28 pb-12'>";
    /* Detail Product */
    $mainProduct = mainProduct($proID);
    while ($row = $mainProduct->fetch_assoc()) {
      echo "<div class='lg:max-w-7xl max-w-4xl grid items-start grid-cols-1 lg:grid-cols-3 gap-12'>";
        echo "<div class='flex justify-center items-center px-4 rounded-xl relative'>";
          echo "<img src='{$row['ImageSource']}' alt='product image' class='w-full rounded-xl object-cover'>";
        echo "</div>";

        echo "<div class='lg:col-span-2 py-5'>";
          echo "<span class='bg-blue-900 text-white text-sm font-medium rounded px-2.5 py-1'>ชื่อเรื่อง</span>";
          echo "<h1 class='text-4xl font-bold mt-3 mb-5 underline md:underline-offset-4 decoration-red-600 indent-8'>{$row['ProName']}</h1>";
          echo "<p class='text-base font-normal pb-3'>ผู้เขียน: {$row['Author']}</p>";
          echo "<p class='text-base font-normal pb-3'>หมวดหมู่: {$row['TypeName']}</p>";
          echo "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'/>";
            echo "<span class='text-3xl font-bold'>{$row['PricePerUnit']}</span> บาท";
          echo "</div>";
          echo "<div class='quantity-controls'>";
            echo "<div class='flex items-center'>";
              echo "<p class='text-base font-normal mr-3'>จำนวน: </p>";
              echo "<button type='button' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>";
                echo "<input type='number' min='1' max='{$row['StockQty']}' value='1' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>";
              echo "<button type='button' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>";
            echo "</div>";
            echo "<p class='text-sm font-normal ml-14 mt-3 text-neutral-600'>มีสินค้าทั้งหมด {$row['StockQty']} เล่ม</p>";
            echo "<div class='flex'>";
              echo "<button class='bg-red-500/25 hover:bg-red-500/50 text-red-700 text-base font-normal py-2 px-4 rounded mt-3 border border-red-700 flex items-center'>เพิ่มลงในตะกร้า";
              echo "<img src='../../Pictures/shopping-cart.png' alt='cart icon' class='w-6 h-6 ml-2'
              style='filter: grayscale(100%) contrast(0);'></button>";
              echo "<button class='bg-red-500 hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded mt-3 ml-3'>ซื้อสินค้า</button>";
            echo "</div>";
          echo "</div>";
        echo '</div>';
      echo '</div>';

      echo "<div class='pt-8'>";
        echo "<p class='text-base font-medium mr-3 pb-2'>รายละเอียด {$row['ProName']}</p>";
        echo "<p class='text-sm font-normal mr-3 indent-8 leading-8'>{$row['Description']} </p>";
      echo '</div>';

      echo "<p class='pt-8 text-base font-medium mr-3 pb-2'>แนะนำสำหรับคุณ</p>";
    }
    /* Recommend Product */
    $recommendProduct = recommendProduct($proID, $type);
    $count = 0;
    echo "<div class='flex flex-wrap gap-x-5 sm:grid-cols-2 lg:grid-cols-5 justify-center items-center'>";
      while ($row = $recommendProduct->fetch_assoc()) {
        if ($count >= 5) {
          break;
        }
        $proName = mb_strlen($row['ProName']) > 15 ? mb_substr($row['ProName'], 0, 15) . '...' : $row['ProName'];
        echo "<form method='post' id='myForm{$count}' style='display: inline;'>";
          echo "<input type='hidden' name='proID' value='{$row['ProID']}'>";
          echo "<input type='hidden' name='typeName' value='{$row['TypeName']}'>";
          echo "<button type='submit'>";
            echo "<div class='group relative flex flex-col'>";
              echo "<div class='pt-2 overflow-hidden group-hover:opacity-75' style='height: 190px; width: 190px;'>";
                echo "<img src='{$row['ImageSource']}' alt='' class='h-full w-full object-cover rounded-lg object-center'>";
              echo "</div>";
              echo "<div class='mt-4 flex justify-between'>";
                echo "<h3 class='text-sm text-gray-700'>";
                  echo "<span aria-hidden='true' class='absolute inset-0'></span>";
                  echo $proName;
                echo "</h3>";
                echo "<p class='text-sm font-medium text-gray-900'>{$row['PricePerUnit']}</p>";
              echo "</div>";
            echo "</div>";
          echo "</button>";
        echo "</form>";
        $count++;
      }
    echo "</div>";
  echo "</div>";
?>
</body>
<script>
  document.querySelectorAll('.quantity-controls').forEach(function(control) {
    var decreaseButton = control.querySelector('.decrease');
    var increaseButton = control.querySelector('.increase');
    var quantityInput = control.querySelector('.quantity');

    decreaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
      }
    });

    increaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      var maxQuantity = parseInt(quantityInput.max, 10);
      if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
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
        }
      }
    });
  });
</script>
</html>