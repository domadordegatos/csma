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

  // Admin desde sesión (misma conexión)
  $user = $_SESSION['user'];
  $sqlAdmin="SELECT id_user FROM users_admins WHERE user = '$user' LIMIT 1";
  $resAdmin=mysqli_query($conexion,$sqlAdmin);
  if(!$resAdmin){ return 0; }
  $rowAdmin=mysqli_fetch_row($resAdmin);
  if(!$rowAdmin){ return 0; }
  $id_admin = $rowAdmin[0];

  $datos = $_SESSION['carrito_temp'];
  $id_tarjeta = $_POST['form1']; // tarjeta del estudiante

  // Bloqueo corto para evitar cruces (ventas / recargas / préstamos)
  $lockOk = mysqli_query($conexion, "LOCK TABLES movimientos_dinero WRITE, usuarios WRITE");
  if(!$lockOk){ return 0; }

  try {
    // Folio atómico bajo lock
    $resFolio = mysqli_query($conexion, "SELECT COALESCE(MAX(id_factura),0)+1 AS next_id FROM movimientos_dinero");
    if(!$resFolio){ return 0; }
    $rowFolio = mysqli_fetch_assoc($resFolio);
    $id_factura = (int)$rowFolio['next_id'];

    // Datos del estudiante bajo lock
    $sqlUser="SELECT id_usuario, saldo FROM usuarios WHERE id_tarjeta = '$id_tarjeta' LIMIT 1";
    $resUser=mysqli_query($conexion,$sqlUser);
    if(!$resUser){ return 0; }
    $rowUser=mysqli_fetch_assoc($resUser);
    if(!$rowUser){ return 0; }
    $id_user = $rowUser['id_usuario'];
    $saldo = (float)$rowUser['saldo'];

    // Total de la compra
    $total_pago=0;
    for ($i=0; $i < count($datos) ; $i++) {
      $d=explode("||", $datos[$i]);
      $total_pago += (float)$d[2];
    }

    // Prestada: puede quedar negativo
    $nuevo_saldo = $saldo - $total_pago;

    // Actualiza saldo
    $sqlUpd="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id_tarjeta'";
    $okUpd=mysqli_query($conexion, $sqlUpd);
    if(!$okUpd){ return 0; }

    // Inserta movimientos: valor_nuevo = saldo final (el mismo de usuarios)
    $r=0;
    for ($i=0; $i < count($datos) ; $i++) {
      $d=explode("||", $datos[$i]);
      $valor_nuevo = $nuevo_saldo;

      $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_user','$d[0]','$d[2]','$saldo','$valor_nuevo','$fecha','$hora',null)";
      $okIns = mysqli_query($conexion,$insert);
      if(!$okIns){ return 0; }
      $r++;
    }

    return $r;

  } finally {
    mysqli_query($conexion, "UNLOCK TABLES");
  }
}


/*         function crearventa_prestada(){
          require_once "conexion.php";
          $conexion=conexion();
          date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');
            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

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
                    $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_user','$d[0]','$d[2]','$saldo','$nuevo_saldo','$fecha','$hora',null)";
                      $r=$r + $result=mysqli_query($conexion,$insert);
                  }
                  return $r;
        } */

/*         function crearventa(){
          require_once "conexion.php";
          $conexion=conexion();
          date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');

            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

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
                  echo -1; 
              }else{
                  $nuevo_saldo = $saldo - $total_pago;
                  $sql="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id'";
                  $ejecutar=mysqli_query($conexion, $sql);

                  for ($i=0; $i < count($datos) ; $i++) {
                    $d=explode("||", $datos[$i]);
        
                    $nuevo_saldo=$saldo-$d[2];
                    $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_user','$d[0]','$d[2]','$saldo','$nuevo_saldo','$fecha','$hora',null)";
                      $r=$r + $result=mysqli_query($conexion,$insert);
                  }
                  return $r;
              }
        } */

              function crearventa(){
              require_once "conexion.php";
              $conexion=conexion();
              date_default_timezone_set('America/Bogota');

              $time = time();
              $hora = date("H:i:s",$time);
              $fecha= date('Y-m-d');

              // Admin desde la sesión (esto NO depende del lock)
              $user = $_SESSION['user'];
              $sqlAdmin="SELECT id_user FROM users_admins WHERE user = '$user' LIMIT 1";
              $resAdmin=mysqli_query($conexion,$sqlAdmin);
              if(!$resAdmin){ return 0; }
              $rowAdmin=mysqli_fetch_row($resAdmin);
              if(!$rowAdmin){ return 0; }
              $id_admin = $rowAdmin[0];

              $datos=$_SESSION['carrito_temp'];
              $id_tarjeta=$_POST['form1']; // tarjeta del estudiante

              // --- ZONA CRÍTICA: aquí sí bloqueamos para evitar cruces ---
              // OJO: LOCK TABLES es por conexión, así que todo lo que toque estas tablas
              // debe hacerse con ESTE mismo $conexion.
              $lockOk = mysqli_query($conexion, "LOCK TABLES movimientos_dinero WRITE, usuarios WRITE");
              if(!$lockOk){ return 0; }

              try {
                // Folio ATÓMICO bajo lock (evita que 2 cajas generen el mismo id_factura)
                $resFolio = mysqli_query($conexion, "SELECT COALESCE(MAX(id_factura),0)+1 AS next_id FROM movimientos_dinero");
                if(!$resFolio){ return 0; }
                $rowFolio = mysqli_fetch_assoc($resFolio);
                $id_factura = (int)$rowFolio['next_id'];

                // Datos del estudiante bajo lock (evita cruces con recargas/préstamos)
                $sqlUser="SELECT id_usuario, saldo FROM usuarios WHERE id_tarjeta = '$id_tarjeta' LIMIT 1";
                $resUser=mysqli_query($conexion,$sqlUser);
                if(!$resUser){ return 0; }
                $rowUser=mysqli_fetch_assoc($resUser);
                if(!$rowUser){ return 0; }
                $id_user = $rowUser['id_usuario'];
                $saldo = (float)$rowUser['saldo'];

                // Total
                $total_pago=0;
                for ($i=0; $i < count($datos) ; $i++) {
                  $d=explode("||", $datos[$i]);
                  $total_pago = $total_pago + (float)$d[2];
                }

                if ($saldo < $total_pago) {
                  return 0;
                }

                // Actualiza saldo
                $nuevo_saldo = $saldo - $total_pago;
                $sqlUpd="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id_tarjeta'";
                $okUpd=mysqli_query($conexion, $sqlUpd);
                if(!$okUpd){ return 0; }

                // Inserta movimientos
                $r=0;
                for ($i=0; $i < count($datos) ; $i++) {
                  $d=explode("||", $datos[$i]);

                  // (Tu lógica original) saldo después de cada ítem desde el saldo original
                  // Valor nuevo = saldo final guardado en usuarios
                  $nuevo_saldo_item = $nuevo_saldo;

                  $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_user','$d[0]','$d[2]','$saldo','$nuevo_saldo_item','$fecha','$hora',null)";

                  $okIns = mysqli_query($conexion,$insert);
                  if(!$okIns){ return 0; }
                  $r++;
                }

                return $r;

              } finally {
                // Pase lo que pase, liberar el bloqueo
                mysqli_query($conexion, "UNLOCK TABLES");
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

        public function id_admin($id_admin){
          require_once "conexion.php";
          $conexion=conexion();
          $sql="SELECT id_user from users_admins where user = '$id_admin'";
          $result=mysqli_query($conexion,$sql);
          $id=mysqli_fetch_row($result)[0];
            return $id;
        }
    
    }
    