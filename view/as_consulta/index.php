<?php 
    require_once "../home/navbar.php";
    require_once "../../model/conexion.php";
    require_once "../../model/libraries/lib.php";
    $conexion=conexion();

    date_default_timezone_set('America/Bogota');
            $time = time();
            $hora = date("H:i:s",$time);
            $fecha= date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta</title>
</head>
<body style="overflow-x: hidden;">
    <div class="contendor w-100 d-flex">

        <div class="separador1 w-25 d-flex justify-content-center">
            <div class="subcontenedor mt-5 text-white">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Nombre Usuario</h4>
                        <input type="text" class="form-control" id="buscador_nombre" name="buscador_nombre" placeholder="Arturo...">
                    </div>
                </div>

                <div class="row text-white mt-3">
                    <div class="col-sm-12">
                        <select name="resultado_nombre" id="resultado_nombre" class="form-control">
                            <option value="A" selected>Resultados...</option>
    
                        </select>
                    </div>
                </div>

                
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <button class="btn btn-sm btn-success btn-lg btn-block" onclick="uno()">Consultar Uno</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <button class="btn btn-sm btn-success btn-lg btn-block" onclick="todos()">Consultar Todos</button>
                    </div>
                </div>

            </div>
        </div>


        <div class="separador2 w-75">

            <div class="row mt-4">
                <div class="col-sm-12">
                    <div id="tabla_consulta" class="scrroll px-4" style="overflow-x: hidden;"></div>
                </div>
            </div>

        </div>
    </div>

    
</body>
</html>

<style>
    .scrroll::-webkit-scrollbar {
  width: 16px;
}

.scrroll::-webkit-scrollbar-track {
  background-color: #e4e4e4;
  border-radius: 100px;
}

.scrroll::-webkit-scrollbar-thumb {
  background-color: #64AEF4;
  border-radius: 100px;
}
</style>
<script>
    function uno(){
        if ($('#resultado_nombre').val() == 'A' ) {
            alertify.message("debes seleccionar un usuario");
        } else {
        cadena = "form1=" + $('#resultado_nombre').val() +
                "&form2=" + $('#fecha').val();
        $.ajax({
            type: "POST",
            url: "../../controller/asistencia/buscar_uno.php", //validacion de datos de registro
            data: cadena,
            success: function(r) {
                if (r == 1) {
                    $('#tabla_consulta').load("temp_uno.php");
                    return false;
                }else if(r==2){
                    alertify.error("No hay registros");
                }
            }
        });
        }
    }

    function todos(){
        cadena = "form1=" + 'A' +
                "&form2=" + $('#fecha').val();
        $.ajax({
            type: "POST",
            url: "../../controller/asistencia/buscar_uno.php", //validacion de datos de registro
            data: cadena,
            success: function(r) {
                if (r == 1) {
                    $('#tabla_consulta').load("temp_uno.php");
                    return false;
                }else if(r==2){
                    alertify.error("No hay registros");
                }
            }
        });
    }

$(document).ready(function() {
        $("input[name=buscador_nombre]").change(function() {
            cadena = "form1=" + $('#buscador_nombre').val();
            $.ajax({
                type: "POST",
                url: "../../controller/asistencia/buscador_datos.php", //validacion de datos de registro
                data: cadena,
                success: function(r) {
                    if (r == 1) {
                        $('#resultado_nombre').load("temp_buscador.php");
                        return false;
                    }
                }
            });
        });
        





    });
</script>