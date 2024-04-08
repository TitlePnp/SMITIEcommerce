<?php
  require '../../Backend/Authorized/AdminAuthorized.php';
  require '../../Backend/Authorized/ManageHeader.php';
  require '../../Backend/Admin/Product/ProductList.php';
//   -- Active = ขายอยู่
//   -- Inactive = เลิกขาย
//   -- OutStock = Out Of Stock = ของหมด = stock ต้องเป็น 0
  if (isset($_SESSION['proStatus'])) {
    $status = $_SESSION['proStatus'];
  } else {
    $status = "Active";
  }

  if (isset($_POST['status'])) {
    $status = $_POST['status'];
    $_SESSION['proStatus'] = $status;
  }

  $search = '';
  if (isset($_POST['search']) && trim($_POST['search']) !== '') {
    $search = $_POST['search'];
    $_POST['search'] = '';
  }

  $product = searchProduct($search, $status);
  $pType = showProductType();
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
  <div class="px-20 pb-12">
    <div class="flex flex-wrap justify-center items-center mb-5">
      <button type="button" value="Active" class="head m-3 w-full sm:w-auto bg-white hover:bg-blue-100 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5 border-2 border-blue-700">
        <img src="../../Pictures/Admin/salary.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">กำลังขายอยู่</p>
      </button>

      <button type="button" value="OutStock" class="head m-3 w-full sm:w-auto bg-white hover:bg-blue-100 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5 border-2 border-blue-700">
        <img src="../../Pictures/Admin/pending-box.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">คลังสินค้าหมด</p>
      </button>

      <button type="button" value="Inactive" class="head m-3 w-full sm:w-auto bg-white hover:bg-blue-100 shadow-lg text-black rounded-lg flex flex-col items-center justify-center px-4 py-2.5 border-2 border-blue-700">
        <img src="../../Pictures/Admin/express-delivery.png" class="w-9 h-9 my-2" alt="">
        <p class="text-center text-sm font-medium">ยกเลิกการขาย</p>
      </button>
    </div>

    <div class="flex flex-col sm:flex-row my-6">
      <div class="flex flex-col mr-10">
        <p class="font-medium my-2">ค้นหา</p>
        <form style="width: 100%" method="post">
          <div class="relative">
            <input type="text" class="text-sm w-full placeholder:italic bg-white border rounded-md py-2 px-16 ps-3"
              placeholder="ชื่อสินค้า, ชื่อผู้แต่ง" name="search" required/>
            <button type="submit" class="absolute right-0 top-0 bottom-0 bg-green-300 hover:bg-green-500 rounded-r-md px-5 py-2">
              <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
            </button>
          </div>
        </form>
      </div>
        
      <div class="flex flex-col">
        <p class="font-medium my-1">หมวดหมู่</p>
        <form style="width: 100%" action="ProductOnType.php" method="post">
          <div class="relative inline-flex">
            <svg class="w-2 h-2 absolute top-0 right-0 m-4 pointer-events-none" viewBox="0 0 412 232">
              <path d="M206 171.144L42.678 7.822c-9.763-9.763-25.592-9.763-35.355 0-9.763 9.764-9.763 25.592 0 35.355l181 181c9.763 9.763 25.592 9.763 35.355 0l181-181c9.763-9.764 9.763-25.592 0-35.355-9.763-9.763-25.592-9.763-35.355 0L206 171.144z" fill="#648299" fill-rule="nonzero"/>
            </svg>
            <select name="type" class="text-sm text-gray-600 bg-white border border-gray-200 hover:border-gray-400 rounded-lg  h-9 pl-5 pr-10  appearance-none">
              <option>ทั้งหมด</option>
            <?php while ($row = $pType->fetch_assoc()) { ?>
              <option value="<?php echo $row['TypeName'];?>"><?php echo $row['ThaiType'];?></option>
            <?php } ?>
            </select>
          </div>
          <button type="submit" class="bg-green-300 hover:bg-green-500 rounded-full px-5 py-3 ml-4">
            <img class="h-5 w-auto" src="../../Pictures/search.png" alt="search">
          </button>
        </form>
      </div>
    </div>

    <div class="flex items-center p-4 mb-4 text-green-800 rounded-lg bg-green-100" role="alert">
    <img src="../../Pictures/info-green.png" class="w-4 h-4" alt="info" />
    <div>
      <span class="font-medium pl-3"> 
        <?php echo "ค้นหา: {$search}" ?>
      </span>
    </div>
    </div>

    <div class="flex justify-between items-center">
      <div class="flex space-x-4">
        <a href="ManageType.php">
          <button class="delete bg-amber-400 hover:bg-amber-500 text-black text-sm font-normal py-2 px-4 rounded mb-3 ml-3">+ เพิ่มหมวดหมู่ใหม่</button>
        </a>
        <a href="ProductInfo.php">
          <button class="delete bg-red-500 hover:bg-red-600 text-white text-sm font-normal py-2 px-4 rounded mb-3 ml-3">+ เพิ่มสินค้าใหม่</button>
        </a>
      </div>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-md">
      <table class="w-full text-sm text-black mt-2">
        <thead class="text-sm bg-gray-100">
          <tr>
            <th scope="col"></th>
            <th scope="col" style="letter-spacing: 0.1em;">ชื่อสินค้า</th>
            <th scope="col" style="letter-spacing: 0.1em;">ราคา</th>
            <th scope="col" style="letter-spacing: 0.1em;">วันที่สร้าง /<br /> วันที่แก้ไข</th>
            <th scope="col" style="letter-spacing: 0.1em;">จำนวนสินค้า</th>
            <th scope="col" style="letter-spacing: 0.1em;">หมวดหมู่</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
      <?php
          while ($row = $product->fetch_assoc()) {
            $name = mb_strlen($row['ProName']) > 50 ? mb_substr($row['ProName'], 0, 50) . '...' : $row['ProName'];
            $des = mb_strlen($row['Description']) > 30 ? mb_substr($row['Description'], 0, 30) . '...' : $row['Description'];
      ?>
          <tr class="odd:bg-white even:bg-gray-50 border-b text-center">
            <td class="px-3 py-4 overflow-hidden flex justify-center items-center" style="height: 150px; width: 150px;">
              <img src="<?php echo $row['ImageSource'];?>" alt="" class="h-full w-full object-cover rounded-lg object-center">
            </td>

            <td class="pl-2 py-4 text-left text-base align-top">
              <p><?php echo $name;?></p>
              <p class="ps-3 py-3 text-sm/[17px] leading-loose">
                <span class="font-semibold">ผู้แต่ง: </span><?php echo $row['Author'];?>
              </p>
              <p class="ps-3 text-sm/[17px] leading-loose">
                <span class="font-semibold">รายละเอียด: </span><?php echo $des;?>
              </p>
            </td>

            <td class="px-3 py-4"><?php echo $row['PricePerUnit'];?></td>

            <td class="pl-2 py-4 text-left text-sm">
              <p><?php echo $row['First_Day'];?></p>
              <p><?php echo $row['Update_Day'];?></p>
            </td>

            <td class="px-3 py-4"><?php echo $row['StockQty'];?></td>

            <td class="px-3 py-4">
              <div class="flex justify-center text-center">
                <p class="max-w-20 text-xs font-medium text-white rounded-lg bg-blue-500 px-2 py-2"><?php echo $row['ThaiType'];?></p>
              </div>
            </td>

            <td class="pr-3">
              <form action="ProductInfo.php" method="post">
                <input type="hidden" name="proID" value="<?php echo $row['ProID'];?>">
                <button type="submit"><img class="h-6 w-auto" src="../../Pictures/Admin/pen.png" alt="search"></button>
              </form>
              <button type="button"><img class="h-6 w-auto" src="../../Pictures/Admin/observation.png" alt="search"></button>
            </td>
          </tr>
          <?php } /* end whileloop */?>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    var status = "<?php echo $status; ?>";
    $(document).ready(function() {
      $('.head').each(function() {
        if ($(this).val() === status) {
          $(this).removeClass('bg-white border-2');
          $(this).addClass('bg-blue-300');
        }
      });
      $('.head').click(function() {
        var status = $(this).val();
        $.ajax({
          url: 'Product.php',
          type: 'POST',
          data: {
            status: status
          },
          success: function(data) {
            window.location = 'Product.php';
          }
        });
      });
    });
  </script>
</body>
</html>