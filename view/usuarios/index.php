<?php 
    require_once "../home/navbar.php";
    require_once "../../model/conexion.php";
    require_once "../../model/libraries/lib.php";
    $conexion=conexion();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Csma</title>
</head>

<body onload="r_codigo();">
    <div class="contenedor mx-5">
        <h2 class="mt-4 text-white">Agregado de Usuarios</h2>
        <div class="row">
            <div class="col-xl-6">
                <div class="form-group row">
                    <label for="nombres" class="col-sm-2 col-form-label">Nombres</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nombres">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="apellidos" class="col-sm-2 col-form-label">Apellidos</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="apellidos">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="grado" class="col-sm-2 col-form-label">Grado</label>
                    <div class="col-sm-10">
                        <select class="form-control col-sm-12" name="" id="grado">
                            <option value="A">Opciones...</option>
                            <?php $sql="SELECT * FROM grados order by id_grado asc";
                                                  $result=mysqli_query($conexion,$sql);
                                                  while ($ver=mysqli_fetch_row($result)):?>
                                        <option value=<?php echo $ver[0]; ?>><?php echo $ver[1]; ?></option>
                            <?php endwhile; ?>
                          </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="tarjeta" class="col-sm-2 col-form-label">Tarjeta</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tarjeta">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="saldo" class="col-sm-3 col-form-label">Saldo Inicial</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="saldo" value="0">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12">
                        <button class="btn btn-success btn-lg btn-block" onclick="agregar()">Agregar</button>
                    </div>
                </div>
            </div>
            <div class="col-xl-6">
                <div id="tabla_consulta" style="height: 520px; overflow: scroll; overflow-x: hidden;"></div>
            </div>
        </div>
    </div>

</body>

</html>
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
    function agregar(){
            cadena="form1=" + $('#nombres').val()+
                  "&form2=" + $('#apellidos').val()+
                  "&form3=" + $('#grado').val()+
                  "&form4=" + $('#tarjeta').val()+
                  "&form5=" + $('#saldo').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/agregar_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Usuario agregado exitosamente!!");
                    $('input[id="valor_recarga"]').val('');
                    setTimeout ("window.location.reload()", 2000);
                    return false;
                }if(r==3){
                    $('#tabla_consulta').load("temp_USERS.php");
                    alertify.error("no se agrego a la cartera");
                    return false;
                }if(r==0){
                    $('#tabla_consulta').load("temp_USERS.php");
                    alertify.error("ya existe un usuario con este codigo");
                    return false;
                }if(r==2){
                    $('#tabla_consulta').load("temp_USERS.php");
                    alertify.error("error agregando al listado principal");
                    return false;
                }else{
                    $('#tabla_consulta').load("temp_USERS.php");
                    alertify.error("Error no se pudo agregar");
                    return false;
                }
              }
            });
          }



          function r_codigo(){
            var movimiento="A"
            cadena="form1=" + movimiento;
            $.ajax({
                type:"POST",
                url:"../../controller/consulta_usuarios.php", //validacion de datos de registro
                data:cadena,
                success:function(r){
                if(r==1){
                    alertify.success("Registros encontrados");
                    $('#tabla_consulta').load("temp_USERS.php");
                }else if(r==2){
                    alertify.error("No existen registros");
                    $('#tabla_consulta').load("temp_USERS.php");
                    return false;
                }else{
                    alertify.error("Error en el proceso");
                }
                }
            });
        }
</script>