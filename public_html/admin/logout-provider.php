<?php
  session_start();
  session_destroy();
  header("Location: /provider/login.php");
  exit;
?>