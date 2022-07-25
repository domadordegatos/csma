<?php
require_once "../../model/conexion.php";
require_once "../../model/almuerzos.php";

    $obj= new edicion_usuario();

    $idart=$_POST['idart'];
    $result=$obj->carrito_temporal($idart);
    echo $result;
 ?>