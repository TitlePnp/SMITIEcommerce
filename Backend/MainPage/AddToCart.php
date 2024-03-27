<?php
  session_start();
  require '../../Components/ConnectDB.php';
  manageCart();
  header("Location: ../../Frontend/MainPage/Cart.php"); 
  
  function manageCart() {
    $proID = $_POST['proID'];
    $quantity = $_POST['quantity-hidden'];
    if (isset($_SESSION['tokenJWT'])) {
      $jwt = $_SESSION['tokenJWT'];
      $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
      $id = getID($decoded->user);
      addToCart($id, $proID, $quantity);
    } elseif (isset($_SESSION['tokenGoogle'])) {
      $id = getID($_SESSION['tokenGoogle']);
      addToCart($id, $proID, $quantity);
    } else {
      sessionCart($proID, $quantity);
    }
  }

  function getID($username) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT ca.CusID FROM CUSTOMER_ACCOUNT ca WHERE ca.UserName = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['CusID'];
    $stmt->close();
    return $result;
  }

  function addToCart($id, $proID, $quantity) {
    global $connectDB;
    $leastNum = leatestNum($id) == NULL ? 0 : leatestNum($id);
    if (alreadyInCart($id, $proID) == 0) {
      $stmt = $connectDB->prepare("INSERT INTO CART_LIST (CusID, NumID, ProID, Qty) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("iiii", $id, $leastNum + 1, $proID, $quantity);
      $stmt->execute();
      $stmt->close();
    } else {
      $stmt = $connectDB->prepare("UPDATE CART_LIST cl SET cl.Qty = cl.Qty + ? AND Status = ? WHERE cl.CusID = ? AND cl.ProID = ?");
      $stmt->bind_param("isii", $quantity, 'OnHand', $id, $proID);
      $stmt->execute();
      $stmt->close();
    }
  }

  function sessionCart($proID, $quantity) {
    if (isset($_SESSION['cart'][$proID])) {
      $_SESSION['cart'][$proID] += $quantity;
      if ($_SESSION['cart'][$proID] > checkQty($proID)) {
        $_SESSION['cart'][$proID] = checkQty($proID);
      }
    } else {
      $_SESSION['cart'][$proID] = $quantity;
    }
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

  function alreadyInCart($id, $proID) {
    global $connectDB;
    $stmt = $connectDB->prepare("SELECT COUNT(*) AS alreadyInCart FROM CART_LIST cl WHERE cl.CusID = ? AND cl.ProID = ?");
    $stmt->bind_param("ii", $id, $proID);
    $stmt->execute();
    $result = $stmt->get_result();
    $result = $result->fetch_assoc()['alreadyInCart'];
    $stmt->close();
    return $result;
  }
?>