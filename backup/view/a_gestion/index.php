<?php require_once "../home/navbar.php" ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Almuerzos</title>
    <?php require_once "../../model/libraries/lib.php"; ?>
</head>
<body>
    <div class="container d-flex w-100 justify-content-between">
        <div class="contenedor1 row w-50">
            <div class="row mt-2">
                <h1 class="text-white col-sm-9">
                    <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
                    Gestion Almuerzos</h1>
                <input class="form-control col-sm-3 mt-2" autofocus type="text" id="codigo" name="codigo" placeholder="123..">
            </div>
            <div class="row my-3">
                <div class="col-sm-6 border border-white d-flex justify-content-center align-items-center" style="border-top-left-radius: 20px; border-bottom-left-radius: 20px;">
                    <img src="../media/recursos/profile.png" id="u_img" width="95%" alt="">
                </div>
                <div class="col-sm-6">
                    <div class="row border border-white p-1" style="border-top-right-radius: 20px;">
                        <div class="col-sm-12">
                            <label class="text-white">Nombre: <input type="text" disabled id="u_name" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white" id="prueba">Apellido: <input type="text" disabled id="u_last_name" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white">Grado: <input type="text" disabled id="u_grade" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1">
                        <div class="col-sm-12">
                            <label class="text-white">Id: <input type="text" disabled id="u_id" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                    <div class="row border border-white p-1" style="border-bottom-right-radius: 20px;">
                        <div class="col-sm-12">
                            <label class="text-white">Saldo: $<input type="text" disabled id="u_coin" class="form-control form-control-sm"></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row w-100">
                <div class="col-sm-12 d-flex justify-content-center pt-0 mt-0">
                    <button class="btn btn-success" onclick="location.reload()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                            <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
                          </svg>  SCAN
                    </button>
                </div>
            </div>
        </div>
        <div class="pl-5 contenedor2 mt-2 text-white w-50">
            <h1>Almuerzos del usuario</h1>
            <div class="row mb-2 text-center">
                <div class="col-sm-3"><b>Item</b></div>
                <div class="col-sm-3"><b>Actuales</b></div>
                <div class="col-sm-6"><b>Cantidad a Agregar</b></div>
            </div>
            <div class="mb-3 row">
                <label for="pequeno" class="col-sm-3 col-form-label"><b>Pequeño</b> </label>
                <div class="col-sm-3">
                    <input type="number" disabled class="form-control" id="pequeno_2">
                </div>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="pequeno" value="0" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="grande" class="col-sm-3 col-form-label"><b>Grande</b> </label>
                <div class="col-sm-3">
                    <input type="number" disabled class="form-control" id="grande_2">
                </div>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="grande" value="0" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="Docentes" class="col-sm-3 col-form-label"><b>Docente</b> </label>
                <div class="col-sm-3">
                    <input type="number" disabled class="form-control" id="docente_2">
                </div>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="docente" value="0" required>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="saludable" class="col-sm-3 col-form-label"><b>Saludable</b> </label>
                <div class="col-sm-3">
                    <input type="number" disabled class="form-control" id="saludable_2">
                </div>
                <div class="col-sm-6">
                    <input type="number" class="form-control" id="saludable" value="0" required>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-lg btn-block btn-warning text-white" onclick="actualizar()">Actualizar</button>
                </div>
            </div>
            <div class="row pt-1">
                <div class="col-sm-6 d-flex align-items-center">
                    <input type="text" id="valor_abono" class="form-control" placeholder="$...">
                </div>
                <div class="col-sm-6">
                    <button class="btn btn-block btn-lg btn-info" onclick="abonar()">Abonar</button>
                </div>
            </div>
            <div class="row pt-1">
                <div class="col-sm-12">
                    <button class="btn btn-block btn-lg btn-danger" onclick="vaciar()">Vaciar almuerzos a cero</button>
                </div>
            </div>
        </div>
        
    </div>
</body>
</html>

<style>
    body{
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
    $(document).ready(function(){
    $("input[name=codigo]").change(function(){
      cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
                $('#pequeno_2').val(dato['7']);
                $('#grande_2').val(dato['8']);
                $('#docente_2').val(dato['9']);
                $('#saludable_2').val(dato['10']);

                var id = (dato['6']);
                var dinero = (dato['4']); 
                if(dinero<0){
                    document.getElementById("u_coin").style.backgroundColor = "#FF0000";
                    document.getElementById("u_coin").style.color = "white";
                }else{
                    document.getElementById("u_coin").style.backgroundColor = "";
                    document.getElementById("u_coin").style.color = "";
                }
                
                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img").attr("src","../media/fotos_usuarios/"+id+".jpg");
                    }
                }
                )
                .catch(function(err) {
                console.log('Fetch Error :-S', err);
                });
              }
            });
            });
          });

          function actualizar(){
            if($('#pequeno').val() == '' || $('#grande').val() == '' || $('#docente').val() == '' || $('#saludable').val() == ''){
                alertify.error("debes llenar todos los campos");
            }else{
            cadena="form1=" + $('#pequeno').val()+
                   "&form2=" + $('#grande').val()+
                   "&form3=" + $('#docente').val()+
                   "&form4=" + $('#saludable').val()+
                   "&form5=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/recargar.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Actualizacion de almuerzos exitosa!!");
                    solicitar_informacion();
                    return false;
                }else if(r==3){
                    alertify.error("No puedes recargar valores negativos o nulos");
                    return false;
                }else if(r==2){
                    alertify.error("No hay registros, verifica que hayas escaneado un codigo");
                    return false;
                }
              }
            });
          }
        }

        function solicitar_informacion(){
            cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
                $('#pequeno_2').val(dato['7']);
                $('#grande_2').val(dato['8']);
                $('#docente_2').val(dato['9']);
                $('#saludable_2').val(dato['10']);
                $('#pequeno').val("0");$('#grande').val("0");$('#docente').val("0");$('#saludable').val("0");
                var id = (dato['6']);
                var dinero = (dato['4']); 
                if(dinero<0){
                    document.getElementById("u_coin").style.backgroundColor = "#FF0000";
                    document.getElementById("u_coin").style.color = "white";
                }else{
                    document.getElementById("u_coin").style.backgroundColor = "";
                    document.getElementById("u_coin").style.color = "";
                }
                
                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img").attr("src","../media/fotos_usuarios/"+id+".jpg");
                    }
                }
                )
                .catch(function(err) {
                console.log('Fetch Error :-S', err);
                });
              }
            });
          }

          function abonar(){
            cadena="form1=" + $('#valor_abono').val()+
                   "&form2=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/abono.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("abono exitoso!!");
                    solicitar_informacion();
                    $('input[id="valor_abono"]').val('');
                }else if(r==3){
                    alertify.error("No puedes ingresar valores negativos o nulos");
                    return false;
                }else if(r==2){
                    alertify.error("No hay registros, verifica que hayas escaneado un codigo");
                    return false;
                }else if(r==4){
                    alertify.error("No tienes este monto en tu cuenta, intenta con un valor menor");
                    return false;
                }
              }
            });
          }
          function vaciar(){
            cadena="form1=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/almuerzos/pagar_deudas.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Operacion exitosa!! sin almuerzos");
                    solicitar_informacion();
                }else if(r==2){
                    alertify.error("Error en el proceso, debe ser que no escaneaste nada");
                    return false;
                }else if(r==3){
                    alertify.error("El usuario ya se encuentra con el saldo en $0");
                    return false;
                }else if(r==4){
                    alertify.error("Este usuario no tiene deudas");
                    return false;
                }
              }
            });
          }
</script>