<?php require_once "../home/navbar.php" ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php require_once "../../model/libraries/lib.php"; ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transferencias</title>
</head>
<?php if($ver[5] == 2 || $ver[5] == 3){ ?>

<body>
    <div class="container">
        <div class="cotenedor1 mt-3">
            <h3 class="text-white text-center">Transferencias</h3>
            <div class="contendor-usuarios d-flex w-100">
                <div class="user1 w-50" >
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-white text-center">Origen</h4>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            <div
                                class="col-sm-6 border rounded p-3 border-white d-flex justify-content-center align-items-center">
                                <img src="../media/recursos/profile.png" id="u_img1" width="50%" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-6">
                            <input type="number" placeholder="123...." class="form-control mt-1" id="codigo1" name="codigo1" autofocus>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-4">
                            <input type="number" disabled class="form-control mt-1" id="valor1" name="valor1" autofocus>
                        </div>
                    </div>
                </div>
                <div class="user2 w-50" >
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-white text-center">Destino</h4>
                        </div>
                        <div class="col-sm-12 d-flex justify-content-center">
                            <div
                                class="col-sm-6 border rounded p-3 border-white d-flex justify-content-center align-items-center">
                                <img src="../media/recursos/profile.png" id="u_img2" width="50%" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-6 d-flex justify-content-center">
                            <input type="number" placeholder="123...." class="form-control mt-1" id="codigo2" name="codigo2">
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-4">
                            <input type="number" disabled class="form-control mt-1" id="valor2" name="valor2" autofocus>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center mt-5">
                <div class="col-sm-6">
                    <h4 class="text-center text-white">Valor de transferencía</h4>
                    <input type="numer" class="form-control" placeholder="$..." id="valor_transferencia" name="valor_transferencia">
                    <button class="btn btn-info btn-lg btn-block mt-2" onclick="transferir()">Transferir</button>
                </div>
            </div>
        </div>
    </div>

</body>
<?php } ?>

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
    $(document).ready(function(){
    $("input[name=codigo1]").change(function(){
        document.getElementById("codigo2").focus();
        cadena="form1=" + $('#codigo1').val();
        $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#valor1').val(dato['4']);
                var id = (dato['6']);
                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img1").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img1").attr("src","../media/fotos_usuarios/"+id+".jpg");
                    }
                }
                )
                .catch(function(err) {
                console.log('Fetch Error :-S', err);
                });
              }
            });
      });
      $("input[name=codigo2]").change(function(){
        document.getElementById("valor_transferencia").focus();
        cadena="form1=" + $('#codigo2').val();
        $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#valor2').val(dato['4']);
                var id = (dato['6']);
                fetch("../media/fotos_usuarios/"+id+".jpg")
                .then(
                function(response) {
                    if (response.status !== 200) {/* problemas para encontrar la imagen */
                    console.log('problemas encontrando la imagen, error: ' +
                    response.status);
                    $("#u_img2").attr("src","../media/recursos/profile.png");
                    return;
                    }else{
                        console.log("encontramos la imagen");
                        $("#u_img2").attr("src","../media/fotos_usuarios/"+id+".jpg");
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

    function transferir(){
        if($('#codigo1').val() == '' || $('#codigo2').val() == '' || $('#valor_transferencia').val() == '' ){
                alertify.error("debes llenar todos los campos");
                return false;
            }if($('#codigo1').val() == $('#codigo2').val()){
                alertify.error("los codigos son iguales");
                return false;
            }if($('#valor_transferencia').val() <= 0){
                alertify.error("El valor a transferir es incorrecto");
                return false;
            }
            cadena="form1=" + $('#codigo1').val()+
                  "&form2=" + $('#codigo2').val()+
                  "&form3=" + $('#valor_transferencia').val();
                   
            $.ajax({
              type:"POST",
              url:"../../controller/funcionalidades/transferencias.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                if(r==1){
                    alertify.success("Transferencia de BIT-MATHEWS exitosa!!");
                    actualizar_info();
                    setTimeout ("window.location.reload()", 2000);
                    return false;
                }else if(r==2){
                    alertify.error("No tienes este monto en tu cuenta, intenta con un valor menor");
                    return false;
                }else if(r==3){
                    alertify.error("No tienes este monto en tu cuenta");
                    return false;
                }
              }
            });
          }

         function actualizar_info(){
            cadena="form1=" + $('#codigo1').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#valor1').val(dato['4']);
                var id = (dato['6']);
              }
            });
            cadena="form1=" + $('#codigo2').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php", //validacion de datos de registro
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#valor2').val(dato['4']);
                var id = (dato['6']);
              }
            });
          }
    
</script>