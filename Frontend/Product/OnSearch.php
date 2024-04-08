<?php
  require '../../Backend/Authorized/UserAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  include '../../Backend/ProductQuery/ProductInfo.php';

  $search = '';
  if (isset($_POST['search']) && trim($_POST['search']) !== '') {
    $search = $_POST['search'];
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
  <title>คันหา <?php echo htmlspecialchars($search);?></title>
  <style>
    * {
      font-family: Kodchasan;
    }
  </style>
</head>
<body>
  <div class='px-28 pb-12'>
    <?php 
        $result = searchProduct($search);
        if ($result == null || $search == '') { ?>
          <div class="flex flex-col items-center justify-center">
            <img src="../../Pictures/keywords.png" alt="not found" class="w-56 mx-auto">
            <p class='text-xl font-medium mb-3'>ไม่พบสินค้าที่ค้นหา</p>
            <p class='text-lg font-normal text-gray-400'>ลองค้นหาใหม่อีกครั้ง ด้วยคำที่แตกต่าง หรือ คำที่มีความหมายกว้างกว่านี้</p>
          </div>
          <hr class="h-1 bg-gray-200 border-0 rounded mt-5">
          <p class='pt-8 text-base font-medium mr-3'>สินค้าที่คุณอาจจะสนใจ</p>
          <div class='flex flex-wrap gap-x-5 sm:grid-cols-2 lg:grid-cols-5 justify-center items-center'>
    <?php $recommand = selectShowProduct(10);
          while ($row = $recommand->fetch_assoc()) {
            $proName = mb_strlen($row['ProName']) > 15 ? mb_substr($row['ProName'], 0, 15) . '...' : $row['ProName']; ?>
            <form action='../../Frontend/MainPage/ProductDetail.php' method='post' style='display: inline;'> 
              <input type='hidden' name='proID' value='<?php echo htmlspecialchars($row['ProID']);?>'>
              <input type='hidden' name='typeName' value='<?php echo htmlspecialchars($row['TypeName']);?>'>
              <button type='submit'>
                <div class='group relative flex flex-col mt-8'>
                  <div class='pt-2 overflow-hidden group-hover:opacity-75' style='height: 190px; width: 190px;'>
                    <img src='<?php echo htmlspecialchars($row['ImageSource']);?>' alt='' class='h-full w-full object-cover rounded-lg object-center'>
                  </div>
                  <div class='mt-4 flex justify-between'>
                    <h3 class='text-sm text-gray-700'>
                      <span aria-hidden='true' class='absolute inset-0'></span>
                      <?php echo htmlspecialchars($proName);?>
                    </h3>
                    <p class='text-sm font-medium text-gray-900'><?php echo htmlspecialchars($row['PricePerUnit']);?></p>
                  </div>
                </div>
              </button>
            </form>
    <?php } ?>
          </div>
    <?php } else { ?>
          <div class="flex justify-between items-center">
            <h2 class="leading-7 mb-3 font-medium">
            <span class='bg-green-300 text-black text-sm font-medium rounded px-2 py-1 m-2'>คำค้นหา: </span><?php echo htmlspecialchars($search);?>
          </div>
          <hr class="h-1 bg-gray-200 border-0 rounded mb-5">
          <span class='bg-red-600 text-white text-sm font-medium rounded px-2 py-1 m-2'>ผลลัพธ์: </span>
          <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
    <?php while ($row = $result->fetch_assoc()) { 
            $proName = mb_strlen($row['ProName']) > 16 ? mb_substr($row['ProName'], 0, 16) . '...' : $row['ProName']. "\n"; 
    ?>
            <div class='flex flex-col justify-center items-center'>
              <div class='w-full h-full overflow-hidden rounded-md bg-gray-200 lg:h-80 sm:h-60' style="background-color: white; background-position: center;">
                <form action="../../Frontend/MainPage/ProductDetail.php" method="post">
                  <input type="hidden" name="proID" value="<?php echo htmlspecialchars($row['ProID']); ?>">
                  <input type="hidden" name="typeName" value="<?php echo htmlspecialchars($type); ?>">
                  <button type="submit" style="width: 100%; height: 100%; padding: 0; border: none; background: none;">
                    <img src='<?php echo htmlspecialchars($row['ImageSource']); ?>' alt='product image' class='w-full h-full object-center object-cover'>
                  </button>
                </form>
              </div>
              <div class='mt-4 w-full'>
                <form action="../../Frontend/MainPage/ProductDetail.php" method="post">
                  <input type="hidden" name="proID" value="<?php echo htmlspecialchars($row['ProID']); ?>">
                  <input type="hidden" name="typeName" value="<?php echo htmlspecialchars($type); ?>">
                  <button type="submit" style="width: 100%; height: 100%; padding: 0; border: none; background: none; text-align: left;">
                    <h3 class='text-lg font-medium text-gray-900'><?php echo htmlspecialchars($proName); ?></h3>
                  </button>
                </form>
                <form method="post" id="myForm{$row['ProID']}">
                 <input type="hidden" name="search" value="<?php echo htmlspecialchars($row['Author']); ?>">
                 <button type="submit" style="width: 100%; height: 100%; padding: 0; border: none; background: none;">
                   <p class='mt-1 text-xs text-gray-500 text-left hover:text-blue-900 hover:underline'><?php echo htmlspecialchars($row['Author']); ?></p>
                  </button>
                </form>
                <div class='mt-2 text-center'>
                  <p class='text-xl text-red-600 font-bold'><?php echo htmlspecialchars($row['PricePerUnit']); ?> บาท</p>
                  <button class='add-to-cart-button bg-[#062639] hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded inline-block mt-4'>เพิ่มลงในตะกร้า</button>
                </div>
              </div>
            </div>
          <?php } ?>
          </div>
        <?php }
     ?>
  </div>

  <script>
    $(document).ready(function() {
      $('.add-to-cart-button').click(function() {
        var proID = $(this).closest('.flex').find('input[name="proID"]').val();
        $.ajax({
          type: 'POST',
          url: '../../Backend/CartQuery/AddToCart.php',
          data: {
            proID: proID,
            quantityHidden: 1
          },
          success: function() {
            window.location.href = '../../Frontend/MainPage/Cart.php';
          }
        });
      });
    });
  </script>
</body>
</html>