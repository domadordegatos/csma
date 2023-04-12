<?php require_once "../home/navbar.php" ;
require_once "../../model/conexion.php";
$conexion=conexion();
date_default_timezone_set('America/Bogota');
            $fecha= date('Y-m-d');
            $anio = date("Y");
            $mes = date("m");
$sql="SELECT *FROM carga_almuerzos
JOIN users_admins on users_admins.id_user = carga_almuerzos.id_admin ORDER BY id_carga desc LIMIT 100";
$result=mysqli_query($conexion,$sql);
  $maci=$anio.'-'.$mes.'-01';$macf=$anio.'-'.$mes.'-31';
  $mani=$anio.'-'.($mes-1).'-01';$manf=$anio.'-'.($mes-1).'-31';
  $prepe="SELECT valor FROM categoria_movimientos_almuerzos WHERE id_categoria = 21";
  $pregr="SELECT valor FROM categoria_movimientos_almuerzos WHERE id_categoria = 22";
  $predo="SELECT valor FROM categoria_movimientos_almuerzos WHERE id_categoria = 23";
  $presa="SELECT valor FROM categoria_movimientos_almuerzos WHERE id_categoria = 24";
  $rprepe=mysqli_query($conexion,$prepe); $drprepe=mysqli_fetch_row($rprepe);
  $rpregr=mysqli_query($conexion,$pregr); $drpregr=mysqli_fetch_row($rpregr);
  $rpredo=mysqli_query($conexion,$predo); $drpredo=mysqli_fetch_row($rpredo);
  $rpresa=mysqli_query($conexion,$presa); $drpresa=mysqli_fetch_row($rpresa);

  $mape="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  21 AND fecha BETWEEN '$maci' AND '$macf'";
  $magr="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  22 AND fecha BETWEEN '$maci' AND '$macf'";
  $mado="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  23 AND fecha BETWEEN '$maci' AND '$macf'";
  $masa="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  24 AND fecha BETWEEN '$maci' AND '$macf'";
  $rmape=mysqli_query($conexion,$mape); $drmape=mysqli_fetch_row($rmape);
  $rmagr=mysqli_query($conexion,$magr); $drmagr=mysqli_fetch_row($rmagr);
  $rmado=mysqli_query($conexion,$mado); $drmado=mysqli_fetch_row($rmado);
  $rmasa=mysqli_query($conexion,$masa); $drmasa=mysqli_fetch_row($rmasa);

  $mantpe="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  21 AND fecha BETWEEN '$mani' AND '$manf'";
  $mantgr="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  22 AND fecha BETWEEN '$mani' AND '$manf'";
  $mantdo="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  23 AND fecha BETWEEN '$mani' AND '$manf'";
  $mantsa="SELECT SUM(cantidad) FROM movimientos_almuerzos WHERE tipo_venta = 1 AND categoria =  24 AND fecha BETWEEN '$mani' AND '$manf'";
  $rmantpe=mysqli_query($conexion,$mantpe); $drmantpe=mysqli_fetch_row($rmantpe);
  $rmantgr=mysqli_query($conexion,$mantgr); $drmantgr=mysqli_fetch_row($rmantgr);
  $rmantdo=mysqli_query($conexion,$mantdo); $drmantdo=mysqli_fetch_row($rmantdo);
  $rmantsa=mysqli_query($conexion,$mantsa); $drmantsa=mysqli_fetch_row($rmantsa);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Almuerzos</title>
    <?php require_once "../../model/libraries/lib.php"; ?>
</head>
<?php if($ver[5] == 2 || $ver[5] == 3){ ?>
<body>
    <div class="container">
    <div class="contendor_principal mt-2">
        <table class="table table-sm table-bordered text-white">
          <tr class="table-warning text-dark">
            <td colspan="4" class="text-center">VENTAS DE ESTE MES</td>
          </tr>
          <tr class="text-center">
            <td>Pequeños</td>
            <td>Grandes</td>
            <td>Docentes</td>
            <td>Saludables</td>
          </tr>
          <tr class="text-center">
            <td><?php echo $drmape[0]; ?></td>
            <td><?php echo $drmagr[0]; ?></td>
            <td><?php echo $drmado[0]; ?></td>
            <td><?php echo $drmasa[0]; ?></td>
          </tr>
          <tr class="text-right">
            <td colspan="4">Ganancias: $<?php echo (($drmape[0]*$drprepe[0])+($drmagr[0]*$drpregr[0])+($drmado[0]*$drpredo[0])+($drmasa[0]*$drpresa[0])) ?></td>
          </tr>
          <tr class="table-warning text-dark">
            <td colspan="4" class="text-center">VENTAS MES PASADO</td>
          </tr>
          <tr class="text-center">
            <td>Pequeños</td>
            <td>Grandes</td>
            <td>Docentes</td>
            <td>Saludables</td>
          </tr>
          <tr class="text-center">
            <td><?php echo $drmantpe[0]; ?></td>
            <td><?php echo $drmantgr[0]; ?></td>
            <td><?php echo $drmantdo[0]; ?></td>
            <td><?php echo $drmantsa[0]; ?></td>
          </tr>
          <tr class="text-right">
            <td colspan="4">Ganancias: $<?php echo (($drmantpe[0]*$drprepe[0])+($drmantgr[0]*$drpregr[0])+($drmantdo[0]*$drpredo[0])+($drmantsa[0]*$drpresa[0])) ?></td>
          </tr>
        </table>
      </div>
      <div class="contendor1 text-white mt-3">
        <h3>Historial de Pedidos</h3>
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-bordered table-sm">
              <tr class="table-warning">
                <th>Fecha</th>
                <th>Hora</th>
                <th>Solicitante</th>
                <th>Pequeños</th>
                <th>Grandes</th>
                <th>Docentes</th>
                <th>Saludables</th>
              </tr>
              <?php while ($ver=mysqli_fetch_row($result)):?>
              <tr class="text-white">
                <th><?php echo $ver[6]; ?></th>
                <th><?php echo $ver[7]; ?></th>
                <th><?php echo $ver[9]; ?></th>
                <th class="text-center"><?php echo $ver[2]; ?></th>
                <th class="text-center"><?php echo $ver[3]; ?></th>
                <th class="text-center"><?php echo $ver[4]; ?></th>
                <th class="text-center"><?php echo $ver[5]; ?></th>
              </tr>
              <?php endwhile; ?>
            </table>
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
                    $("#codigo").val('');
                    $("#codigo").focus();
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
                    $("#codigo").val('');
                    $("#codigo").focus();
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
                    $("#codigo").val('');
                    $("#codigo").focus();
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