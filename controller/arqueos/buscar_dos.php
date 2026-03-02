
<?php
session_start();
require_once "../../model/conexion.php";
require_once "../../model/arqueos.php";
$conexion=conexion();

$obj= new arqueos();

  $result=$obj->registros_uno_varios_almuerzos();
  echo $result;

 ?>