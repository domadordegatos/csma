<?php

    class ventas{
    
        function carrito_temporal($id_producto){
            session_start();
            require_once "conexion.php";
            $conexion=conexion();

        $sql1="SELECT * FROM productos WHERE id_producto = '$id_producto'";
                $result1=mysqli_query($conexion,$sql1);
                $ver=mysqli_fetch_row($result1);
                if(mysqli_num_rows($result1)<=0){//no encontro registrros bd
                  echo 2;//no hay registros
                }else{
                  $articulo=$ver[0]."||".
                            $ver[1]."||".
                            $ver[2]."||";
                            $_SESSION['carrito_temp'][]=$articulo;

                            echo 1;
                }
        }

        function crearventa_prestada(){
          require_once "conexion.php";
          $conexion=conexion();
          date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');

          $datos=$_SESSION['carrito_temp'];$id=$_POST['form1'];

          $id_factura=self::crearfolio();
          $id_user=self::id_usuario($id);
          $saldo=self::saldo($id);
          $r=0;
          $total_pago=0;

          for ($i=0; $i < count($datos) ; $i++) {
            $d=explode("||", $datos[$i]);
            $total_pago=$total_pago+$d[2];
          }
                  $nuevo_saldo = $saldo - $total_pago;
                  $sql="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id'";
                  $ejecutar=mysqli_query($conexion, $sql);

                  for ($i=0; $i < count($datos) ; $i++) {
                    $d=explode("||", $datos[$i]);
        
                    $nuevo_saldo=$saldo-$d[2];
                    $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','1','$id_user','$d[0]','$d[2]','$saldo','$nuevo_saldo','$fecha','$hora')";
                      $r=$r + $result=mysqli_query($conexion,$insert);
                  }
                  return $r;
        }

        function crearventa(){
          require_once "conexion.php";
          $conexion=conexion();
          date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');

          $datos=$_SESSION['carrito_temp'];$id=$_POST['form1'];

          $id_factura=self::crearfolio();
          $id_user=self::id_usuario($id);
          $saldo=self::saldo($id);
          $r=0;
          $total_pago=0;

          for ($i=0; $i < count($datos) ; $i++) {
            $d=explode("||", $datos[$i]);
            $total_pago=$total_pago+$d[2];
          }

              if($total_pago>$saldo){
                  echo -1; /* no tienes saldo suficiente */
              }else{
                  $nuevo_saldo = $saldo - $total_pago;
                  $sql="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id'";
                  $ejecutar=mysqli_query($conexion, $sql);

                  for ($i=0; $i < count($datos) ; $i++) {
                    $d=explode("||", $datos[$i]);
        
                    $nuevo_saldo=$saldo-$d[2];
                    $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','1','$id_user','$d[0]','$d[2]','$saldo','$nuevo_saldo','$fecha','$hora')";
                      $r=$r + $result=mysqli_query($conexion,$insert);
                  }
                  return $r;
              }
        }


        public function crearfolio(){
          require_once "conexion.php";
          $conexion=conexion();
          $sql="SELECT id_factura from movimientos_dinero group by id_factura desc";
          $result=mysqli_query($conexion,$sql);
          $id=mysqli_fetch_row($result)[0];
          if($id=="" or $id==null or $id==0){
            return 1;
          }else{
            return $id + 1;
          }
        }

        public function saldo($id){
          require_once "conexion.php";
          $conexion=conexion();
          $sql="SELECT usuarios.saldo FROM usuarios where usuarios.id_tarjeta = '$id'";
            $ejecutar=mysqli_query($conexion, $sql);
            $ver=mysqli_fetch_row($ejecutar);
            $id=$ver[0];
            return $id;
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
    