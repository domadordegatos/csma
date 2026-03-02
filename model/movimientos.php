<?php 
    class movimientos{

// Función para el encabezado del reporte
public function obtener_datos_estudiante($id_usuario) {
    require_once "conexion.php";
    $conexion = conexion();
    $sql = "SELECT 
                u.id_usuario, 
                u.nombre, 
                u.apellido, 
                u.id_tarjeta, 
                u.saldo, 
                g.descripcion AS grado
            FROM usuarios u
            INNER JOIN grados g ON u.grado = g.id_grado
            WHERE u.id_tarjeta = '$id_usuario' OR u.id_usuario = '$id_usuario' 
            LIMIT 1";
    $result = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($result);
}

// Tu consulta de consumos actualizada
public function consulta_pdf_detalle($id_usuario, $f1, $f2) {
    require_once "conexion.php";
    $conexion = conexion();
    
    $sql = "SELECT 
                m.id_factura,
                p.descripcion,
                m.total,
                m.fecha,
                m.hora,
                ua.user as vendedor
            FROM movimientos_dinero m
            INNER JOIN productos p ON m.id_producto = p.id_producto
            INNER JOIN users_admins ua ON m.id_admin = ua.id_user
            WHERE m.id_comprador = (SELECT id_usuario FROM usuarios WHERE id_tarjeta = '$id_usuario' OR id_usuario = '$id_usuario' LIMIT 1)
            AND (m.fecha >= '$f1' AND m.fecha <= '$f2')
            ORDER BY m.id_factura DESC, m.hora ASC";
            
    return mysqli_query($conexion, $sql);
}

      function buscador_datos_estudiante_por_nombre(){
        unset($_SESSION['buscador']);
        $name = $_POST['form1'];
        require_once "conexion.php";
        $conexion = conexion();
        $sql = "SELECT id_tarjeta, nombre, apellido FROM usuarios WHERE (nombre LIKE '%$name%' OR apellido LIKE '%$name%')";
        $result = mysqli_query($conexion, $sql);
        if (mysqli_num_rows($result) <= 0) {
          echo 2;
        } else {
          while ($ver1 = mysqli_fetch_row($result)) {
            $tabla = $ver1[0] . "||" . //id
              $ver1[1] . "||" . //apellidos
              $ver1[2] . "||"; //nombres
            $_SESSION['buscador'][] = $tabla;
          }
          echo 1;
        }
      }

      function agregar_usuario(){
        require_once "conexion.php";
            $conexion=conexion();
        $nombres=$_POST['form1'];$apellidos=$_POST['form2'];$grado=$_POST['form3'];$tarjeta=$_POST['form4'];$saldo=$_POST['form5'];
        $sql="SELECT * FROM usuarios where id_tarjeta = '$tarjeta'";
        $result=mysqli_query($conexion,$sql);
        if(mysqli_num_rows($result)<=0){
          $sql="INSERT INTO usuarios VALUES ('','$nombres','$apellidos','$grado','$tarjeta','$saldo')";
        $ejecutar=mysqli_query($conexion, $sql);
        if($ejecutar){
          $id_usuario=self::id_usuario($tarjeta);
          $sql="INSERT INTO cartera_almuerzos VALUES ('','$id_usuario',0,0,0,0,0)";
          $ejecutar=mysqli_query($conexion, $sql);
            if($ejecutar){
              echo 1;
            }else{
              echo 3;
            }
        }else{
          echo 2;
        }
        }else{
        echo 0;
      }
      }

        function r_movimientos(){
            require_once "conexion.php";
            $conexion=conexion();
            $id=$_POST['form1'];

            if($id == 'A'){
                unset($_SESSION['consulta_temp']);
                $sql="SELECT * FROM usuarios
                JOIN grados ON grados.id_grado = usuarios.grado ORDER BY saldo ASC";
                $result=mysqli_query($conexion,$sql);
                if(mysqli_num_rows($result)<=0){
                    echo 2;//no existen datos del usuario
                  }else{
                    while ($ver1=mysqli_fetch_row($result)){
                    $tabla=$ver1[0]."||".
                           $ver1[1]."||".
                           $ver1[2]."||".
                           $ver1[4]."||".
                           $ver1[5]."||".
                           $ver1[7]."||";
                       $_SESSION['consulta_temp'][]=$tabla;
                     }
                     echo 1;
                  }
            }else{
                unset($_SESSION['consulta_temp']);
                require_once "conexion.php";
                $conexion=conexion();
                $id=$_POST['form1'];
                if(is_numeric($id)){
                  $id_usuario=self::id_usuario($id);
                }else{
                  $id_usuario = $id;
                }
                $sql="SELECT * FROM usuarios
                JOIN grados ON grados.id_grado = usuarios.grado WHERE (id_usuario = '$id_usuario' or apellido LIKE '%$id%' OR nombre LIKE '%$id%') ORDER BY saldo ASC";
                $result=mysqli_query($conexion,$sql);
                if(mysqli_num_rows($result)<=0){
                    echo 2;//no existen datos del usuario
                  }else{
                    while ($ver1=mysqli_fetch_row($result)){
                    $tabla=$ver1[0]."||".
                           $ver1[1]."||".
                           $ver1[2]."||".
                           $ver1[4]."||".
                           $ver1[5]."||".
                           $ver1[7]."||";
                       $_SESSION['consulta_temp'][]=$tabla;
                     }
                     echo 1;
                  }
            }
        }

        
        function r_usuarios(){
          require_once "conexion.php";
          $conexion=conexion();
          $id=$_POST['form1'];

          if($id == 'A'){
              unset($_SESSION['consulta_users']);
              $sql="SELECT usuarios.id_usuario, usuarios.nombre, usuarios.apellido, usuarios.id_tarjeta, grados.descripcion FROM usuarios
              JOIN grados ON grados.id_grado = usuarios.grado ORDER BY usuarios.id_usuario desc";
              $result=mysqli_query($conexion,$sql);
              if(mysqli_num_rows($result)<=0){
                  echo 2;//no existen datos del usuario
                }else{
                  while ($ver1=mysqli_fetch_row($result)){
                  $tabla=$ver1[0]."||".
                         $ver1[1]."||".
                         $ver1[2]."||".
                         $ver1[3]."||".
                         $ver1[4]."||";
                     $_SESSION['consulta_users'][]=$tabla;
                   }
                   echo 1;
                }
          }
      }


        function r_movimiento_factura(){
            unset($_SESSION['consulta_temp_factura']);
                $id=$_POST['form1'];
                require_once "conexion.php";
                $conexion=conexion();
                $sql="SELECT movimientos_dinero.id_factura, users_admins.user,
                productos.descripcion, movimientos_dinero.total, movimientos_dinero.valor_anterior, movimientos_dinero.valor_nuevo
                FROM movimientos_dinero
                 JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
                 JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
                 JOIN users_admins ON users_admins.id_user = movimientos_dinero.id_admin
               WHERE movimientos_dinero.id_factura = '$id'";
                $result=mysqli_query($conexion,$sql);
                if(mysqli_num_rows($result)<=0){
                    echo 2;//no existen datos del usuario
                  }else{
                    while ($ver1=mysqli_fetch_row($result)){
                    $tabla=$ver1[0]."||".
                           $ver1[1]."||".
                           $ver1[2]."||".
                           $ver1[3]."||".
                           $ver1[4]."||".
                           $ver1[5]."||";
                       $_SESSION['consulta_temp_factura'][]=$tabla;
                     }
                     echo 1;
                  }
        }

        function r_movimiento_detallado(){
 
                unset($_SESSION['consulta_temp_detallada']);
                $id=$_POST['form1'];
                require_once "conexion.php";
                $conexion=conexion();
                $sql="SELECT movimientos_dinero.id_factura, users_admins.user,
                productos.descripcion, movimientos_dinero.total, movimientos_dinero.valor_anterior, movimientos_dinero.valor_nuevo,
                movimientos_dinero.fecha, movimientos_dinero.hora
                FROM movimientos_dinero
                JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
                JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
                JOIN users_admins ON users_admins.id_user = movimientos_dinero.id_admin
                WHERE usuarios.id_usuario = '$id'
                GROUP BY id_factura ORDER BY id_factura desc";
                $result=mysqli_query($conexion,$sql);
                if(mysqli_num_rows($result)<=0){
                    echo 2;//no existen datos del usuario
                  }else{
                    while ($ver1=mysqli_fetch_row($result)){
                    $tabla=$ver1[0]."||".
                           $ver1[1]."||".
                           $ver1[2]."||".
                           $ver1[3]."||".
                           $ver1[4]."||".
                           $ver1[5]."||".
                           $ver1[6]."||".
                           $ver1[7]."||";
                       $_SESSION['consulta_temp_detallada'][]=$tabla;
                     }
                     echo 1;
                  }
            
        }

        public function id_usuario($id_user){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT id_usuario from usuarios where id_tarjeta = '$id_user'";
            $result=mysqli_query($conexion,$sql);
            $id=mysqli_fetch_row($result)[0];
              return $id;
          }


    }

?>