  
<?php
session_start();
$index=$_POST['ind'];
unset($_SESSION['carrito_temp'][$index]);
$datos=array_values($_SESSION['carrito_temp']);
unset($_SESSION['carrito_temp']);
$_SESSION['carrito_temp']=$datos;
 ?>