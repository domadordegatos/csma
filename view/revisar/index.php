<?php 
    require_once "../home/navbar.php";
    require_once "../../model/conexion.php";
    require_once "../../model/libraries/lib.php";
    $conexion=conexion();
    
    
    
?>


<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta Bit-Mathews</title>
</head>
<?php if($ver[5] == 2 || $ver[5] == 3){ ?>
<body onload="r_codigo();">

    <div class="container-x d-flex mt-3">
        <div class="contenedor1 w-50 ml-3">
        
            <h1 class="display-4 text-white">Cartera de Bit-Mathews
                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                    <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                    <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                  </svg>
            </h1>

            <div class="row mb-2">
                <div class="col-sm-2 d-flex justify-content-center pt-0 mt-0">
                    <button class="btn btn-success" onclick="location.reload()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-upc-scan" viewBox="0 0 16 16">
                            <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1h-3zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5zM.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5zM3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z"/>
                          </svg>  SCAN
                    </button>
                </div>
                <div class="col-sm-6 d-flex align-items-center">
                    <input class="form-control col-sm-12 mt-2" autofocus type="text" id="codigo" name="codigo" placeholder="123..">
                </div>
                <!-- <div class="col-sm-4 d-flex align-items-center">
                    <input class="form-control col-sm-12 mt-2" type="date" id="date" name="date">
                </div> -->
            </div>

            <div id="tabla_consulta" style="height: 520px; overflow: scroll; overflow-x: hidden;"></div>
            
        </div>
        <div class="contenedor2 w-50 mx-4">
            <div id="tabla_consulta_detallada" style="height: 300px; overflow: scroll; overflow-x: hidden;"></div>
            <div id="tabla_consulta_factura" style="height: 300px; overflow: scroll; overflow-x: hidden;"></div>
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

            function consulta_factura(id){
            cadena="form1=" + id;
                    $.ajax({
                    type:"POST",
                    url:"../../controller/consulta_detallada_factura.php", //validacion de datos de registro
                    data:cadena,
                    success:function(r){
                        if(r==1){
                            alertify.success("Registros encontrados");
                            $('#tabla_consulta_factura').load("temp_factura.php");
                        }else if(r==2){
                            alertify.error("No existen registros");
                            $('#tabla_consulta_factura').load("temp_factura.php");
                            return false;
                        }else{
                            alertify.error("Error en el proceso");
                        }
                    }
                });
            }

        function consulta(id){

            var sin_fecha="A";
            cadena="form1=" + id +
                   "&form2=" + sin_fecha;
                    $.ajax({
                    type:"POST",
                    url:"../../controller/consulta_detallada.php", //validacion de datos de registro
                    data:cadena,
                    success:function(r){
                        if(r==1){
                            alertify.success("Registros encontrados");
                            $('#tabla_consulta_detallada').load("consulta_detallada.php");
                        }else if(r==2){
                            alertify.error("No existen registros");
                            $('#tabla_consulta_detallada').load("consulta_detallada.php");
                            return false;
                        }else{
                            alertify.error("Error en el proceso");
                        }
                    }
                });
        }

        $(document).ready(function(){
            $("input[name=codigo]").change(function(){
                if($('#codigo').val()=="CFFL53WJN"){
            document.getElementById("codigo").value = "1122334455";
            }
            cadena="form1=" + $('#codigo').val();
                    $.ajax({
                    type:"POST",
                    url:"../../controller/consulta_movimientos.php", //validacion de datos de registro
                    data:cadena,
                    success:function(r){
                        if(r==1){
                            alertify.success("Registros encontrados");
                            $('#tabla_consulta').load("temp_consulta.php");
                        }else if(r==2){
                            alertify.error("No existen registros");
                            $('#tabla_consulta').load("temp_consulta.php");
                            return false;
                        }else{
                            alertify.error("Error en el proceso");
                        }
                    }
                });
            });
          });

          $(document).ready(function(){
            $("input[name=date]").change(function(){
            cadena="form1=" + $('#date').val()+
                   "&form2=" + $('#codigo').val();
                    $.ajax({
                    type:"POST",
                    url:"../../controller/consulta_movimientos_fecha.php", //validacion de datos de registro
                    data:cadena,
                    success:function(r){
                        if(r==1){
                            alertify.success("Registros encontrados");
                            $('#tabla_consulta_detallada').load("consulta_detallada.php");
                        }else if(r==2){
                            alertify.error("No existen registros");
                            $('#tabla_consulta_detallada').load("consulta_detallada.php");
                            return false;
                        }else{
                            alertify.error("Error en el proceso");
                        }
                    }
                });
            });
          });

    function r_codigo(){
    var movimiento="A"
    cadena="form1=" + movimiento;
    $.ajax({
        type:"POST",
        url:"../../controller/consulta_movimientos.php", //validacion de datos de registro
        data:cadena,
        success:function(r){
        if(r==1){
            alertify.success("Registros encontrados");
            $('#tabla_consulta').load("temp_consulta.php");
        }else if(r==2){
            alertify.error("No existen registros");
            $('#tabla_consulta').load("temp_consulta.php");
            return false;
        }else{
            alertify.error("Error en el proceso");
        }
        }
    });
}
</script>