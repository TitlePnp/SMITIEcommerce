<?php
  require '../../Backend/Authorized/UserAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  include '../../Backend/CartQuery/CartDetail.php';
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
<body> <!-- PRODUCT SECTION -->
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
            <td scope="col" class="px-6 py-3 text-center">ต้องการลบสินค้า?</td>
          </tr>
        </thead>
        <tbody>
          <?php
          /* FOR SESSION */
          if (isset($_SESSION['cart'])) {
            $count = count($_SESSION['cart']);
            $keys = array_keys($_SESSION['cart']);
            $quantity = array_values($_SESSION['cart']);
            for ($i = 0; $i < $count; $i++) {
              $row = showCartSession($keys[$i])->fetch_assoc();?>
              <tr class='bg-white font-normal border-b'>
                <td class='w-4 p-4'>
                  <div class='flex items-center'>
                    <input id='checkbox-product' type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded'>
                    <label for='checkbox-product' class='sr-only'>checkbox</label>
                  </div>
                </td>
                <td class='w-4 p-4'>
                  <div style='height: 120px; width: 120px;'>
                    <img src='<?php echo $row['ImageSource'];?>' alt='' class='h-full w-full object-cover rounded-lg object-center'>
                  </div>
                </td>
                <?php $proName = mb_strlen($row['ProName']) > 20 ? mb_substr($row['ProName'], 0, 20) . '...' : $row['ProName'];?>
                <td scope='row' class='font-medium text-gray-900'><?php echo $proName;?></td>
                <td class='text-center'><?php echo $row['PricePerUnit'];?></td>
                <td>
                  <div class='quantity-controls flex items-center justify-center'>
                    <input type='hidden' name='proID' value='<?php echo $keys[$i];?>'>
                    <input type='hidden' name='quantityHidden' value=''>
                    <input type='hidden' name='pricePerUnit' value='<?php echo $row['PricePerUnit'];?>'>
                    <button type='submit' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>
                      <input type='number' min='1' max='<?php echo $row['StockQty'];?>' value='<?php echo $quantity[$i]?>' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>
                    <button type='submit' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>
                    </div>
                  <p class='text-center text-sm font-normal mt-3 text-neutral-600'>มีสินค้าทั้งหมด <?php echo $row['StockQty'];?> เล่ม</p>
                </td>
                <td class='text-center'><p class='sum'></p></td>
                <td class='text-center'><button class='delete bg-amber-400 hover:bg-amber-500 text-white text-base font-normal py-2 px-4 rounded mt-3 ml-3' data-proid='<?php echo $keys[$i];?>'>ลบ</button></td>
              </tr>
      <?php } 
          } else { /* FOR DB */
              $rows = showCartDB(getID());
              while ($row = $rows->fetch_assoc()) {
                $id = $row['ProID'];?>
                <tr class='bg-white font-normal border-b'>
                  <td class='w-4 p-4'>
                    <div class='flex items-center'>
                      <input id='checkbox-product' type='checkbox' class='w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded'>
                      <label for='checkbox-product' class='sr-only'>checkbox</label>
                    </div>
                  </td>
                  <td class='w-4 p-4'>
                    <div style='height: 120px; width: 120px;'>
                      <img src='<?php echo $row['ImageSource'];?>' alt='' class='h-full w-full object-cover rounded-lg object-center'>
                    </div>
                  </td>
            <?php $proName = mb_strlen($row['ProName']) > 20 ? mb_substr($row['ProName'], 0, 20) . '...' : $row['ProName'];?>
                  <td scope='row' class='font-medium text-gray-900'><?php echo $proName;?></td>
                  <td class='text-center'><?php echo $row['PricePerUnit'];?></td>
                  <td>
                    <div class='quantity-controls flex items-center justify-center'>
                      <input type='hidden' name='proID' value='<?php echo $row['ProID'];?>'>
                      <input type='hidden' name='quantityHidden' value=''>
                      <input type='hidden' name='pricePerUnit' value='<?php echo $row['PricePerUnit'];?>'>
                      <button type='submit' class='decrease hover:bg-slate-200 border border-gray-300 h-8 w-8 border-r-0 flex items-bottom justify-center'>-</button>
                        <input type='number' min='1' max='<?php echo $row['StockQty'];?>' value='<?php echo $row['Qty'];?>' class='quantity bg-white text-gray-900 text-sm w-16 h-8 border border-gray-300  text-center'>
                      <button type='submit' class='increase hover:bg-slate-200 border border-gray-300 h-8 w-8 border-l-0 flex items-bottom justify-center'>+</button>
                    </div>
                    <p class='text-center text-sm font-normal mt-3 text-neutral-600'>มีสินค้าทั้งหมด <?php echo $row['StockQty'];?> เล่ม</p>
                  </td>
                  <td class='text-center'><p class='sum'></p></td>
                  <td class='text-center'><button type='submit' class='delete bg-amber-400 hover:bg-amber-500 text-white text-base font-normal py-2 px-4 rounded mt-3 ml-3' data-proid='<?php echo $row['ProID'];?>'>ลบ</button></td>
                </tr>
        <?php }
            }?>
        </tbody>
      </table>
    </div>
    <!-- SUMMARY SECTION -->
    <footer class="sticky bottom-0 bg-[#062639] rounded-lg shadow m-4 p-4">
      <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center mb-2 md:mb-0">
          <input id="checkbox-all-last" type="checkbox" class="w-4 h-4 text-blue-600 mr-2">
          <label for="checkbox-all-last" class="text-white">สินค้าที่เลือก: <span id="qty-choose"></span> รายการ</label>
        </div>
        <div class="text-white text-center mb-2 md:mb-0"> ราคารวมทั้งหมด: <span id="total"></span> บาท</div>
        <div class="justify-end">
          <form action='SummaryOrder.php' method='post'>
            <input type='hidden' name='select-proID' value=''>
            <button class='bg-red-500 hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded'>ซื้อสินค้า</button>
          </form>
        </div>
      </div>
    </footer>
  </div>
