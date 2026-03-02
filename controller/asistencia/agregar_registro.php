<?php
require_once "../../model/conexion.php";
require_once "../../model/asistencia.php";

    $obj= new asistencia();

    $id_user=$_POST['id'];
    $result=$obj->agregar_registro($id_user);
    echo $result;
 ?>