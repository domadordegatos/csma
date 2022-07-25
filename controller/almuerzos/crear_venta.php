<?php
session_start();
require_once "../../model/conexion.php";
require_once "../../model/almuerzos.php";
$conexion=conexion();

$obj= new edicion_usuario();

if (count($_SESSION['carrito_temp_almuerzos'])==0) {
  echo 0;
}else{
  $result=$obj->crearventa();
  unset($_SESSION['carrito_temp_almuerzos']);
  echo $result;
}
 ?>