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
    <title>Registro</title>
</head>
<body>
    <div class="contendor w-100 d-flex h-100">
        <div class="separador1 mt-5 w-50 text-white">
            <div class="row justify-content-center">
                <div class="col-sm-6 ">
                    <h3>Codigo de Barras</h3>
                    <input type="text" class="form-control" name="codigo" id="codigo" autofocus>
                </div>
            </div>
        </div>

        <div class="separador2 mt-5 w-50 row justify-content-center ">
            <div class="sub-separador text-white p-2">

                <div class="row">
                    <div  style="border-top-left-radius: 15px; border-top-right-radius: 15px;"  class="col-sm-12 border d-flex justify-content-center align-items-center">
                        <img id="u_img" class="my-2" width="40%" src="../media/recursos/profile.png">
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 border text-center bg-success">Nombre</div>
                    <div class="col-sm-6 border ">
                        <input type="text" id="u_name" class="my-1 form-control form-control-sm" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 border text-center bg-success">Codigo</div>
                    <div class="col-sm-6 border ">
                        <input type="text" id="u_id" class="my-1 form-control form-control-sm" disabled>
                    </div>
                </div>
                <div class="sub" id="tabla_registros">

                </div>
            </div>


        </div>
    </div>
</body>
</html>

<script>
    $(document).ready(function(){
    $("input[name=codigo]").change(function(){
      cadena="form1=" + $('#codigo').val();
            $.ajax({
              type:"POST",
              url:"../../controller/datos_usuario.php",
              data:cadena,
              success:function(r){
                dato=jQuery.parseJSON(r);
                $('#u_name').val(dato['0']);
                $('#u_id').val(dato['3']);
                $('#u_id_bd').val(dato['6']);//id en base de datos
                var id = (dato['6']);
                if(id != null){
                    agregar_registro(id);
                    /* registros_previos(id); */
                }
                console.log("data",id);
                
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

          function agregar_registro(id){
            $.ajax({
                    type:"POST",
                    data:"id=" +id,
                    url:"../../controller/asistencia/agregar_registro.php",
                    success:function(r){
                        if(r==1){
                            $('#tabla_registros').load("registros_usuario.php");
                            alertify.success("Llegada registrada");
                            document.getElementById("codigo").value = "";
                            $("#codigo").focus();
                        }else if(r==2){
                            alertify.error("Error registrando");
                            document.getElementById("codigo").value = "";
                            $("#codigo").focus();
                        }
                    }
                });
          }
</script>