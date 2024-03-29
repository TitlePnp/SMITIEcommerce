<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  $_SESSION['productOnCart'] = 0;
  if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
    $id = getID();
    $_SESSION['productOnCart'] = countCart($id);
  } elseif (isset($_SESSION['cart'])) {
    $key = array_keys($_SESSION['cart']);
    $_SESSION['productOnCart'] = count($key);
  }

  function countCart($id) {
    global $connectDB;
    $statusDone = 'Ordered';
    $statusCancel = 'Cancel';
    $stmt = $connectDB->prepare(
      "SELECT COUNT(cl.ProID) AS countCart
      FROM CART_LIST cl
      WHERE cl.CusID = ? AND cl.Status != ? AND cl.Status != ?");
    $stmt->bind_param("iss", $id, $statusDone, $statusCancel);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['countCart'];
    $stmt->close();
    return $result;
  }
?>