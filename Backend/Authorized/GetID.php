<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../vendor/autoload.php';
  use Firebase\JWT\Key;
  use \Firebase\JWT\JWT;

  function getID() {
    if (isset($_SESSION['tokenJWT'])) {
      global $connectDB;
      $jwt = $_SESSION['tokenJWT'];
      $key = "SECRETKEY_SMITIECOM_CLIENT";
      $decoded = JWT::decode($jwt, new Key($key, 'HS256'));
      $username = $decoded->user;
      $stmt = $connectDB->prepare("SELECT ca.CusID FROM CUSTOMER_ACCOUNT ca WHERE ca.UserName = ?");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $id = $result->fetch_assoc()['CusID'];
      $stmt->close();
      return $id;
    } elseif (isset($_SESSION['tokenGoogle'])) {
      global $connectDB;
      $googleId = $_SESSION['tokenGoogle'];
      $stmt = $connectDB->prepare("SELECT ca.CusID FROM CUSTOMER_ACCOUNT ca WHERE ca.GoogleId = ?");
      $stmt->bind_param("s", $googleId);
      $stmt->execute();
      $result = $stmt->get_result();
      $id = $result->fetch_assoc()['CusID'];
      $stmt->close();
      return $id;
    }
  }
?>