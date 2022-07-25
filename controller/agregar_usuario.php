<?php
session_start();
require_once "../model/conexion.php";
require_once "../model/movimientos.php";
$conexion=conexion();

$obj= new movimientos();

  $result=$obj->agregar_usuario();
  echo $result;

 ?>