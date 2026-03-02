<?php
session_start();
require_once "../model/conexion.php";
require_once "../model/login.php";
$conexion=conexion();

$obj= new login();

  $result=$obj->iniciar();
  echo $result;

 ?>