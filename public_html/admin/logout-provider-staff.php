<?php
  session_start();
  session_destroy();
  header("Location: /staff/login.php");
  exit;
?>