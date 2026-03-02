<?php require_once "../home/navbar.php"; ?>
<?php $user = $_SESSION['user']; ?>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <?php require_once "../../model/libraries/lib.php"; ?>
  <title>PREFERENCIAS PRODUCTOS</title>
  <link rel="icon" type="image/png" href="../media/recursos/ico.png" />

  <style>
    .product-btn { position: relative; }
    .product-btn .checkmark{
      position:absolute; top:4px; right:6px;
      width:22px; height:22px; border-radius:50%;
      display:none; align-items:center; justify-content:center;
      background:#28a745; color:white; font-weight:bold;
      font-size:14px;
    }
    .product-btn.selected{ outline:3px solid #28a745; opacity:1; }
    .product-btn.selected .checkmark{ display:flex; }
    .product-btn:not(.selected){ opacity:0.45; }
  </style>
</head>

<body style="overflow-x:hidden !important;">
<?php if (in_array(3, $permisos_array)) { ?>

  <div class="container-x d-flex mt-4">

    <!-- PANEL IZQUIERDO -->
    <div class="contenedor1" style="width:30%; margin-left:3rem; margin-right:3rem;">

      <div class="row my-3">
        <div class="col-sm-6 border border-white d-flex justify-content-center align-items-center"
             style="border-top-left-radius:20px; border-bottom-left-radius:20px;">
          <img src="../media/recursos/profile.png" id="u_img" width="95%" alt="">
        </div>

        <div class="col-sm-6">
          <div class="row border border-white p-1" style="border-top-right-radius:20px;">
            <div class="col-sm-12">
              <label class="text-white">Nombre:
                <input type="text" disabled id="u_name" class="form-control form-control-sm">
              </label>
            </div>
          </div>

          <div class="row border border-white p-1">
            <div class="col-sm-12">
              <label class="text-white">Apellido:
                <input type="text" disabled id="u_last_name" class="form-control form-control-sm">
              </label>
            </div>
          </div>

          <div class="row border border-white p-1">
            <div class="col-sm-12">
              <label class="text-white">Grado:
                <input type="text" disabled id="u_grade" class="form-control form-control-sm">
              </label>
            </div>
          </div>

          <div class="row border border-white p-1">
            <div class="col-sm-12">
              <label class="text-white">Id:
                <input type="text" disabled id="u_id" class="form-control form-control-sm">
              </label>
            </div>
          </div>

          <div class="row border border-white p-1" style="border-bottom-right-radius:20px;">
            <div class="col-sm-12">
              <label class="text-white">Saldo: $
                <input type="text" disabled id="u_coin" class="form-control form-control-sm">
              </label>
            </div>
          </div>

          <!-- ID real BD -->
          <input type="hidden" id="u_id_bd">
        </div>
      </div>

      <!-- Búsqueda por código -->
      <div class="row w-100 mb-2">
        <div class="col-sm-12 d-flex justify-content-center pt-0 mt-0">
          <button class="btn btn-success" onclick="location.reload()">SCAN</button>
          <input class="form-control col-sm-5 mt-1 mx-2" autofocus type="text" id="codigo" name="codigo" placeholder="123..">
          <button class="btn btn-primary" onclick="solicitar_informacion()">Buscar</button>
        </div>
      </div>

      <div class="row mt-2 w-100">
        <div class="col-sm-12">
          <div id="pref_status" class="text-white"></div>
        </div>
      </div>

    </div>

    <!-- PANEL DERECHO -->
    <div class="contenedor2" style="width:75%; margin-right:3rem;">

      <div class="row w-100">
        <div class="col-sm-12 d-flex justify-content-between align-items-center">
          <h3 class="text-white">Preferencias de productos</h3>
          <div>
            <button class="btn btn-success" onclick="seleccionar_todo()">Seleccionar todo</button>
            <button class="btn btn-warning" onclick="quitar_todo()">Quitar todo</button>
            <button class="btn btn-primary" onclick="guardar_preferencias()">Guardar</button>
          </div>
        </div>
      </div>

      <div class="row w-100 mt-2">
        <div class="col-sm-12" id="productos_pref_grid">
          <div class="text-white">Busca un usuario para cargar productos.</div>
        </div>
      </div>

    </div>
  </div>

<script>
  $(document).ready(function() {
    $('#codigo').on('keypress', function(e){
      if(e.which === 13){ solicitar_informacion(); }
    });
  });

  function solicitar_informacion() {
    let cadena = "form1=" + $('#codigo').val();
    $.ajax({
      type: "POST",
      url: "../../controller/datos_usuario.php",
      data: cadena,
      success: function(r) {
        let dato = jQuery.parseJSON(r);
        cargar_usuario(dato, $('#codigo').val());
      }
    });
  }

  function cargar_usuario(dato, codigo) {
    $('#u_name').val(dato['0']);
    $('#u_last_name').val(dato['1']);
    $('#u_grade').val(dato['2']);
    $('#u_id').val(dato['3']);
    $('#u_coin').val(dato['4']);
    $('#u_id_bd').val(dato['6']);
    $('#codigo').val(codigo);

    // foto
    let idFoto = dato['6'];
    fetch("../media/fotos_usuarios/" + idFoto + ".jpg")
      .then(res => {
        if(res.ok){
          $("#u_img").attr("src", "../media/fotos_usuarios/" + idFoto + ".jpg");
        } else {
          $("#u_img").attr("src", "../media/recursos/profile.png");
        }
      })
      .catch(() => $("#u_img").attr("src", "../media/recursos/profile.png"));

    cargar_grid_preferencias();
  }

  function cargar_grid_preferencias(){
    let idUsuario = $('#u_id_bd').val();
    if(!idUsuario){
      $('#productos_pref_grid').html("<div class='text-white'>Busca un usuario para cargar productos.</div>");
      return;
    }

    $('#productos_pref_grid').load('../../controller/preferencias_productos.php?action=render&id_usuario=' + encodeURIComponent(idUsuario), function(){
      let has = $('#pref_has_record').val();
      if(has === '1'){
        $('#pref_status').html("Modo: <b>Con restricciones</b> (solo los seleccionados serán permitidos).");
      } else {
        $('#pref_status').html("Modo: <b>Sin restricciones</b> (por defecto se permiten todos). Si desmarcas y guardas, se guardará la restricción.");
      }
    });
  }

  function toggle_pref(btn){
    btn.classList.toggle('selected');
  }

  function seleccionar_todo(){
    $('#productos_pref_grid .product-btn').addClass('selected');
  }

  function quitar_todo(){
    $('#productos_pref_grid .product-btn').removeClass('selected');
  }

  function limpiar_busqueda(){
    $('#codigo').val('');
    $('#u_name').val('');
    $('#u_last_name').val('');
    $('#u_grade').val('');
    $('#u_id').val('');
    $('#u_coin').val('');
    $('#u_id_bd').val('');
    $("#u_img").attr("src", "../media/recursos/profile.png");
    $('#productos_pref_grid').html("<div class='text-white'>Busca un usuario para cargar productos.</div>");
    $('#pref_status').html('');
    $('#codigo').focus();
  }

function guardar_preferencias(){
  let idUsuario = $('#u_id_bd').val();
  if(!idUsuario){
    alertify.error("Primero busca un usuario.");
    return;
  }

  let ids = [];
  $('#productos_pref_grid .product-btn.selected').each(function(){
    ids.push($(this).data('id'));
  });

  $.ajax({
    type: "POST",
    url: "../../controller/preferencias_productos.php?action=save",
    // CLAVE: enviar como ids[] para que PHP lo reciba como array
    data: { id_usuario: idUsuario, 'ids[]': ids },
    dataType: "json",
    success: function(resp){
      if(resp.ok){
        alertify.success(resp.msg);
        limpiar_busqueda();
      } else {
        alertify.error(resp.msg || "Error guardando preferencias");
      }
    }
  });
}
</script>

<?php } else { ?>
  <div class="container mt-5">
    <h2 class="text-danger text-center">No tienes permisos para entrar aquí.</h2>
  </div>
<?php } ?>
</body>
</html>