<?php

    class edicion_usuario{

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

                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_admin','$id_usuario','2','5','$cantidad','$val_antiguo','$val_antiguo','$fecha','$hora')";
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
            $sql="SELECT id_factura from movimientos_dinero group by id_factura desc";
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


        function retiro(){
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

            $sql="SELECT usuarios.saldo FROM usuarios where usuarios.id_tarjeta = '$id'";
            $ejecutar=mysqli_query($conexion, $sql);
            $ver=mysqli_fetch_row($ejecutar);
            if(mysqli_num_rows($ejecutar)<=0){ /* no hay registro del usuario */
                echo 2; /* no existe el usuario en bd */
            }else{/* hay registros del usuario */
                if($valor<=0){/* estas tratando de ingresar un valor nulo */
                    echo 3;
                }else{/* valor valido */
                    if($valor>$ver[0]){
                        echo 4;
                    }else{
                        $nuevo_saldo = $ver[0] - $valor;
                        $sql="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id'";
                        $ejecutar=mysqli_query($conexion, $sql);

                        //$insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_usuario','15','$valor','$ver[0]','$nuevo_saldo','$fecha','$hora')";
                        //$ejecutar2=mysqli_query($conexion, $insert);
                        if($ejecutar){/* si lo ejecuto */
                            echo 1; /* exitoso */
                        }
                    }
                }
            }

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

                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_admin','$id_usuario',4,5,0,'$ver[0]','$nuevo_saldo','$fecha','$hora')";
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
                        $insert="INSERT INTO movimientos_almuerzos VALUES ('','$id_admin','$id_usuario',5,5,0,'$valor_antiguo','$valor_antiguo','$fecha','$hora')";
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