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
    <title>Arqueo Almuerzos</title>
</head>
<body style="overflow-x: hidden;">
    <div class="contendor w-100 d-flex">

        <div class="separador1 w-25 d-flex justify-content-center">
            <div class="subcontenedor mt-5 text-white">
                <div class="row">
                    <div class="col-sm-12">
                        <h4>Fecha del Arqueo</h4>
                    </div>
                </div>

                
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo $fecha; ?>">
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <button class="btn btn-sm btn-success btn-lg btn-block" onclick="uno()">Consultar dia</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-sm-12">
                        <button class="btn btn-sm btn-success btn-lg btn-block" onclick="todos()">Consultar este Mes</button>
                    </div>
                </div>
                <hr style="border-top: 1px solid white;">
<div class="row mt-3">
    <div class="col-sm-12">
        <label>Desde:</label>
        <input type="date" class="form-control form-control-sm" id="f_inicio">
    </div>
</div>
<div class="row mt-2">
    <div class="col-sm-12">
        <label>Hasta:</label>
        <input type="date" class="form-control form-control-sm" id="f_final">
    </div>
</div>
<div class="row mt-3">
    <div class="col-sm-12">
        <button class="btn btn-sm btn-info btn-block" onclick="rangoAlmuerzos()">Consultar Rango</button>
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
    function rangoAlmuerzos() {
    const inicio = $('#f_inicio').val();
    const fin = $('#f_final').val();

    if (inicio && fin) {
        $.ajax({
            type: "GET",
            url: "../../controller/arqueos/rango_almuerzos.php",
            data: { start_date: inicio, end_date: fin },
            success: function(r) {
                if (r.trim() == "1") {
                    $('#tabla_consulta').load("temp_uno.php");
                } else {
                    alertify.error("No hay registros en este rango");
                }
            }
        });
    } else {
        alertify.warning("Seleccione ambas fechas");
    }
}
    function uno(){
        cadena = "form1=" + 'A' +
                "&form2=" + $('#fecha').val();
        $.ajax({
            type: "POST",
            url: "../../controller/arqueos/buscar_dos.php", //validacion de datos de registro
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

    function todos(){
        cadena = "form1=" + 'B' +
                "&form2=" + $('#fecha').val();
        $.ajax({
            type: "POST",
            url: "../../controller/arqueos/buscar_dos.php", //validacion de datos de registro
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
</script>