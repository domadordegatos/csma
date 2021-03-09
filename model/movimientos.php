<?php 
    class movimientos{

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
                $id_usuario=self::id_usuario($id);
                $sql="SELECT * FROM usuarios
                JOIN grados ON grados.id_grado = usuarios.grado WHERE id_usuario = '$id_usuario' ORDER BY saldo ASC";
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

        function r_movimientos_fecha(){
          unset($_SESSION['consulta_temp_factura']);
                $id=$_POST['form2'];
                $fecha=$_POST['form1'];
                require_once "conexion.php";
                $conexion=conexion();
                $id_usuario=self::id_usuario($id);
                $sql="SELECT movimientos_dinero.id_factura, users_admins.user,
                productos.descripcion, movimientos_dinero.total, movimientos_dinero.valor_anterior, movimientos_dinero.valor_nuevo,
                movimientos_dinero.fecha, movimientos_dinero.hora
                FROM movimientos_dinero
                JOIN usuarios ON usuarios.id_usuario = movimientos_dinero.id_comprador
                JOIN productos ON productos.id_producto = movimientos_dinero.id_producto
                JOIN users_admins ON users_admins.id_user = movimientos_dinero.id_admin
                WHERE movimientos_dinero.id_comprador = '$id' AND movimientos_dinero.fecha = '$fecha'
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
                           $ver1[5]."||";
                       $_SESSION['consulta_temp_factura'][]=$tabla;
                     }
                     echo 1;
                  }
        }

        function r_movimiento_factura(){
            unset($_SESSION['consulta_temp_factura']);
                $id=$_POST['form1'];
                require_once "conexion.php";
                $conexion=conexion();
                $id_usuario=self::id_usuario($id);
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
            $fecha=$_POST['form2'];

            if($fecha == 'A'){
                unset($_SESSION['consulta_temp_detallada']);
                $id=$_POST['form1'];
                require_once "conexion.php";
                $conexion=conexion();
                $id_usuario=self::id_usuario($id);
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
            }else{

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