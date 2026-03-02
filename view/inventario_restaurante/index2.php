<?php
require_once "../home/navbar.php";
require_once "../../model/conexion.php";
require_once "../../model/libraries/lib.php";
$conexion = conexion();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facturas - Inventario Restaurante</title>
</head>

<?php if (in_array(21, $permisos_array)) { ?>

<body onload="iniciarPagina();">

  <div class="container-x w-100">
    <div class="separador1 mx-3 my-3">

      <div class="row g-3">

        <!-- IZQUIERDA (50%) -->
        <div class="col-12 col-lg-6">

          <!-- Barra superior: título + filtro -->
          <div class="d-flex align-items-center justify-content-between flex-wrap mb-2" style="gap:10px;">
            <h3 class="text-white m-0">Facturas</h3>

            <div class="d-flex align-items-center flex-wrap" style="gap:10px;">
              <div class="d-flex align-items-center" style="gap:6px;">
                <label class="text-white m-0 small"><b>Fecha:</b></label>
                <input id="in_fecha_factura" type="date" class="form-control form-control-sm" style="width: 170px;">
              </div>

              <button id="btn_refrescar_facturas" type="button" class="btn btn-sm btn-outline-light">
                Refrescar
              </button>

              <button id="btn_limpiar_filtro" type="button" class="btn btn-sm btn-secondary">
                Ver recientes
              </button>
            </div>
          </div>

          <!-- Tabla facturas -->
          <div class="table-responsive">
            <table class="table table-sm table-striped align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th style="width:90px;">Factura</th>
                  <th style="width:120px;">Fecha</th>
                  <th style="width:110px;">Hora</th>
                  <th style="width:160px;">Costo ingreso</th>
                  <th>Descripción</th>
                  <th style="width:90px;">Ver</th>
                </tr>
              </thead>

              <tbody id="tbody_facturas">
                <tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>
              </tbody>
            </table>
          </div>

        </div>

        <!-- DERECHA (50%) -->
        <div class="col-12 col-lg-6">

          <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
              <b>Detalle de factura</b>

              <button type="button" id="btn_cerrar_detalle" class="btn btn-sm btn-outline-secondary" disabled>
                Cerrar
              </button>
            </div>

            <div class="card-body" id="panel_detalle_factura">
              <div class="text-muted">
                Selecciona una factura y presiona <b>Ver</b> para cargar el detalle.
              </div>
            </div>
          </div>

        </div>

      </div><!-- row -->

    </div>
  </div>

</body>

<?php } ?>
</html>

<script>
  let filtroFechaActiva = false;

  function iniciarPagina() {
    setFechaHoy();
    cargar_facturas();
    eventos();
  }

  function setFechaHoy() {
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    $('#in_fecha_factura').val(`${yyyy}-${mm}-${dd}`);
  }

  function obtenerFiltros() {
    return {
      // solo filtra si el usuario activó filtro
      fecha: filtroFechaActiva ? ($('#in_fecha_factura').val() || '') : ''
    };
  }

  function cargar_facturas() {
    const filtros = obtenerFiltros();

    $('#tbody_facturas').html('<tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>');

    $('#tbody_facturas').load(
      "../../controller/restaurante/facturas_tabla_render.php",
      filtros,
      function (response, status, xhr) {
        if (status === "error") {
          console.log("ERROR load facturas:", xhr.responseText);
          alertify.error("Error cargando facturas");
          $('#tbody_facturas').html('<tr><td colspan="6" class="text-center text-muted">Error</td></tr>');
        }
      }
    );
  }

  function cargar_detalle_factura(id_factura) {
    $("#panel_detalle_factura").html('<div class="text-muted">Cargando detalle...</div>');

    $("#panel_detalle_factura").load(
      "../../controller/restaurante/factura_detalle_render.php",
      { id_factura: id_factura },
      function (response, status, xhr) {
        if (status === "error") {
          console.log("ERROR load detalle:", xhr.responseText);
          alertify.error("Error cargando detalle");
          $("#panel_detalle_factura").html('<div class="text-muted">Error cargando detalle</div>');
          $("#btn_cerrar_detalle").prop("disabled", true);
          return;
        }

        $("#btn_cerrar_detalle").prop("disabled", false);
      }
    );
  }

  function cerrar_detalle() {
    $("#panel_detalle_factura").html(
      '<div class="text-muted">Selecciona una factura y presiona <b>Ver</b> para cargar el detalle.</div>'
    );
    $("#btn_cerrar_detalle").prop("disabled", true);
  }

  function eventos() {
    $(document).on('change', '#in_fecha_factura', function () {
      filtroFechaActiva = true;
      cargar_facturas();
    });

    $(document).on('click', '#btn_refrescar_facturas', function () {
      cargar_facturas();
    });

    $(document).on('click', '#btn_limpiar_filtro', function () {
      filtroFechaActiva = false;
      cargar_facturas();
    });

    $(document).on('click', '.btn_ver_factura', function () {
      const id = $(this).data('id');
      cargar_detalle_factura(id);
    });

    $(document).on('click', '#btn_cerrar_detalle', function () {
      cerrar_detalle();
    });
  }
</script>
