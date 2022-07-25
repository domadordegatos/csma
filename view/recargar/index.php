<?php require_once "../home/navbar.php" ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once "../../model/libraries/lib.php"; ?>
    <title>RECARGA CSMA</title>
    <link rel="icon" type="image/png" href="../media/recursos/ico.png" />
</head>
<?php if($ver[5] == 2 || $ver[5] == 3){ ?>
<body>

    <div class="container my-4 d-flex justify-content-between">
        <div class="contenedor1 row w-50">
            <div class="row">
                <h1 class="text-white col-sm-9">
                    <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
                    Usuario a recargar</h1>
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
            <div class="row mt-2">
                <div class="col-sm-12">
                    <h1 class="text-white">Saldar Deudas</h1>
                    <div class="row">
                        <div class="col-sm-12 text-white">
                            <label for="">Mucho cuidado con esta opcion, solo hay que dar click para dejar en 0 el saldo del usuario que vaya a realizar el pago que este en mora</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-warning" onclick="pagar_deudas()">$$$ PAGAR DEUDAS</button>
                        </div>                        
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="contenedor2 row w-50 align-items-right pl-5">
            <div class="row">
                <div class="col-sm-12">
                    <h1 class="text-white">
                        <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
                        Recargar Dinero</h1>
                    <div class="row">
                        <div class="col-sm-7">
                            <input class="form-control" placeholder="3500.." type="text" id="valor_recarga">
                        </div>
                        <div class="col-sm-5">
                            <button class="btn btn-info" type="button" onclick="recargar()">$$$ RECARGAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1 class="text-white">
                        <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
                        Retirar Dinero</h1>
                    <div class="row">
                        <div class="col-sm-7">
                            <input class="form-control" placeholder="3500.." type="text" id="valor_retiro">
                        </div>
                        <div class="col-sm-5">
                            <button class="btn btn-danger"  onclick="retirar()">$$$ RETIRAR</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-sm-12">
                    <h1 class="text-white">
                        <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
                        Abonar Dinero</h1>
                    <div class="row">
                        <div class="col-sm-7">
                            <input class="form-control" placeholder="3500.." type="text" id="valor_abono">
                        </div>
                        <div class="col-sm-5">
                            <button class="btn btn-info"  onclick="abonar()">$$$ ABONAR</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php } ?>
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
        if($('#codigo').val()=="CFFL53WJN"){
            document.getElementById("codigo").value = "1122334455";
            }
      cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
                $("#valor_recarga").focus();
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

          function recargar(){
            cadena="form1=" + $('#valor_recarga').val()+
                   "&form2=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/recargar.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Recarga de BIT-MATHEWS exitosa!!");
                    $('input[id="valor_recarga"]').val('');
                    solicitar_informacion();
                    setTimeout ("window.location.reload()", 2000);
                    return false;
                }else if(r==3){
                    alertify.error("No puedes recargar valores negativos o nulos");
                    return false;
                }else if(r==2){
                    alertify.error("No hay registros, verifica que hayas escaneado un codigo");
                    return false;
                }else if(r==4){
                    alertify.error("No puedes recargar, este usuario debe dinero");
                    return false;
                }
              }
            });
          }

          function retirar(){
            cadena="form1=" + $('#valor_retiro').val()+
                   "&form2=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/retiro.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Retiro de BIT-MATHEWS exitosa!!");
                    solicitar_informacion();
                    $('input[id="valor_retiro"]').val('');
                    setTimeout ("window.location.reload()", 2000);
                    return false;
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

          function abonar(){
            cadena="form1=" + $('#valor_abono').val()+
                   "&form2=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/abono.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("abono de BIT-MATHEWS exitosa!!");
                    solicitar_informacion();
                    $('input[id="valor_abono"]').val('');
                    setTimeout ("window.location.reload()", 2000);
                    return false;
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

          function solicitar_informacion(){
            cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_last_name').val(dato['1']);
                $('#u_grade').val(dato['2']);
                $('#u_id').val(dato['3']);
                $('#u_coin').val(dato['4']);
                $('#u_id_bd').val(dato['6']);
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

          function pagar_deudas(){
            cadena="form1=" + $('#u_id').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/pagar_deudas.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Operacion exitosa!! estas al dia :D");
                    solicitar_informacion();
                    setTimeout ("Location.reload();", 2000);
                    return false;
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