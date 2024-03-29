<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  
  addUpdateOnHand();
  function addUpdateOnHand() {
    $proID = $_POST['proID'];
    $quantity = $_POST['quantityHidden'];
    if (isset($_SESSION['tokenJWT']) || isset($_SESSION['tokenGoogle'])) {
      $id = getID();
      addUpToCart($id, $proID, $quantity);
    } else {
      sessionCart($proID, $quantity);
    }
  }

  function addUpToCart($id, $proID, $quantity) {
    global $connectDB;
    $leastNum = leatestNum($id) == NULL ? 1 : (leatestNum($id)+1);
    if (alreadyInCart($id, $proID) == 0) {
      $stmt = $connectDB->prepare("INSERT INTO CART_LIST (CusID, NumID, ProID, Qty) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("iiii", $id, $leastNum, $proID, $quantity);
      $stmt->execute();
      $stmt->close();
    } else {
      $onHandQty = checkQtyOnHand($proID);
      $qtyUpdate = ($onHandQty + $quantity);
      $stockQty = checkQty($proID);
      if ($qtyUpdate >= $stockQty) {
        $qtyUpdate = $stockQty;
      }

      if (isset($_POST['update']) && $_POST['update'] == 'true') {
        $qtyUpdate = $quantity;
      }
      $statusUpdate = "OnHand";
      $status = 'Ordered';
      $stmt = $connectDB->prepare("UPDATE CART_LIST cl SET cl.Qty = ?, Status = ? WHERE cl.CusID = ? AND cl.ProID = ? AND cl.Status != ?");
      $stmt->bind_param("isiis", $qtyUpdate, $statusUpdate, $id, $proID, $status);
      $stmt->execute();
      $stmt->close();
    }
  }

  function sessionCart($proID, $quantity) {
    if (isset($_SESSION['cart'][$proID])) {
      $_SESSION['cart'][$proID] += $quantity;
      if ($_SESSION['cart'][$proID] >= checkQty($proID)) {
        $_SESSION['cart'][$proID] = checkQty($proID);
      }
    } else {
      $_SESSION['cart'][$proID] = $quantity;
    }

    if (isset($_POST['update']) && $_POST['update'] == 'true') {
      $_SESSION['cart'][$proID] = $quantity;
    }
  }

  function checkCart($id) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(*) AS haveCart FROM CART_LIST cl WHERE cl.CusID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['haveCart'];
    $stmt->close();
    return $result;
  }
  
  function alreadyInCart($id, $proID) {
    global $connectDB;
    $status = 'Ordered';
    $stmt = $connectDB->prepare("SELECT COUNT(*) AS alreadyInCart FROM CART_LIST cl WHERE cl.CusID = ? AND cl.ProID = ? AND cl.Status != ?");
    $stmt->bind_param("iis", $id, $proID, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['alreadyInCart'];
    $stmt->close();
    return $result;
  }

  function leatestNum($id) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT MAX(NumID) AS LatestNumID FROM CART_LIST cl WHERE cl.CusID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['LatestNumID'];
    $stmt->close();
    return $result;
  }

  function checkQtyOnHand($proID) {
    global $connectDB;
    $status = 'OnHand';
    $stmt = $connectDB->prepare("SELECT cl.Qty FROM CART_LIST cl WHERE cl.ProID = ? AND cl.Status = ?");
    $stmt->bind_param("is", $proID, $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['Qty'];
    $stmt->close();
    return $result;
  }

  function checkQty($proID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT p.StockQty FROM PRODUCT p WHERE p.ProID = ?");
    $stmt->bind_param("i", $proID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['StockQty'];
    $stmt->close();
    return $result;
  }
?>