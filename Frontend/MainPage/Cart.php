<?php
require '../../Backend/Authorized/UserAuthorized.php';
require '../../Backend/Authorized/ManageHeader.php';
include '../../Backend/MainPage/CartDetail.php';
if (isset($_SESSION['cart'])) {
    print_r($_SESSION['cart']);
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
  <div class="px-28 pb-12">
    <div class="relative overflow-x-scroll sm:rounded-lg">
      <table class="w-full font-black">
        <thead class="font-base font-medium uppercase border-b-2">
          <tr>
            <th scope="col" class="p-4 border-r">
              <div class="flex items-center">
                <input id="checkbox-all" type="checkbox" class="w-4 h-4 text-blue-600">
                <label for="checkbox-all" class="sr-only">checkbox</label>
              </div>
            </th>
            <td scope="col" class="px-6 py-3 text-center">สินค้า</td>
            <td scope="col" class="px-6 py-3 text-center"></td>
            <td scope="col" class="px-6 py-3 text-center border-x">ราคาต่อเล่ม</td>
            <td scope="col" class="px-6 py-3 text-center border-x">จำนวน</td>
            <td scope="col" class="px-6 py-3 text-center border-x">ราคารวม</td>
            <td scope="col" class="px-6 py-3 text-center">Action</td>
          </tr>
        </thead>
        <tbody>
          <?php
            $count = count($_SESSION['cart']);
            $keys = array_keys($_SESSION['cart']);
            $quantity = array_values($_SESSION['cart']);
            for ($i = 0; $i < $count; $i++) {
              $row = showCartProduct($keys[$i])->fetch_assoc();
              $totalPrice = $row['PricePerUnit'] * $quantity[$i];
              echo "<tr class='bg-white font-normal border-b'>";
                echo "<td class='w-4 p-4'>";
                  echo "<div class='flex items-center'>";
                    echo "<input id='checkbox-product' type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded'>";
                    echo "<label for='checkbox-product' class='sr-only'>checkbox</label>";
                  echo "</div>";
                echo "</td>";
                echo "<td class='w-4 p-4'>";
                  echo "<div style='height: 120px; width: 120px;'>";
                    echo "<img src='{$row['ImageSource']}' alt='' class='h-full w-full object-cover rounded-lg object-center'>";
                  echo "</div>";
                echo "</td>";
                $proName = $row['ProName'];
                if (strlen($proName) >= 20) {
                  $proName = wordwrap($proName, 20, "<br />\n", true);
                }
                echo "<td scope='row' class='font-medium text-gray-900'>{$proName}</td>";
                echo "<td class='text-center'>{$row['PricePerUnit']}</td>";
                echo "<td>";
                  echo "<div class='quantity-controls flex items-center justify-center'>";
                    echo "<button type='button' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>";
                      echo "<input type='number' min='1' max='{$row['StockQty']}' value='{$quantity[$i]}' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>";
                    echo "<button type='button' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>";
                  echo "</div>";
                  echo "<p class='text-center text-sm font-normal mt-3 text-neutral-600'>มีสินค้าทั้งหมด {$row['StockQty']} เล่ม</p>";
                echo "</td>";
                echo "<td class='text-center'>{$totalPrice}</td>";
                echo "<td class='text-center'><button class='bg-amber-400 hover:bg-amber-500 text-white text-base font-normal py-2 px-4 rounded mt-3 ml-3'>ลบ</button></td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div>
  <div>

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
    $.ajax({
    url: '../../Backend/MainPage/AddToCart.php',
    method: 'POST',
    data: {
      proID: productId,
      'quantity-hidden': quantity
    },
    success: function(response) {
      // Update the page with the response if necessary
      location.reload(); // reload the page to see the updated cart
    }
  });
  });

  $(document).ready(function(){
    $('#checkbox-all').click(function(){
      if ($(this).is(':checked')) {
        $('input[type="checkbox"]').prop('checked', true);
      } else{
        $('input[type="checkbox"]').prop('checked', false);
      }
    });
  });
</script>
</body>
</html>