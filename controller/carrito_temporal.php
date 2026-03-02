<?php
require_once "../model/conexion.php";
require_once "../model/ventas.php";

    $obj= new ventas();

    $idart=$_POST['idart'];
    $result=$obj->carrito_temporal($idart);
    echo $result;
 ?>