<?php require_once "../home/navbar.php";
date_default_timezone_set('America/Bogota');
            $fecha= date('Y-m-d');

            require_once "../../model/conexion.php";
            $conexion=conexion();
            $sql="SELECT * FROM carga_almuerzos  JOIN users_admins ON carga_almuerzos.id_admin = users_admins.id_user where fecha = '$fecha' ORDER BY id_carga DESC LIMIT 1";
            $result=mysqli_query($conexion,$sql);
            $dat_alm=mysqli_fetch_row($result);

            $sql2="SELECT * FROM cartera_almuerzos JOIN usuarios ON usuarios.id_usuario = cartera_almuerzos.id_user  WHERE (pequeno > 0 OR grande >0 OR docente > 0 OR saludable >0)";
            $result2=mysqli_query($conexion,$sql2);

            $sql3="SELECT COUNT(pequeno) FROM cartera_almuerzos WHERE pequeno > 0";
            $result3=mysqli_query($conexion,$sql3); $conteo3=mysqli_fetch_row($result3);

            $sql4="SELECT COUNT(grande) FROM cartera_almuerzos WHERE grande > 0";
            $result4=mysqli_query($conexion,$sql4); $conteo4=mysqli_fetch_row($result4);

            $sql5="SELECT COUNT(docente) FROM cartera_almuerzos WHERE docente > 0";
            $result5=mysqli_query($conexion,$sql5); $conteo5=mysqli_fetch_row($result5);

            $sql6="SELECT COUNT(saludable) FROM cartera_almuerzos WHERE saludable > 0";
            $result6=mysqli_query($conexion,$sql6); $conteo6=mysqli_fetch_row($result6);

	if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
          require_once "../../model/conexion.php";
          $conexion=conexion();
    $sql="SELECT * FROM users_admins JOIN roles ON roles.id_rol = users_admins.estado  where users_admins.user = '$user'";
    $result=mysqli_query($conexion,$sql); $ver=mysqli_fetch_row($result);
            ?>  
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carga</title>
    <?php require_once "../../model/libraries/lib.php"; ?>
</head>

<body onload="solicitar_informacion()">
    <div class="container-x mx-5 mt-3 d-flex">
    <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
        <div class="contenedor1 text-white w-25">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Carga de almuerzos</h3>
                </div>
                <div class="col-sm-12">
                    <h6><?php echo $fecha; ?></h6>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="pequeno" class="col-sm-2 col-form-label">Pequeños</label>
                </div>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="pequeno">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="grande" class="col-sm-2 col-form-label">Grandes</label>
                </div>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="grande">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="docente" class="col-sm-2 col-form-label">Docentes</label>
                </div>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="docente">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-4">
                    <label for="saludable" class="col-sm-2 col-form-label">Saludables</label>
                </div>
                <div class="col-sm-8">
                    <input type="number" class="form-control" id="saludable">
                </div>
            </div>
            <div class="row form-group">
                <button onclick="actualizar()" class="btn btn-lg btn-block btn-warning">Cargar</button>
            </div>
        </div>
        <?php } ?>
        <div class="contenedor2 text-white ml-5 w-100 pl-5">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Almuerzos del dia</h3>
                </div>
            </div>
            <div class="row w-50">
                <div class="col-sm-12">
                    <table class="table table-sm table-bordered">
                        <tr class="text-center table-info">
                            <td>Tipo</td>
                            <td>Cantidad</td>
                        </tr>
                        <tr>
                            <td>Pequeños</td>
                            <td class="text-center"><?php echo $dat_alm[2]; ?></td>
                        </tr>
                        <tr>
                            <td>Grandes</td>
                            <td class="text-center"><?php echo $dat_alm[3]; ?></td>
                        </tr>
                        <tr>
                            <td>Docentes</td>
                            <td class="text-center"><?php echo $dat_alm[4]; ?></td>
                        </tr>
                        <tr>
                            <td>Saludables</td>
                            <td class="text-center"><?php echo $dat_alm[5]; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Actualización fecha y hora: <?php echo $dat_alm[6]."====>"; ?> <b><?php echo $dat_alm[7] ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="2">Usuario que actualizó: <b><?php echo $dat_alm[9]; ?></b></td>
                        </tr>
                        
                    </table>
                </div>
            </div>
            
            <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered table-sm text-white">
                        <tr class="text-center table-info text-dark">
                            <th>Nombres</th>
                            <th>Pequeños</th>
                            <th>Grandes</th>
                            <th>Docentes</th>
                            <th>Saludables</th>
                        </tr>
                        <tr class="text-danger text-center">
                            <th class="text-right text-white">CONTEO HOY</th>
                            <th class="table-success"><?php echo $conteo3[0]; ?></th>
                            <th class="table-success"><?php echo $conteo4[0]; ?></th>
                            <th class="table-success"><?php echo $conteo5[0]; ?></th>
                            <th class="table-success"><?php echo $conteo6[0]; ?></th>
                        </tr>
                        <?php while ($ver=mysqli_fetch_row($result2)): ?>
                        <tr>
                            <th><?php echo $ver[8]." ".$ver[9]; ?></th>
                            <th class="text-center"><?php echo $ver[2]; ?></th>
                            <th class="text-center"><?php echo $ver[3]; ?></th>
                            <th class="text-center"><?php echo $ver[4]; ?></th>
                            <th class="text-center"><?php echo $ver[5]; ?></th>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

</body>

</html>
<?php
} else {
 /*  echo "sin sesion"; */
	header("location:../login/index.html");
	}
?>

<style>
    body {
        /* background-image: radial-gradient(circle at 84.17% 84.17%, #aff5ff 0, #83daec 25%, #42b9d4 50%, #0099be 75%, #007fae 100%); */
        /* background-repeat: no-repeat;
        height: 100vh; */
        background-image: url(../media/recursos/fondo.png);
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
    }
</style>

<script>
        function actualizar(){
            cadena="form1=" + $('#pequeno').val()+
                   "&form2=" + $('#grande').val()+
                   "&form3=" + $('#docente').val()+
                   "&form4=" + $('#saludable').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/cargar_almuerzos.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Almuerzos cargados con exito!!");
                    solicitar_informacion();
                    setTimeout ("window.location.reload()", 2000);
                    return false;
                }else if(r==3){
                    alertify.error("No puedes recargar valores negativos o nulos");
                    return false;
                }else{
                    alertify.error("error del sistema");
                    return false;
                }
              }
            });
        }

        function solicitar_informacion(){
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/datos_carga_almuerzos.php",
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#pequeno').val(dato['0']);
                $('#grande').val(dato['1']);
                $('#docente').val(dato['2']);
                $('#saludable').val(dato['3']);
              }
            });
          }
</script>