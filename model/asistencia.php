<?php 
    class asistencia{

        function agregar_registro($id){
            require_once "conexion.php";
            $conexion=conexion();
            date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');

            $insert="INSERT INTO asistencia VALUES ('','$id','$fecha','$hora')";
                    $ejecutar=mysqli_query($conexion, $insert);
                            if($ejecutar){
                                self::registros_por_usuario($id);
                            }else{
                                echo 2;
                            }
        }

        function registros_por_usuario($id){
            session_start();
            unset($_SESSION['registros_user']);
            require_once "conexion.php";
            $conexion=conexion();
            date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');

            $sql1="SELECT * FROM asistencia WHERE id_usuario = '$id' and fecha = '$fecha'";
                $result=mysqli_query($conexion,$sql1);
                if(mysqli_num_rows($result)<=0){//no encontro registrros bd
                  echo 2;//no hay registros
                }else{
                             while ($ver1=mysqli_fetch_row($result)){
                                 $registros=$ver1[0]."||".
                                            $ver1[1]."||".
                                            $ver1[2]."||".
                                            $ver1[3]."||";
                                            $_SESSION['registros_user'][]=$registros;
                                }
                                echo 1;
        }
    }

    function buscador_datos(){
        unset($_SESSION['buscador']);
        $name = $_POST['form1'];
        require_once "conexion.php";
        $conexion = conexion();
        $sql = "SELECT id_usuario, apellido,  nombre FROM usuarios WHERE apellido LIKE '%$name%' OR nombre LIKE '%$name%'";
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

    function registros_uno_varios(){
        $id = $_POST['form1']; $fecha = $_POST['form2'];
        unset($_SESSION['datos_uno_varios']);
        require_once "conexion.php";
        $conexion=conexion();

        if($id != 'A'){
            $sql1="SELECT asistencia.id_asistencia, usuarios.apellido, usuarios.nombre, grados.descripcion, asistencia.fecha, asistencia.hora FROM asistencia
            JOIN usuarios ON usuarios.id_usuario = asistencia.id_usuario
            JOIN grados ON grados.id_grado = usuarios.grado
            WHERE asistencia.id_usuario = '$id' AND asistencia.fecha = '$fecha'";
            $result=mysqli_query($conexion,$sql1);
        }else{
            $sql1="SELECT asistencia.id_asistencia, usuarios.apellido, usuarios.nombre, grados.descripcion, asistencia.fecha, asistencia.hora FROM asistencia
            JOIN usuarios ON usuarios.id_usuario = asistencia.id_usuario
            JOIN grados ON grados.id_grado = usuarios.grado
            AND asistencia.fecha = '$fecha' ORDER BY nombre, hora asc";
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
                                              $ver1[5]."||";
                                              $_SESSION['datos_uno_varios'][]=$tabla_registros;
                            }
                            echo 1;
    }
}
}
?>