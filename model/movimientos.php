<?php 
    class movimientos{

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