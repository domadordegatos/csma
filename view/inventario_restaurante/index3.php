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
  <title>Catálogo - Productos y Unidades</title>
</head>

<?php if (in_array(22, $permisos_array)) { ?>

<body onload="iniciarPagina();">

  <div class="container-fluid py-3">
    <h3 class="text-white mb-3">Catálogo Inventario - Restaurante</h3>

    <div class="row g-3">

      <!-- =========================
           IZQUIERDA: PRODUCTOS
      ========================== -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong id="lbl_title_producto">Nuevo producto</strong>
            <button id="btn_refrescar_productos" type="button" class="btn btn-sm btn-outline-secondary">
              Refrescar
            </button>
          </div>

          <div class="card-body">

            <!-- Form Productos -->
            <input type="hidden" id="in_id_producto" value="">

            <div class="row g-2">
              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Descripción</label>
                <input id="in_desc_producto" type="text" class="form-control form-control-sm"
                       placeholder="Ej: Arroz, Carne, Frijol..." autocomplete="off">
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Unidad de medida</label>
                <select id="sel_unidad_producto" class="form-control form-control-sm"></select>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Cantidad inicial</label>
                <input id="in_cantidad_producto" type="number" class="form-control form-control-sm"
                       placeholder="Solo para crear (ej: 0 o 10)" value="0">
                <small class="text-muted">Solo se usa al crear. En edición queda bloqueado.</small>
              </div>

              <div class="col-12 col-md-6">
                <label class="form-label mb-1">Precio unitario</label>
                <input id="in_precio_producto" type="number" class="form-control form-control-sm"
                       placeholder="Ej: 5500" value="0">
              </div>
            </div>

            <div class="d-flex gap-2 mt-3">
              <button id="btn_guardar_producto" type="button" class="btn btn-sm btn-primary">
                Guardar
              </button>
              <button id="btn_cancelar_producto" type="button" class="btn btn-sm btn-secondary" disabled>
                Cancelar edición
              </button>
            </div>

            <hr class="my-3">

            <!-- Tabla Productos -->
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:70px;">ID</th>
                    <th>Descripción</th>
                    <th style="width:120px;">Unidad</th>
                    <th style="width:120px;">Precio</th>
                    <th style="width:110px;">Estado</th>
                    <th style="width:170px;">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbody_productos">
                  <tr><td colspan="6" class="text-center text-muted">Cargando...</td></tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

      <!-- =========================
           DERECHA: UNIDADES
      ========================== -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong id="lbl_title_unidad">Nueva unidad</strong>
            <button id="btn_refrescar_unidades" type="button" class="btn btn-sm btn-outline-secondary">
              Refrescar
            </button>
          </div>

          <div class="card-body">

            <!-- Form Unidades -->
            <input type="hidden" id="in_id_unidad" value="">

            <div class="row g-2">
              <div class="col-12">
                <label class="form-label mb-1">Descripción unidad</label>
                <input id="in_desc_unidad" type="text" class="form-control form-control-sm"
                       placeholder="Ej: PAQUETE, KILO, LIBRA, UNIDAD..." autocomplete="off">
              </div>
            </div>

            <div class="d-flex gap-2 mt-3">
              <button id="btn_guardar_unidad" type="button" class="btn btn-sm btn-primary">
                Guardar
              </button>
              <button id="btn_cancelar_unidad" type="button" class="btn btn-sm btn-secondary" disabled>
                Cancelar edición
              </button>
            </div>

            <hr class="my-3">

            <!-- Tabla Unidades -->
            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:80px;">ID</th>
                    <th>Descripción</th>
                    <th style="width:110px;">Estado</th>
                    <th style="width:170px;">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbody_unidades">
                  <tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>

</body>

<?php } ?>
</html>

