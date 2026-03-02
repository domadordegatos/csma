<?php
session_start();
require_once "../../model/arqueos.php"; // Verifica que la ruta a tu clase sea correcta
$obj = new arqueos();

// Recibimos las fechas enviadas por el método GET desde el AJAX
$fecha1 = $_GET['start_date'];
$fecha2 = $_GET['end_date'];

// Ejecutamos la función del modelo
$res = $obj->registros_rango_fechas($fecha1, $fecha2);

// Devolvemos la respuesta (1 para éxito, 2 para vacío)
echo $res;
?>