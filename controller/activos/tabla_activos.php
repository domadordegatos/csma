<?php
session_start();
require_once "../../model/conexion.php";
require_once "../../model/activos_inventario.php";
$conexion=conexion();

$obj= new activos_inventario();

  $result=$obj->tabla_activos_csma();
  echo $result;

 ?>