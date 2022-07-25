  
<?php
session_start();
$index=$_POST['ind'];
unset($_SESSION['carrito_temp_almuerzos'][$index]);
$datos=array_values($_SESSION['carrito_temp_almuerzos']);
unset($_SESSION['carrito_temp_almuerzos']);
$_SESSION['carrito_temp_almuerzos']=$datos;
 ?>