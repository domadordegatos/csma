<?php  
    class login{

        function iniciar(){
            $user=$_POST['form1'];$password=$_POST['form2'];
            require_once "conexion.php";
            $conexion=conexion();

            $sql="SELECT * FROM users_admins WHERE user = '$user' and password = '$password'";
            $result=mysqli_query($conexion,$sql);
            $ver=mysqli_fetch_row($result);
                if(mysqli_num_rows($result)<=0){//no encontro registrros bd
                  echo 2;//no hay registros
                }else if($ver[4] == 0){ //usuario desactivado
                    echo 3;
                }else if($ver[4] == 1){//usuario activo
                    $_SESSION['user']=$user;
                    echo 1;
                }else{ //error
                    echo 0;
                }
        }

    }

?>