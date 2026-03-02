
<?php
session_start();
require_once "../../model/conexion.php";
require_once "../../model/asistencia.php";
$conexion=conexion();

$obj= new asistencia();

  $result=$obj->registros_uno_varios();
  echo $result;

 ?>