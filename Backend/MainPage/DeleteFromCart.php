<?php
  session_start();
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  manageCart();
  function manageCart() {
    $proID = $_POST['proID'];
    if (isset($_SESSION['tokenJWT'])) {
      $jwt = $_SESSION['tokenJWT'];
      $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
      $id = getID($decoded->user);
      removeFromCart($id, $proID);
    } elseif (isset($_SESSION['tokenGoogle'])) {
      $id = getID($_SESSION['tokenGoogle']);
      removeFromCart($id, $proID);
    } else {
      unset($_SESSION['cart'][$proID]);
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

  function removeFromCart($id, $proID) {
    global $connectDB;
    $stmt = $connectDB->prepare("UPDATE CART_LIST cl SET cl.Qty = ? AND Status = ? WHERE cl.CusID = ? AND cl.ProID = ? AND cl.Status = ?");
    $stmt->bind_param("isiis", 0, 'Cancel', $id, $proID, 'OnHand');
    $stmt->execute();
    $stmt->close();
  }
?>