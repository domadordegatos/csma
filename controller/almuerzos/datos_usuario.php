<?php
require_once "../../model/conexion.php";
require_once "../../model/almuerzos.php";
$conexion=conexion();

    $obj= new edicion_usuario();

    echo json_encode($obj->ob_datos_usuario())
 ?>