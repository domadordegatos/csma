<?php 
    require_once "../home/navbar.php";
    require_once "../../model/conexion.php";
    require_once "../../model/libraries/lib.php";
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
    <title>Precios</title>
</head>
<?php if($ver[5] == 2 || $ver[5] == 3){ ?>

<body>
    <div class="container">
        <div class="contendor1 w-75 mt-3">
            <h3 class="text-white">Actualización de Precios</h3>
            <div class="form">
                <div class="form-group row">
                    <label for="cafeteria" class="text-white col-sm-2 col-form-label">Cafetería</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="cafeteria" id="cafeteria">
                            <option value="A">Cafetería...</option>
                            <?php $sql="SELECT * FROM productos WHERE estado =1";
                                              $result=mysqli_query($conexion,$sql);
                                              while ($ver=mysqli_fetch_row($result)):?>
                            <option value=<?php echo $ver[0]; ?>>
                                <?php echo $ver[1]; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="val_cafeteria" placeholder="$...">
                    </div>
                    <button class="btn btn-sm btn-warning" onclick="actualizarCafeteria()">Actualizar</button>
                </div>
            </div>

            <div class="form">
                <div class="form-group row">
                    <label for="almuerzos" class="text-white col-sm-2 col-form-label">Almuerzos</label>
                    <div class="col-sm-5">
                        <select class="form-control" name="almuerzos" id="almuerzos">
                            <option value="A">Almuerzos...</option>
                            <?php $sql="SELECT * FROM categoria_movimientos_almuerzos where estado =1";
                                              $result=mysqli_query($conexion,$sql);
                                              while ($ver=mysqli_fetch_row($result)):?>
                            <option value=<?php echo $ver[0]; ?>>
                                <?php echo $ver[1]; ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" id="val_almuerzos" placeholder="$...">
                    </div>
                    <button class="btn btn-sm btn-warning" onclick="actualizarAlmuerzos()">Actualizar</button>
                </div>
            </div>
        </div>
        <div class="contendor2">
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
        /*background-image: url(../media/recursos/fondo.png);*/
        background: rgb(170,58,180);
background: linear-gradient(142deg, rgba(170,58,180,1) 0%, rgba(253,29,29,1) 50%, rgba(220,252,69,1) 100%);
        background-position: center;
        background-attachment: fixed;
        background-size: cover;
    }
</style>

<script>
    $(document).ready(function () {
        $("select[name=cafeteria]").change(function () {
            cadena = "form1=" + $('#cafeteria').val();
            $.ajax({
                type: "POST",
                url: "../../controller/funcionalidades/datos_cafeteria.php", //validacion de datos de registro
                data: cadena,
                success: function (r) {
                    dato = jQuery.parseJSON(r);
                    $('#val_cafeteria').val(dato['0']);
                }
            });
        });
    });

    function actualizarCafeteria() {
        if ($('#val_cafeteria').val() == '') {
            alertify.error("debes llenar todos los campos");
        } else {
            cadena = "form1=" + $('#cafeteria').val() +
                "&form2=" + $('#val_cafeteria').val();

            $.ajax({
                type: "POST",
                url: "../../controller/funcionalidades/actualizar_cafeteria.php", //validacion de datos de registro
                data: cadena,
                success: function (r) {
                    if (r == 1) {
                        alertify.success("Actualizacion de precio exitosa!!");
                        return false;
                    } else if (r == 3) {
                        alertify.error("No puedes recargar valores negativos o nulos");
                        return false;
                    } else if (r == 2) {
                        alertify.error("error desconocido en el sistema");
                        return false;
                    }
                }
            });
        }
    }

    $(document).ready(function () {
        $("select[name=almuerzos]").change(function () {
            cadena = "form1=" + $('#almuerzos').val();
            $.ajax({
                type: "POST",
                url: "../../controller/funcionalidades/datos_almuerzos.php", //validacion de datos de registro
                data: cadena,
                success: function (r) {
                    dato = jQuery.parseJSON(r);
                    $('#val_almuerzos').val(dato['0']);
                }
            });
        });
    });

    function actualizarAlmuerzos() {
        if ($('#val_almuerzos').val() == '') {
            alertify.error("debes llenar todos los campos");
        } else {
            cadena = "form1=" + $('#almuerzos').val() +
                "&form2=" + $('#val_almuerzos').val();

            $.ajax({
                type: "POST",
                url: "../../controller/funcionalidades/actualizar_almuerzos.php", //validacion de datos de registro
                data: cadena,
                success: function (r) {
                    if (r == 1) {
                        alertify.success("Actualizacion de precio exitosa!!");
                        return false;
                    } else if (r == 3) {
                        alertify.error("No puedes recargar valores negativos o nulos");
                        return false;
                    } else if (r == 2) {
                        alertify.error("error desconocido en el sistema");
                        return false;
                    }
                }
            });
        }
    }
</script>