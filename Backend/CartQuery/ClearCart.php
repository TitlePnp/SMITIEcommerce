<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  function updateOnHand($ProIds) {
    if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
      $id = getID();
      updateOnCart($id, $ProIds);
    } else {
      foreach ($ProIds as $proID) {
        unset($_SESSION['cart'][$proID]);
      }
    }
  }

  function updateOnCart($id, $ProIds) {
    global $connectDB;
    $newStatus = 'Ordered';
    $status = 'OnHand';
    foreach ($ProIds as $proID) {
      $stmt = $connectDB->prepare("UPDATE CART_LIST cl SET Status = ? WHERE cl.CusID = ? AND cl.ProID = ? AND cl.Status = ?");
      $stmt->bind_param("siis", $newStatus, $id, $proID, $status);
      $stmt->execute();
      $stmt->close();
    }
  }
?>