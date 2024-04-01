<?php
  require '../../Backend/Authorized/UserAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  include '../../Backend/ProductQuery/ProductInfo.php';
  $type = 'knowledge';
  $proPerPage = 20;
  $countPro = countProduct($type);
  $pages = ceil($countPro / $proPerPage);
  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }
  $startPro = ($page - 1) * $proPerPage;
  $product = showProductSplitPage($type, $startPro, $proPerPage);
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
  <title>หนังสือการ์ตูน</title>
  <style>
    * {
      font-family: Kodchasan;
    }
  </style>
</head>
<body>
  <div class='px-28 pb-12'>
    <div class="flex justify-between items-center">
      <h2 class="leading-7 mb-3 font-medium">
        <span class='bg-amber-400 text-white text-sm font-medium rounded px-2 py-1 m-2'>knowledge</span>หนังสือความรู้รอบตัว
      </h2>
      <p class="text-sm">หน้าที่ <?php echo $page; ?> จาก <?php echo $pages; ?></p>
    </div>
    <hr class="h-1 bg-gray-200 border-0 rounded mb-5">
    <div class="mt-6 grid grid-cols-1 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8">
      <?php while ($row = $product->fetch_assoc())  {
              $proName = mb_strlen($row['ProName']) > 16 ? mb_substr($row['ProName'], 0, 16) . '...' : $row['ProName']. "\n";
      ?>
      <div class='flex flex-col justify-center items-center'>
        <div class='w-full h-full overflow-hidden rounded-md bg-gray-200 lg:h-80 sm:h-60'>
          <form action="../../Frontend/MainPage/ProductDetail.php" method="post">
            <input type="hidden" name="proID" value="<?php echo $row['ProID']; ?>">
            <input type="hidden" name="typeName" value="<?php echo $type; ?>">
            <button type="submit" style="width: 100%; height: 100%; padding: 0; border: none; background: none;">
              <img src='<?php echo $row['ImageSource']; ?>' alt='product image' class='w-full h-full object-center object-cover'>
            </button>
          </form>
        </div>
        <div class='mt-4 w-full'>
          <form action="../../Frontend/MainPage/ProductDetail.php" method="post">
            <input type="hidden" name="proID" value="<?php echo $row['ProID']; ?>">
            <input type="hidden" name="typeName" value="<?php echo $type; ?>">
            <button type="submit" style="width: 100%; height: 100%; padding: 0; border: none; background: none; text-align: left;">
              <h3 class='text-lg font-medium text-gray-900'><?php echo $proName; ?></h3>
            </button>
          </form>
          <p class='mt-1 text-xs text-gray-500'><?php echo $row['Author']; ?></p>
          <div class='mt-2 text-center'>
            <p class='text-xl text-red-600 font-bold'><?php echo $row['PricePerUnit']; ?> บาท</p>
            <button class='add-to-cart-button bg-[#062639] hover:bg-red-600 text-white text-base font-normal py-2 px-4 rounded inline-block mt-4'>เพิ่มลงในตะกร้า</button>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
    <div class="mt-6 flex justify-center">
      <div class="flex">
        <?php for ($i=1; $i<=$pages; $i++) { 
                if ($i == $page) {     ?>
                  <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 bg-[#062639] rounded-md text-white"><?php echo $i; ?></a>
                <?php } else { ?>
                <a href="?page=<?php echo $i; ?>" class="mx-1 px-3 py-1 bg-gray-200 rounded-md"><?php echo $i; ?></a>
        <?php }} ?>
      </div>
    </div>
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
            location.reload();
          }
        });
      });
    });
  </script>
</body>
</html>