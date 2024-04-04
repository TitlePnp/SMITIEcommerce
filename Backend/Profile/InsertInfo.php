<?php
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  require '../../Components/ConnectDB.php';
  require '../../Backend/Authorized/GetID.php';
  $id = $_POST['id'];
  echo $id;
  $fName = $_POST['firstName'];
  $lName = $_POST['lastName'];
  $sex = isset($_POST['sex']) ? $_POST['sex'] : "" ;
  $tel = $_POST['phone'];
  $address = "{$_POST['address']} ตำบล/แขวง {$_POST['district']} อำเภอ/เขต {$_POST['subdistrict']}";
  $province = $_POST['province'];
  $postcode = $_POST['postcode'];
  $stmt = $connectDB->prepare("UPDATE CUSTOMER SET CusFName = ?, CusLName = ?, Sex = ?, Tel = ?, Address = ?, Province = ?, Postcode = ? WHERE CusID = ?");
  $stmt->bind_param("sssssssi", $fName, $lName, $sex, $tel, $address, $province, $postcode, $id);
  $stmt->execute();
  $stmt->close();
  $connectDB->close();
  // header("Location: ../../Frontend/MainPage/Home.php");
?>