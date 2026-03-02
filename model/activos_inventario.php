<?php
    class activos_inventario{

        function tabla_activos_csma(){
            unset($_SESSION['tabla_activos_csma']);
            require_once "conexion.php";
            $conexion=conexion();
            $sql1="SELECT activos.id_activo, tipo_activo.descripcion , marcas.descripcion , activos.codigo_csma, activos.codigo_equipo, uso_zona.descripcion, estados_insumos.descripcion, activos.caracteristicas, activos.detalles,
            activos.fecha FROM activos
            JOIN tipo_activo ON tipo_activo.id_tipo_activo = activos.id_tipo
            JOIN marcas ON marcas.id_marcas = activos.id_marca
            JOIN uso_zona ON uso_zona.id_zona = activos.id_zona
            JOIN estados_insumos ON estados_insumos.id_estado = activos.id_estado order by activos.id_activo desc";
                $result=mysqli_query($conexion,$sql1);
                if(mysqli_num_rows($result)<=0){//no encontro registrros bd
                  echo 2;//no hay registros
                }else{
                             while ($ver1=mysqli_fetch_row($result)){
                                 $registros=$ver1[0]."||".
                                            $ver1[1]."||".
                                            $ver1[2]."||".
                                            $ver1[3]."||".
                                            $ver1[4]."||".
                                            $ver1[5]."||".
                                            $ver1[6]."||".
                                            $ver1[7]."||".
                                            $ver1[8]."||".
                                            $ver1[9]."||";
                                            $_SESSION['tabla_activos_csma'][]=$registros;
                                }
                                echo 1;
        }
        }
        }
 ?>