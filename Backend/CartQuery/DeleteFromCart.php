<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  cancelOnHand();
  function cancelOnHand() {
    $proID = $_POST['proID'];
    if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
      $id = getID();
      removeFromCart($id, $proID);
    } else {
      unset($_SESSION['cart'][$proID]);
    }
  }

  function removeFromCart($id, $proID) {
    echo "removeFromCart";
    echo $proID;
    global $connectDB;
    $qty = 0;
    $newStatus = 'Cancel';
    $status = 'OnHand';
    $stmt = $connectDB->prepare("UPDATE CART_LIST cl SET cl.Qty = ?, Status = ? WHERE cl.CusID = ? AND cl.ProID = ? AND cl.Status = ?");
    $stmt->bind_param("isiis", $qty, $newStatus, $id, $proID, $status);
    $stmt->execute();
    $stmt->close();
  }
?>