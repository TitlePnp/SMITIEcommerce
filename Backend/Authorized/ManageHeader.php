<?php
  if (isset($role)) {
    if ($role == "User") {
      include '../../Components/Header/HeaderUser.html';
    } else if ($role == "Admin") {
      include '../../Components/Header/HeaderUserAdmin.html';
    } elseif ($role == "SuperAdmin") {
      include '../../Components/Header/HeaderSuperAdmin.html';
    }
  } else {
    include '../../Components/Header/HeaderGuest.html';
  }
?>