<script>
  function iniciarPagina() {
    cargarUnidadesSelect();
    cargarTablaProductos();
    cargarTablaUnidades();
    eventos();
    resetFormProducto();
    resetFormUnidad();
  }

  // =========================
  // CARGAS
  // =========================
  function cargarUnidadesSelect(selectedId = '') {
    $("#sel_unidad_producto").load(
      "../../controller/restaurante/unidades_options.php",
      { selected: selectedId },
      function (response, status, xhr) {
        if (status === "error") {
          console.log(xhr.responseText);
          alertify.error("Error cargando unidades");
        }
      }
    );
  }

  function cargarTablaProductos() {
    $("#tbody_productos").load(
      "../../controller/restaurante/productos_list_render.php",
      {},
      function (response, status, xhr) {
        if (status === "error") {
          console.log(xhr.responseText);
          alertify.error("Error cargando productos");
        }
      }
    );
  }

  function cargarTablaUnidades() {
    $("#tbody_unidades").load(
      "../../controller/restaurante/unidades_list_render.php",
      {},
      function (response, status, xhr) {
        if (status === "error") {
          console.log(xhr.responseText);
          alertify.error("Error cargando unidades");
        }
      }
    );
  }

  // =========================
  // FORM RESETS
  // =========================
  function setModoProducto(edicion) {
    $("#lbl_title_producto").text(edicion ? "Editar producto" : "Nuevo producto");
    $("#btn_cancelar_producto").prop("disabled", !edicion);
    $("#in_cantidad_producto").prop("disabled", edicion); // cantidad solo para crear
  }

  function resetFormProducto() {
    $("#in_id_producto").val("");
    $("#in_desc_producto").val("");
    $("#in_cantidad_producto").val("0").prop("disabled", false);
    $("#in_precio_producto").val("0");
    cargarUnidadesSelect('');
    setModoProducto(false);
  }

  function setModoUnidad(edicion) {
    $("#lbl_title_unidad").text(edicion ? "Editar unidad" : "Nueva unidad");
    $("#btn_cancelar_unidad").prop("disabled", !edicion);
  }

  function resetFormUnidad() {
    $("#in_id_unidad").val("");
    $("#in_desc_unidad").val("");
    setModoUnidad(false);
  }

  // =========================
  // EVENTOS
  // =========================
  function eventos() {
    // Refrescar
    $(document).on("click", "#btn_refrescar_productos", function () {
      cargarTablaProductos();
    });

    $(document).on("click", "#btn_refrescar_unidades", function () {
      cargarTablaUnidades();
      cargarUnidadesSelect($("#sel_unidad_producto").val() || '');
    });

    // Guardar producto (crear/editar)
    $(document).on("click", "#btn_guardar_producto", function () {
      const id_producto = $("#in_id_producto").val().trim();
      const descripcion = $("#in_desc_producto").val().trim();
      const id_unidad   = $("#sel_unidad_producto").val();
      const cantidad    = $("#in_cantidad_producto").val();
      const precio      = $("#in_precio_producto").val();

      if (descripcion === "") {
        alertify.error("Escribe la descripción del producto");
        return;
      }
      if (!id_unidad) {
        alertify.error("Selecciona la unidad de medida");
        return;
      }

      $.ajax({
        type: "POST",
        url: "../../controller/restaurante/productos_guardar.php",
        data: {
          id_producto,
          descripcion,
          id_unidad,
          cantidad,
          precio_unitario: precio
        },
        dataType: "json",
        success: function (resp) {
          if (!resp || resp.ok !== true) {
            alertify.error(resp?.msg || "No se pudo guardar el producto");
            return;
          }
          alertify.success(resp.msg || "Producto guardado");
          resetFormProducto();
          cargarTablaProductos();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alertify.error("Error guardando producto");
        }
      });
    });

    // Cancelar edición producto
    $(document).on("click", "#btn_cancelar_producto", function () {
      resetFormProducto();
    });

    // Editar producto (carga al form)
    $(document).on("click", ".btn_editar_producto", function () {
      const id = $(this).data("id");
      const desc = $(this).data("desc");
      const unidad = $(this).data("unidad");
      const precio = $(this).data("precio");

      $("#in_id_producto").val(id);
      $("#in_desc_producto").val(desc);
      $("#in_precio_producto").val(precio);

      // cantidad bloqueada en edición
      $("#in_cantidad_producto").val("0").prop("disabled", true);

      // recargar select y seleccionar unidad
      cargarUnidadesSelect(String(unidad || ''));
      setModoProducto(true);
    });

    // Toggle producto
    $(document).on("click", ".btn_toggle_producto", function () {
      const id = $(this).data("id");
      const nuevo = $(this).data("nuevo"); // 1 o 0

      $.ajax({
        type: "POST",
        url: "../../controller/restaurante/productos_toggle.php",
        data: { id_producto: id, estado: nuevo },
        dataType: "json",
        success: function (resp) {
          if (!resp || resp.ok !== true) {
            alertify.error(resp?.msg || "No se pudo cambiar el estado");
            return;
          }
          alertify.success("Estado actualizado");
          cargarTablaProductos();
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alertify.error("Error cambiando estado");
        }
      });
    });

    // =====================
    // UNIDADES
    // =====================
    $(document).on("click", "#btn_guardar_unidad", function () {
      const id_unidad = $("#in_id_unidad").val().trim();
      const descripcion = $("#in_desc_unidad").val().trim();

      if (descripcion === "") {
        alertify.error("Escribe la descripción de la unidad");
        return;
      }

      $.ajax({
        type: "POST",
        url: "../../controller/restaurante/unidades_guardar.php",
        data: { id_unidad, descripcion },
        dataType: "json",
        success: function (resp) {
          if (!resp || resp.ok !== true) {
            alertify.error(resp?.msg || "No se pudo guardar la unidad");
            return;
          }
          alertify.success(resp.msg || "Unidad guardada");
          resetFormUnidad();
          cargarTablaUnidades();
          cargarUnidadesSelect($("#sel_unidad_producto").val() || '');
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alertify.error("Error guardando unidad");
        }
      });
    });

    $(document).on("click", "#btn_cancelar_unidad", function () {
      resetFormUnidad();
    });

    $(document).on("click", ".btn_editar_unidad", function () {
      const id = $(this).data("id");
      const desc = $(this).data("desc");

      $("#in_id_unidad").val(id);
      $("#in_desc_unidad").val(desc);
      setModoUnidad(true);
    });

    $(document).on("click", ".btn_toggle_unidad", function () {
      const id = $(this).data("id");
      const nuevo = $(this).data("nuevo");

      $.ajax({
        type: "POST",
        url: "../../controller/restaurante/unidades_toggle.php",
        data: { id_unidad: id, estado: nuevo },
        dataType: "json",
        success: function (resp) {
          if (!resp || resp.ok !== true) {
            alertify.error(resp?.msg || "No se pudo cambiar el estado");
            return;
          }
          alertify.success("Estado actualizado");
          cargarTablaUnidades();
          cargarUnidadesSelect($("#sel_unidad_producto").val() || '');
        },
        error: function (xhr) {
          console.log(xhr.responseText);
          alertify.error("Error cambiando estado");
        }
      });
    });
  }
</script>
