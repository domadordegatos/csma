<?php

    class edicion_usuario{

        function prestar_dinero(){
            require_once "conexion.php";
            $conexion=conexion();
            date_default_timezone_set('America/Bogota');
              $time = time();
              $hora = date("H:i:s",$time);
              $fecha= date('Y-m-d');
  
              $user = $_SESSION['user'];
              $id_admin=self::id_admin($user);
  
            $datos=$_SESSION['carrito_temp_almuerzos'];$id=$_POST['form1'];
  
            $id_factura=self::crearfolio();
            $id_user=self::id_usuario($id);
            $valor_antiguo=self::valor_antiguo($id_user);
            $r=0;
            $total_pago=0; $c=0; $pago_final=0;
            $total_pe=0; $total_gr=0; $total_do=0; $total_sa=0;
            $nuevo_pe=0; $nuevo_gr=0; $nuevo_do=0; $nuevo_sa=0;
            for ($i=0; $i < count($datos) ; $i++) { //aqui sacamos la cuenta de cada almuerzo
              $d=explode("||", $datos[$i]);
              if($d[0]==21){$total_pe=$total_pe+1;}
              if($d[0]==22){$total_gr=$total_gr+1;}
              if($d[0]==23){$total_do=$total_do+1;}
              if($d[0]==24){$total_sa=$total_sa+1;}
              $c=$c+1;
            }
            $pre_pe=self::almuerzo_previo(2,$id_user);
            $pre_gr=self::almuerzo_previo(3,$id_user);
            $pre_do=self::almuerzo_previo(4,$id_user);
            $pre_sa=self::almuerzo_previo(5,$id_user);

            $precio_pe=self::valores_almuerzos(21);
            $precio_gr=self::valores_almuerzos(22);
            $precio_do=self::valores_almuerzos(23);
            $precio_sa=self::valores_almuerzos(24);
            
            $pago_final = ($total_pe*$precio_pe)+($total_gr*$precio_gr)+($total_do*$precio_do)+($total_sa*$precio_sa);
            $pago_final_2 = $valor_antiguo - $pago_final;
  
                    $nuevo_pe = $pre_pe - $total_pe;
                    $nuevo_gr = $pre_gr - $total_gr;
                    $nuevo_do = $pre_do - $total_do;
                    $nuevo_sa = $pre_sa - $total_sa;
                    
                    $sql="UPDATE cartera_almuerzos SET pequeno = '$nuevo_pe', grande = '$nuevo_gr', docente = '$nuevo_do', saludable = '$nuevo_sa', cartera = '$pago_final_2' WHERE id_user = '$id_user'";
                    $ejecutar=mysqli_query($conexion, $sql);
                    if($ejecutar){
                        for ($i=0; $i < count($datos) ; $i++) {
                            $d=explode("||", $datos[$i]);
                
                            $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_factura','$id_admin','$id_user',3,$d[0],'$c','$valor_antiguo','$valor_antiguo','$fecha','$hora')";
                              $r=$r + $result=mysqli_query($conexion,$insert);
                          }
                          if($r){echo 1;}
                    }
          }

        function crearventa(){
            require_once "conexion.php";
            $conexion=conexion();
            date_default_timezone_set('America/Bogota');
              $time = time();
              $hora = date("H:i:s",$time);
              $fecha= date('Y-m-d');
  
              $user = $_SESSION['user'];
              $id_admin=self::id_admin($user);
  
            $datos=$_SESSION['carrito_temp_almuerzos'];$id=$_POST['form1'];
  
            $id_factura=self::crearfolio();
            $id_user=self::id_usuario($id);
            $valor_antiguo=self::valor_antiguo($id_user);
            $r=0;
            $total_pago=0; $c=0;
            $total_pe=0; $total_gr=0; $total_do=0; $total_sa=0;
  
            for ($i=0; $i < count($datos) ; $i++) { //aqui sacamos la cuenta de cada almuerzo
              $d=explode("||", $datos[$i]);
              if($d[0]==21){$total_pe=$total_pe+1;}
              if($d[0]==22){$total_gr=$total_gr+1;}
              if($d[0]==23){$total_do=$total_do+1;}
              if($d[0]==24){$total_sa=$total_sa+1;}
              $c=$c+1;
            }
            $pre_pe=self::almuerzo_previo(2,$id_user);
            $pre_gr=self::almuerzo_previo(3,$id_user);
            $pre_do=self::almuerzo_previo(4,$id_user);
            $pre_sa=self::almuerzo_previo(5,$id_user);

            if($total_pe > $pre_pe){ return 7;}
            if($total_gr > $pre_gr){ return 7;}
            if($total_do > $pre_do){ return 7;}
            if($total_sa > $pre_sa){ return 7;}
  
                    $nuevo_pe = $pre_pe - $total_pe;
                    $nuevo_gr = $pre_gr - $total_gr;
                    $nuevo_do = $pre_do - $total_do;
                    $nuevo_sa = $pre_sa - $total_sa;
                    
                    $sql="UPDATE cartera_almuerzos SET pequeno = '$nuevo_pe', grande = '$nuevo_gr', docente = '$nuevo_do', saludable = '$nuevo_sa'  WHERE id_user = '$id_user'";
                    $ejecutar=mysqli_query($conexion, $sql);
                    if($ejecutar){
                        for ($i=0; $i < count($datos) ; $i++) {
                            $d=explode("||", $datos[$i]);
                
                            $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_factura','$id_admin','$id_user',1,$d[0],'$c','$valor_antiguo','$valor_antiguo','$fecha','$hora')";
                              $r=$r + $result=mysqli_query($conexion,$insert);
                          }
                          if($r){echo 1;}
                    }
          }

        function carrito_temporal($id_producto){
            session_start();
            require_once "conexion.php";
            $conexion=conexion();

        $sql1="SELECT * FROM categoria_movimientos_almuerzos WHERE id_categoria = '$id_producto'";
                $result1=mysqli_query($conexion,$sql1);
                $ver=mysqli_fetch_row($result1);
                if(mysqli_num_rows($result1)<=0){//no encontro registrros bd
                  echo 2;//no hay registros
                }else{
                  $articulo=$ver[0]."||".
                            $ver[1]."||".
                            $ver[2]."||";
                            $_SESSION['carrito_temp_almuerzos'][]=$articulo;

                            echo 1;
                }
        }

        function ob_datos_usuario(){
            require_once "conexion.php";
            $conexion=conexion();
            $id=$_POST['form1'];

            $sql1="SELECT * FROM usuarios 
            JOIN grados ON grados.id_grado = usuarios.grado
            JOIN cartera_almuerzos ON cartera_almuerzos.id_user = usuarios.id_usuario
             where usuarios.id_tarjeta = '$id'";
                $result=mysqli_query($conexion,$sql1);
                $ver=mysqli_fetch_row($result);
                $datos=array( "0" => $ver[1],
                              "1" => $ver[2],
                              "2" => $ver[7],
                              "3" => $ver[4],
                              "4" => $ver[14],
                              "6" => $ver[0],
                              "7" => $ver[10],
                              "8" => $ver[11],
                              "9" => $ver[12],
                              "10" => $ver[13]
                                );
                                return $datos;
        }

        function recargar(){
            date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');
            require_once "conexion.php";
            $conexion=conexion();
            $pequeno=$_POST['form1']; $grande=$_POST['form2']; $docente=$_POST['form3']; $saludable=$_POST['form4']; $id=$_POST['form5'];
            $id_factura=self::crearfolio();
            $id_usuario=self::id_usuario($id);
            
            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

            $sql="SELECT usuarios.saldo FROM usuarios where usuarios.id_tarjeta = '$id'";
            $ejecutar=mysqli_query($conexion, $sql);
            $ver=mysqli_fetch_row($ejecutar);
            if(mysqli_num_rows($ejecutar)<=0){ /* no hay registro del usuario */
                echo 2; /* no existe el usuario en bd */
            }else{/* hay registros del usuario */
                if($pequeno<0 || $grande<0 || $docente<0 || $saludable<0){/* estas tratando de ingresar un valor nulo */
                    echo 3;
                }else{
                    $pe=self::valores_almuerzos(1);
                    $gr=self::valores_almuerzos(2);
                    $do=self::valores_almuerzos(3);
                    $sa=self::valores_almuerzos(4);
                    $t_p=($pequeno*$pe)+($grande*$gr)+($docente*$do)+($saludable*$sa);
                    $cantidad=($pequeno+$grande+$docente+$saludable);
                    if($pequeno != 0){
                        $sql="UPDATE cartera_almuerzos SET pequeno = '$pequeno' WHERE id_user = '$id_usuario'";
                        $ejecutar=mysqli_query($conexion, $sql);
                    }
                    if($grande != 0){
                        $sql="UPDATE cartera_almuerzos SET grande = '$grande' WHERE id_user = '$id_usuario'";
                        $ejecutar=mysqli_query($conexion, $sql);
                    }
                    if($docente != 0){
                        $sql="UPDATE cartera_almuerzos SET docente = '$docente' WHERE id_user = '$id_usuario'";
                        $ejecutar=mysqli_query($conexion, $sql);
                    }
                    if($saludable != 0){
                        $sql="UPDATE cartera_almuerzos SET saludable = '$saludable' WHERE id_user = '$id_usuario'";
                        $ejecutar=mysqli_query($conexion, $sql);
                    }
                    if($pequeno == 0 && $grande == 0 && $docente == 0 && $saludable == 0){
                        return 3;
                    }
                        //$sql="UPDATE cartera_almuerzos SET pequeno = '$pequeno', grande = '$grande', docente = '$docente', saludable = '$saludable' WHERE id_user = '$id_usuario'";
                        //$ejecutar=mysqli_query($conexion, $sql);

                        $val_antiguo=self::valor_antiguo($id_usuario);

                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_factura','$id_admin','$id_usuario','2','5','$cantidad','$val_antiguo','$val_antiguo','$fecha','$hora')";
                        $ejecutar2=mysqli_query($conexion, $insert);
                        
                        
                        if($ejecutar){/* si lo ejecuto */
                            echo 1; /* exitoso */
                        }
                }
            }

        }

        public function crearfolio(){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT id_factura from movimientos_almuerzos group by id_factura desc";
            $result=mysqli_query($conexion,$sql);
            $id=mysqli_fetch_row($result)[0];
            if($id=="" or $id==null or $id==0){
              return 1;
            }else{
              return $id + 1;
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

        function abono(){
            require_once "conexion.php";
            $conexion=conexion();
            $valor=$_POST['form1']; $id=$_POST['form2'];
            date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');
            $id_factura=self::crearfolio();
            $id_usuario=self::id_usuario($id);
            
            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

            $sql="SELECT cartera FROM cartera_almuerzos where id_user = '$id_usuario'";
            $ejecutar=mysqli_query($conexion, $sql);
            $ver=mysqli_fetch_row($ejecutar);
            if(mysqli_num_rows($ejecutar)<=0){ /* no hay registro del usuario */
                echo 2; /* no existe el usuario en bd */
            }else{/* hay registros del usuario */
                if($valor<=0){/* estas tratando de ingresar un valor nulo */
                    echo 3;
                }else{/* valor valido */
                    if($valor>$ver[0]){
                        $nuevo_saldo = $ver[0] + $valor;
                        $sql="UPDATE cartera_almuerzos SET cartera = '$nuevo_saldo' WHERE id_user = '$id_usuario'";
                        $ejecutar=mysqli_query($conexion, $sql);

                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_factura','$id_admin','$id_usuario',4,5,0,'$ver[0]','$nuevo_saldo','$fecha','$hora')";
                        $ejecutar2=mysqli_query($conexion, $insert);
                        
                        if($ejecutar){/* si lo ejecuto */
                            echo 1; /* exitoso */
                        }
                }
            }

        }
    }

        function pagar_deudas(){
            require_once "conexion.php";
            $conexion=conexion();
            $id=$_POST['form1'];
            date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');
            $id_factura=self::crearfolio();
            $id_usuario=self::id_usuario($id);
            $valor_antiguo=self::valor_antiguo($id_usuario);
            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

                    $sql="UPDATE cartera_almuerzos SET pequeno = '0', grande = '0', docente = '0', saludable = '0' WHERE id_user = '$id_usuario'";
                    $ejecutar=mysqli_query($conexion, $sql);
                    if($ejecutar){
                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_factura','$id_admin','$id_usuario',5,5,0,'$valor_antiguo','$valor_antiguo','$fecha','$hora')";
                        $ejecutar2=mysqli_query($conexion, $insert);
                            if($ejecutar2){/* si lo ejecuto */
                                echo 1; /* exitoso */
                            }else{
                                echo 2;
                            }
                    }
                    

        }

        public function id_admin($id_admin){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT id_user from users_admins where user = '$id_admin'";
            $result=mysqli_query($conexion,$sql);
            $id=mysqli_fetch_row($result)[0];
              return $id;
              echo "id=>".$id;
          }
          public function valores_almuerzos($id){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT valor from categoria_movimientos_almuerzos where id_categoria = '$id'";
            $result=mysqli_query($conexion,$sql);
            $ver=mysqli_fetch_row($result);
              return $ver[0];
          }
          public function valor_antiguo($id){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT cartera from cartera_almuerzos where id_user = '$id'";
            $result=mysqli_query($conexion,$sql);
            $ver=mysqli_fetch_row($result);
              return $ver[0];
          }
          public function almuerzo_previo($pos,$id){
            require_once "conexion.php";
            $conexion=conexion();
            $sql="SELECT * from cartera_almuerzos where id_user = '$id'";
            $result=mysqli_query($conexion,$sql);
            $ver=mysqli_fetch_row($result);
              return $ver[$pos];
          }

    }

?>