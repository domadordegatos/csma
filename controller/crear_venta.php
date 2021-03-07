<?php
session_start();
require_once "../model/conexion.php";
require_once "../model/ventas.php";
$conexion=conexion();

$obj= new ventas();

if (count($_SESSION['carrito_temp'])==0) {
  echo 0;
}else{
  $result=$obj->crearventa();
  unset($_SESSION['carrito_temp']);
  echo $result;
}
 ?>