<script>
  /* Decrease, Increase QTY */
  document.querySelectorAll('.quantity-controls').forEach(function(control, index) {
    var decreaseButton = control.querySelector('.decrease');
    var increaseButton = control.querySelector('.increase');
    var quantityInput = control.querySelector('.quantity');
    var quantityHidden = control.querySelector('input[name="quantityHidden"]');
    
    var price = parseFloat(control.querySelector('input[name="pricePerUnit"]').value);
    var sumElement = document.querySelectorAll('.sum')[index];
    var sum = price * quantityInput.value;

    decreaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
        quantityHidden.value = quantityInput.value;
      }
      sum = price * quantityInput.value;
      sumElement.innerHTML = sum;
      var proID = control.querySelector('input[name="proID"]').value;
      sendUpdateRequest(proID, quantityInput.value);
    });

    increaseButton.addEventListener('click', function() {
      var currentQuantity = parseInt(quantityInput.value, 10);
      var maxQuantity = parseInt(quantityInput.max, 10);
      if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
        quantityHidden.value = quantityInput.value;
      }
      sum = price * quantityInput.value;
      sumElement.innerHTML = sum;
      var proID = control.querySelector('input[name="proID"]').value;
      sendUpdateRequest(proID, quantityInput.value);
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
      sum = price * quantityInput.value;
      sumElement.innerHTML = sum;
      var proID = control.querySelector('input[name="proID"]').value;
      sendUpdateRequest(proID, quantityInput.value);
    });
    sumElement.innerHTML = sum;
    quantityHidden.value = quantityInput.value;
  });

  /* CHECK BOX */
  $(document).ready(function(){
    updateTotal();
    $('#checkbox-all, #checkbox-all-last').click(function(){
      if ($(this).is(':checked')) {
        $('input[type="checkbox"]').prop('checked', true);
      } else{
        $('input[type="checkbox"]').prop('checked', false);
      }
      updateTotal();
    });
    $('input[type="checkbox"]').click(function(){
      updateTotal();
    });
  });

  /* SUMMARY SECTION */
  function updateTotal() {
    var total = 0;
    var count = 0;
    var rows = document.querySelectorAll('tbody tr');
    rows.forEach(function(row) {
      var checkbox = row.querySelector('input[type="checkbox"]');
      if (checkbox.checked) {
        var price = parseFloat(row.querySelector('td:nth-child(4)').textContent);
        var qty = parseInt(row.querySelector('input[type="number"]').value, 10);
        total += price * qty;
        count++;
      }
    });
    document.getElementById('total').innerHTML = total.toFixed(2);
    document.getElementById('qty-choose').innerHTML = count;
  }

  /* AJAX UPDATE */
  function sendUpdateRequest(proID, quantity) {
    console.log(proID, quantity);
    $.ajax({
      type: 'POST',
      url: '../../Backend/CartQuery/AddToCart.php',
      data: {
        proID: proID,
        quantityHidden: quantity,
        update: true
      },
      success: function() {
        console.log('success');
        updateTotal();
      }
    });
  }

  $(document).ready(function() {
    document.querySelectorAll('.delete').forEach(function(button) {
      button.addEventListener('click', function() {
          var proID = button.dataset.proid;
          sendDeleteRequest(proID);
      });
    });
  });

  /* AJAX DELETE */
  function sendDeleteRequest(proID) {
    $.ajax({
      type: 'POST',
      url: '../../Backend/CartQuery/DeleteFromCart.php',
      data: {
        proID: proID,
        action: 'cancel'
      },
      success: function() {
        location.reload();
      }
    });
  }

  /* บังคับให้เลือกสินค้าก่อน */
  $('form').on('submit', function(e) {
    var count = 0;
    var checkedProIds = [];
    $('input[type="checkbox"]').each(function() {
      if ($(this).is(':checked')) {
        count++;
        checkedProIds.push($(this).closest('tr').find('input[name="proID"]').val());
      }
    });
    $('input[name="select-proID"]').val(checkedProIds.join(','));
    if (count === 0) {
      e.preventDefault();
      alert('กรุณาเลือกสินค้าที่ต้องการซื้อ');
    }
  });
</script>
</body>
</html>