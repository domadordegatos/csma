<?php
session_start();
require_once "../model/conexion.php";
require_once "../model/edicion_usuario.php";
$conexion=conexion();

$obj= new edicion_usuario();

  $result=$obj->pagar_deudas();
  echo $result;

 ?>