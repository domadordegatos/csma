<?php
session_start();
require_once "../../model/conexion.php";
require_once "../../model/almuerzos.php";
$conexion=conexion();

$obj= new edicion_usuario();

  $result=$obj->r_movimiento_detallado();
  echo $result;

 ?>