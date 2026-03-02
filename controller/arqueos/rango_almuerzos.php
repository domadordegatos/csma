<?php
session_start();
require_once "../../model/arqueos.php";
$obj = new arqueos();

$fecha1 = $_GET['start_date'];
$fecha2 = $_GET['end_date'];

$res = $obj->rango_almuerzos($fecha1, $fecha2);
echo $res;
?>