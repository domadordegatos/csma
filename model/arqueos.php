<?php 
    class arqueos{

    function rango_almuerzos($fecha_inicio, $fecha_fin){
    if(session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION['datos_uno_varios_arqueo_almuerzos']); // Sesión específica de almuerzos
    require_once "conexion.php";
    $conexion = conexion();

    $sql = "SELECT usuarios.id_usuario, 
            usuarios.nombre,  usuarios.apellido,
            tipo_movimientos_almuerzos.descripcion, 
            movimientos_almuerzos.dinero_cargado, 
            medios_pago.descripcion, 
            movimientos_almuerzos.fecha, 
            movimientos_almuerzos.hora,
            users_admins.user
            FROM movimientos_almuerzos
            JOIN usuarios ON usuarios.id_usuario = movimientos_almuerzos.id_user_movimiento
            JOIN tipo_movimientos_almuerzos ON tipo_movimientos_almuerzos.id_movimiento = movimientos_almuerzos.tipo_venta
            JOIN medios_pago ON medios_pago.id_medio = movimientos_almuerzos.medio_pago
            JOIN users_admins ON users_admins.id_user = movimientos_almuerzos.id_user_venta
            WHERE movimientos_almuerzos.tipo_venta IN (2,4)
            AND (movimientos_almuerzos.fecha >= '$fecha_inicio' AND movimientos_almuerzos.fecha <= '$fecha_fin')
            ORDER BY movimientos_almuerzos.fecha ASC, movimientos_almuerzos.hora ASC";

    $result = mysqli_query($conexion, $sql);

    if($result && mysqli_num_rows($result) > 0){
        while ($ver1 = mysqli_fetch_row($result)){
            $tabla_registros = $ver1[0]."||".$ver1[1]."||".$ver1[2]."||".$ver1[3]."||".
                               $ver1[4]."||".$ver1[5]."||".$ver1[6]."||".$ver1[7]."||".$ver1[8]."||";
            $_SESSION['datos_uno_varios_arqueo_almuerzos'][] = $tabla_registros;
        }
        return 1;
    } else {
        return 2;
    }
}

function registros_rango_fechas($fecha_inicio, $fecha_fin){
    if(session_status() === PHP_SESSION_NONE) session_start();
    unset($_SESSION['datos_uno_varios_arqueo']);
    require_once "conexion.php";
    $conexion = conexion();

    // Tu consulta exacta con las variables del sistema
    $sql = "SELECT 
    usuarios.id_usuario, 
    usuarios.nombre, 
    usuarios.apellido,
    productos.descripcion, 
    movimientos_dinero.total, 
    medios_pago.descripcion, 
    movimientos_dinero.fecha, 
    movimientos_dinero.hora,
    users_admins.user
FROM movimientos_dinero
LEFT JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
LEFT JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
LEFT JOIN medios_pago ON medios_pago.id_medio = movimientos_dinero.id_medio_pago
LEFT JOIN users_admins ON users_admins.id_user = movimientos_dinero.id_admin
WHERE movimientos_dinero.id_producto IN (14, 15, 37, 16)
  AND (movimientos_dinero.fecha >= '$fecha_inicio' AND movimientos_dinero.fecha <= '$fecha_fin')
ORDER BY movimientos_dinero.fecha ASC, movimientos_dinero.hora ASC";

    $result = mysqli_query($conexion, $sql);

    if($result && mysqli_num_rows($result) > 0){
        while ($ver1 = mysqli_fetch_row($result)){
            // Construimos la cadena EXACTAMENTE con 9 campos y el separador ||
            $tabla_registros = $ver1[0]."||".
                               $ver1[1]."||".
                               $ver1[2]."||".
                               $ver1[3]."||".
                               $ver1[4]."||".
                               $ver1[5]."||".
                               $ver1[6]."||".
                               $ver1[7]."||".
                               $ver1[8]."||";
            
            $_SESSION['datos_uno_varios_arqueo'][] = $tabla_registros;
        }
        return 1; // Éxito: El controlador recibirá un 1
    } else {
        return 2; // Vacío: El controlador recibirá un 2
    }
}
    function registros_uno_varios(){
        $id = $_POST['form1']; $fecha = $_POST['form2'];
        unset($_SESSION['datos_uno_varios_arqueo']);
        require_once "conexion.php";
        $conexion=conexion();

        if($id == 'A'){
            $sql1="SELECT usuarios.id_usuario, 
            usuarios.nombre,  usuarios.apellido,
            productos.descripcion, 
            movimientos_dinero.total, 
            medios_pago.descripcion, 
            movimientos_dinero.fecha, 
            movimientos_dinero.hora ,
            users_admins.user
                FROM movimientos_dinero
                JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
                JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
                JOIN medios_pago ON medios_pago.id_medio = movimientos_dinero.id_medio_pago
                join users_admins on users_admins.id_user = movimientos_dinero.id_admin
                WHERE movimientos_dinero.id_producto IN (14, 15, 37, 16)
                AND movimientos_dinero.fecha = '$fecha' -- Filtrar por la fecha específica
                ORDER BY hora asc";
            $result=mysqli_query($conexion,$sql1);
        }else{
            $sql1="SELECT usuarios.id_usuario, 
            usuarios.nombre,  usuarios.apellido,
            productos.descripcion, 
            movimientos_dinero.total, 
            medios_pago.descripcion, 
            movimientos_dinero.fecha, 
            movimientos_dinero.hora ,
            users_admins.user
                FROM movimientos_dinero
                JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
                JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
                JOIN medios_pago ON medios_pago.id_medio = movimientos_dinero.id_medio_pago
                join users_admins on users_admins.id_user = movimientos_dinero.id_admin
                WHERE movimientos_dinero.id_producto IN (14, 15, 37, 16)
                AND DATE_FORMAT(movimientos_dinero.fecha, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') -- Filtrar por el mes actual
                ORDER BY fecha ASC, hora ASC";
            $result=mysqli_query($conexion,$sql1);
        }
            if(mysqli_num_rows($result)<=0){
              echo 2;
            }else{
                         while ($ver1=mysqli_fetch_row($result)){
                             $tabla_registros=$ver1[0]."||".
                                              $ver1[1]."||".
                                              $ver1[2]."||".
                                              $ver1[3]."||".
                                              $ver1[4]."||".
                                              $ver1[5]."||".
                                              $ver1[6]."||".
                                              $ver1[7]."||".
                                              $ver1[8]."||";
                                              $_SESSION['datos_uno_varios_arqueo'][]=$tabla_registros;
                            }
                            echo 1;
    }
}


function registros_uno_varios_almuerzos(){
    $id = $_POST['form1']; $fecha = $_POST['form2'];
    unset($_SESSION['datos_uno_varios_arqueo_almuerzos']);
    require_once "conexion.php";
    $conexion=conexion();

    if($id == 'A'){
        $sql1="SELECT usuarios.id_usuario, 
        usuarios.nombre,  usuarios.apellido,
        tipo_movimientos_almuerzos.descripcion, 
        movimientos_almuerzos.dinero_cargado, 
        medios_pago.descripcion, 
        movimientos_almuerzos.fecha, 
        movimientos_almuerzos.hora,
        users_admins.user
            FROM movimientos_almuerzos
            JOIN usuarios ON usuarios.id_usuario = movimientos_almuerzos.id_user_movimiento
            JOIN tipo_movimientos_almuerzos ON tipo_movimientos_almuerzos.id_movimiento = movimientos_almuerzos.tipo_venta
            JOIN medios_pago ON medios_pago.id_medio = movimientos_almuerzos.medio_pago
            join users_admins on users_admins.id_user = movimientos_almuerzos.id_user_venta
            WHERE movimientos_almuerzos.tipo_venta IN (2,4)
            AND movimientos_almuerzos.fecha = '$fecha' -- Filtrar por la fecha específica
            ORDER BY hora asc;";
        $result=mysqli_query($conexion,$sql1);
    }else{
        $sql1="SELECT usuarios.id_usuario, 
        usuarios.nombre,  usuarios.apellido,
        tipo_movimientos_almuerzos.descripcion, 
        movimientos_almuerzos.dinero_cargado, 
        medios_pago.descripcion, 
        movimientos_almuerzos.fecha, 
        movimientos_almuerzos.hora ,
        users_admins.user
            FROM movimientos_almuerzos
            JOIN usuarios ON usuarios.id_usuario = movimientos_almuerzos.id_user_movimiento
            JOIN tipo_movimientos_almuerzos ON tipo_movimientos_almuerzos.id_movimiento = movimientos_almuerzos.tipo_venta
            JOIN medios_pago ON medios_pago.id_medio = movimientos_almuerzos.medio_pago
            join users_admins on users_admins.id_user = movimientos_almuerzos.id_user_venta
            WHERE movimientos_almuerzos.tipo_venta IN (2,4)
            AND DATE_FORMAT(movimientos_almuerzos.fecha, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') -- Filtrar por el mes actual
            ORDER BY fecha ASC, hora ASC";
        $result=mysqli_query($conexion,$sql1);
    }
        if(mysqli_num_rows($result)<=0){
          echo 2;
        }else{
                     while ($ver1=mysqli_fetch_row($result)){
                         $tabla_registros=$ver1[0]."||".
                                          $ver1[1]."||".
                                          $ver1[2]."||".
                                          $ver1[3]."||".
                                          $ver1[4]."||".
                                          $ver1[5]."||".
                                          $ver1[6]."||".
                                          $ver1[7]."||".
                                          $ver1[8]."||";
                                          $_SESSION['datos_uno_varios_arqueo_almuerzos'][]=$tabla_registros;
                        }
                        echo 1;
}
}


}
?>