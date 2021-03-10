<?php

    class edicion_usuario{

        function ob_datos_usuario(){
            require_once "conexion.php";
            $conexion=conexion();
            $id=$_POST['form1'];

            $sql1="SELECT * FROM usuarios JOIN grados ON grados.id_grado = usuarios.grado where usuarios.id_tarjeta = '$id'";
                $result=mysqli_query($conexion,$sql1);
                $ver=mysqli_fetch_row($result);
                $datos=array( "0" => $ver[1],
                              "1" => $ver[2],
                              "2" => $ver[7],
                              "3" => $ver[4],
                              "4" => $ver[5],
                              "6" => $ver[0]
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
            $valor=$_POST['form1']; $id=$_POST['form2'];
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
                    if($ver[0]<0){/* debe dinero */
                        echo 4;
                    }else{/* no debe nada */
                        $nuevo_saldo = $ver[0] + $valor;
                        $sql="UPDATE usuarios SET saldo = '$nuevo_saldo' WHERE id_tarjeta = '$id'";
                        $ejecutar=mysqli_query($conexion, $sql);
                        
                        $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_usuario','14','$valor','$ver[0]','$nuevo_saldo','$fecha','$hora')";
                        $ejecutar2=mysqli_query($conexion, $insert);

                        if($ejecutar){/* si lo ejecuto */
                            echo 1; /* exitoso */
                        }
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

                        $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_usuario','15','$valor','$ver[0]','$nuevo_saldo','$fecha','$hora')";
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
            
            $user = $_SESSION['user'];
            $id_admin=self::id_admin($user);

            $sql="SELECT usuarios.saldo FROM usuarios where usuarios.id_tarjeta = '$id'";
            $ejecutar=mysqli_query($conexion, $sql);
            $ver=mysqli_fetch_row($ejecutar);
            if($ver[0]==0){
                echo 3;
            }else{
                if($ver[0]>0){
                    echo 4;
                }else{
                    $sql="UPDATE usuarios SET saldo = '0' WHERE id_tarjeta = '$id'";
                    $ejecutar=mysqli_query($conexion, $sql);

                    $insert="INSERT INTO movimientos_dinero VALUES ('','$id_factura','$id_admin','$id_usuario','16','0','$ver[0]','0','$fecha','$hora')";
                    $ejecutar2=mysqli_query($conexion, $insert);
                            if($ejecutar){/* si lo ejecuto */
                                echo 1; /* exitoso */
                            }else{
                                echo 2;
                            }
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


    }

?>