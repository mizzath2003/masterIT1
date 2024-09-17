<?php 
session_start();
session_destroy();
setcookie("admin", "", 1, "/");
header('Location:/login');
?>