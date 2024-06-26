<?php
  require '../../Backend/UserManage/OnCart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <script src="https://cdn.tailwindcss.com/3.4.3"></script>
  <!-- <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet"> -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kodchasan:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
  <style>
    .nav-style {
      color: white;
      border-radius: 0.375rem;
      /* px-3 */
      padding-left: 0.75rem;
      padding-right: 0.75rem;
      /* py-2 */
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
    }

    .nav-style:hover, [aria-current="page"] {
      background-color: white;
      color: black;
    }
  </style>
</head>
<body>
  <!-- SECTION 1 -->
  <nav class="sticky top-0 bg-[#062639] z-20 text-sm font-semibold tracking-wider" style="font-family: 'Kodchasan', semibold,serif;">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 relative flex h-12 items-center justify-between">
      <!-- logo -->
      <div>
        <a href="../../Frontend/MainPage/Home.php"><img class="h-10 w-auto" src="../../Pictures/logo.png" alt="logo"></a>
      </div>
      <!-- search bar -->
      <form class="mx-auto" style="width: 60%" action="../../Frontend/Product/OnSearch.php" method="post">
        <div class="relative">
          <input type="text" class="text-sm w-full placeholder:italic bg-white border rounded-md py-2 px-3 
                  focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1"
                  placeholder="ค้นหาสินค้าที่คุณต้องการ..." name="search" required/>
          <button type="submit" class="absolute right-0 top-0 bottom-0 bg-green-300 text-white hover:bg-green-500 rounded-r-md text-sm px-5 py-2.5">
            <img class="h-5 w-auto" src="../../Pictures/search.png" alt="logo">
          </button>
        </div>
      </form>
      <!-- shopping cart -->
      <div class="relative inline-flex w-fit">
        <div class="absolute bottom-auto left-auto right-0 top-0 z-10 inline-block text-white
                  -translate-y-1/3 translate-x-1/6 rounded-full bg-red-600 p-2 text-xs/[1px]"> <?php echo $_SESSION['productOnCart']; ?>
        </div>
        <button type="button" class="relative flex mr-3" id="cart" aria-expanded="false" aria-haspopup="true">
          <a href="../../Frontend/MainPage/Cart.php">
            <img class="h-6 w-6 hover:scale-110"
            src="../../Pictures/shopping-cart.png" alt="shopping-cart">
          </a>
        </button>
      </div>
      <!-- notification -->
      <button type="button" class="relative rounded-full p-1 text-white hover:text-red-600 hover:bg-white ml-2">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967
                                                                  0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312
                                                                  6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255
                                                                  0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
        </svg>
      </button>
      <!-- dropdown user -->
      <div class="relative ml-3">
        <div>
          <button type="button" class="relative flex" id="user-button" aria-expanded="false" aria-haspopup="true">
            <img class="h-8 w-8 rounded-full border border-transparent hover:border-red-600 hover:scale-110"
                  src="../../Pictures/user.png" alt="user-account">
          </button>
        </div>
        <!-- list dropdown user -->
        <div class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white py-1 shadow-lg
                  ring-1 ring-black ring-opacity-5 focus:outline-none text-sm hidden" role="menu"
                  aria-orientation="vertical" aria-labelledby="user-button" tabindex="-1" id="user-menu">
          <a href="../../Frontend/Profile/UserProfile.php" class="block px-4 py-2 hover:bg-gray-200" role="menuitem" tabindex="-1" id="user-item-0">บัญชีผู้ใช้งาน</a>
          <a href="../../Backend/UserManage/Signout.php" class="block px-4 py-2 hover:bg-gray-200" role="menuitem" tabindex="-1" id="user-item-1">ออกจากระบบ</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- SECTION 2 -->
  <nav class="bg-[#062639] text-sm font-semibold tracking-wider mb-10" style="font-family: 'Kodchasan', semibold,serif;">
    <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8 relative flex h-12 items-center justify-between">
      <!-- sm:hidden = ซ่อนส่วนของ Element เมื่อมีขนาดเล็ก -->
      <div class="absolute inset-y-0 left-0 flex items-center sm:hidden">
        <button type="button" class="relative inline-flex items-center justify-center rounded-md p-2 text-white
                                    hover:bg-white hover:text-black focus:outline-none focus:ring-2 focus:ring-inset
                                    focus:ring-white" id="hide-button" aria-expanded="false" aria-haspopup="true">
          <!-- icon hamburger -->
          <svg class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>
      </div>
      <!-- ส่วนของ nav ต่างๆ -->
      <div class="flex flex-1 items-center justify-center">
        <!-- menu -->
        <div class="hidden sm:ml-6 sm:block">
          <div class="flex space-x-10">
            <a href="../../Frontend/Product/Comic.php" class="nav-style" aria-current="false">หนังสือการ์ตูน</a>
            <a href="../../Frontend/Product/Knowledge.php" class="nav-style" aria-current="false">หนังสือความรู้รอบตัว</a>
            <a href="../../Frontend/Product/Novel.php" class="nav-style" aria-current="false">หนังสือนวนิยาย</a>
            <a href="../../Frontend/Product/Magazine.php" class="nav-style" aria-current="false">หนังสือนิตยสาร</a>
            <a href="../../Frontend/Product/General.php" class="nav-style" aria-current="false">หนังสือทั่วไป</a>
          </div>
        </div>
      </div>

      <div class="text-sm font-semibold text-white sm:hidden tracking-wider hidden" aria-labelledby="hide-button" id="hide-menu"
          style="position: fixed; top: 6rem; left: 0; right: 0;">
        <div class="space-y-1 px-2 pb-3 pt-2 bg-[#062639]">
          <a href="#" class="nav-style block" aria-current="false">หนังสือการ์ตูน</a>
          <a href="test1.php" class="nav-style block" aria-current="false">หนังสือความรู้รอบตัว</a>
          <a href="#" class="nav-style block" aria-current="false">นวนิยาย</a>
          <a href="#" class="nav-style block" aria-current="false">นิตยสาร</a>
          <a href="#" class="nav-style block" aria-current="false">หนังสือทั่วไป</a>
        </div>
      </div>
    </div>
  </nav>

  <script>
    /* จะถูกทำทุกครั้งที่โหลดหน้่าใหม่ */
    window.onload = function() {
      /* ตรวจสอบ URL ปัจจุบัน */
      const currentPage = window.location.pathname;
      /* ดึงลิงค์ทั้งหมดที่มี class 'nav-style' */
      const links = document.getElementsByClassName('nav-style');
      /* วนลูปทุกลิงค์ */
      for (let i = 0; i < links.length; i++) {
        const link = links[i];
        let linkPath = link.getAttribute('href');
        linkPath = linkPath.replace('../..', '/SmitiShop');
        /* ถ้า URL ของลิงค์มี folder ตรงกับ URL folder เดียวกันปัจจุบัน */
        if (linkPath === currentPage) {
            /* ตั้งค่า aria-current เป็น 'page' */
            link.setAttribute('aria-current', 'page');
        } else {
            /* ตั้งค่า aria-current เป็น 'false' */
            link.setAttribute('aria-current', 'false');
        }
      }
    };

    /* จัดการ menu product dropdown */
    document.getElementById('hide-button').addEventListener('click', function() {
        var menu = document.getElementById('hide-menu');
        /* ดึงค่าสถานะปัจจุบัน */
        var isExpanded = this.getAttribute('aria-expanded') === 'true';
        /* เปลี่ยนค่า aria-expanded เมื่อปุ่มถูกคลิก */
        this.setAttribute('aria-expanded', !isExpanded);
        /* แสดงเมนู dropdown ตามสถานะ aria-expanded */
        menu.style.display = isExpanded ? 'none' : 'block';
    });

    /* จัดการ menu user */
    document.getElementById('user-button').addEventListener('click', function() {
        var menu = document.getElementById('user-menu');
        /* ดึงค่าสถานะปัจจุบัน */
        var isExpanded = this.getAttribute('aria-expanded') === 'true';
        /* เปลี่ยนค่า aria-expanded เมื่อปุ่มถูกคลิก */
        this.setAttribute('aria-expanded', !isExpanded);
        /* แสดงเมนู dropdown ตามสถานะ aria-expanded */
        menu.style.display = isExpanded ? 'none' : 'block';
    });
  </script>
</body>
</html>