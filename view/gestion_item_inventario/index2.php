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
  <title>Administrar Tipos de Activo</title>
</head>

<?php if (in_array(16, $permisos_array)) { ?>

<body>
  <div class="container-fluid py-3">
    <div class="row g-3">

      <!-- IZQUIERDA: Formulario -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header">
            <strong>Agregar tipo de activo</strong>
          </div>
          <div class="card-body">

            <div class="mb-2">
              <label class="form-label mb-1">Código (numérico)</label>
              <input id="in_codigo_tipo" type="number" class="form-control form-control-sm" placeholder="Ej: 101">
            </div>

            <div class="mb-2">
              <label class="form-label mb-1">Descripción</label>
              <input id="in_descripcion_tipo" type="text" class="form-control form-control-sm" placeholder="Ej: Impresora">
            </div>

            <div class="d-flex gap-2">
              <button id="btn_guardar_tipo" type="button" class="btn btn-sm btn-primary">
                Guardar
              </button>

              <button id="btn_limpiar_tipo" type="button" class="btn btn-sm btn-secondary">
                Limpiar
              </button>
            </div>

            <hr>
            <div class="small text-muted">
              Se insertará con <code>estado = 1</code> por defecto.
            </div>

          </div>
        </div>
      </div>

      <!-- DERECHA: Listado -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Listado: Tipos de Activo</strong>
            <button id="btn_refrescar_tipo" type="button" class="btn btn-sm btn-outline-secondary">
              Refrescar
            </button>
          </div>

          <div class="card-body">
            <div class="row g-2 mb-2">
              <div class="col-12 col-md-6">
                <input id="in_buscar_tipo" type="text" class="form-control form-control-sm"
                       placeholder="Buscar por descripción o código...">
              </div>
              <div class="col-12 col-md-6">
                <select id="sel_filtro_estado_tipo" class="form-control form-control-sm">
                  <option value="all">Todos</option>
                  <option value="1" selected>Activos</option>
                  <option value="0">Inactivos</option>
                </select>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 70px;">ID</th>
                    <th style="width: 90px;">Código</th>
                    <th>Descripción</th>
                    <th style="width: 110px;">Estado</th>
                    <th style="width: 90px;">Acción</th>
                  </tr>
                </thead>
                <tbody id="tbody_tipo_activo">
                  <tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>
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
  function limpiarTipo() {
    $('#in_codigo_tipo').val('');
    $('#in_descripcion_tipo').val('').focus();
  }

  function cargarTipoActivo() {
    const q = $('#in_buscar_tipo').val().trim();
    const estado = $('#sel_filtro_estado_tipo').val();

    $('#tbody_tipo_activo').html('<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>');

    $.ajax({
      type: "GET",
      url: "../../controller/activos/tipo_activo_list.php",
      data: { q: q, estado: estado },
      dataType: "json",
      success: function(resp) {
        if (!resp || resp.ok !== true) {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cargar el listado");
          $('#tbody_tipo_activo').html('<tr><td colspan="5" class="text-center text-muted">Error</td></tr>');
          return;
        }

        if (!resp.data || resp.data.length === 0) {
          $('#tbody_tipo_activo').html('<tr><td colspan="5" class="text-center text-muted">Sin resultados</td></tr>');
          return;
        }

        let html = '';
        resp.data.forEach(r => {
          const estadoBadge = (String(r.estado) === "1")
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>';

          const btnTxt = (String(r.estado) === "1") ? "Desact." : "Act.";
          const btnClass = (String(r.estado) === "1") ? "btn-outline-danger" : "btn-outline-success";

          html += `
            <tr>
              <td>${r.id}</td>
              <td>${r.codigo}</td>
              <td>${(r.descripcion || '').toString()}</td>
              <td>${estadoBadge}</td>
              <td>
                <button type="button"
                        class="btn btn-sm ${btnClass} btn_toggle_tipo"
                        data-id="${r.id}"
                        data-estado="${r.estado}">
                  ${btnTxt}
                </button>
              </td>
            </tr>
          `;
        });

        $('#tbody_tipo_activo').html(html);
      },
      error: function(xhr) {
        console.log("ERROR list:", xhr.responseText);
        alertify.error("Error del servidor al listar");
        $('#tbody_tipo_activo').html('<tr><td colspan="5" class="text-center text-muted">Error</td></tr>');
      }
    });
  }

  function insertarTipoActivo() {
    const codigo = $('#in_codigo_tipo').val().trim();
    const descripcion = $('#in_descripcion_tipo').val().trim();

    if (!codigo) {
      alertify.error("El código es obligatorio");
      return;
    }
    if (!/^\d+$/.test(codigo)) {
      alertify.error("El código debe ser numérico");
      return;
    }
    if (!descripcion) {
      alertify.error("La descripción es obligatoria");
      return;
    }

    $.ajax({
      type: "POST",
      url: "../../controller/activos/tipo_activo_insert.php",
      data: { codigo: codigo, descripcion: descripcion },
      dataType: "json",
      success: function(resp) {
        if (resp && resp.ok) {
          alertify.success("Guardado correctamente");
          limpiarTipo();
          cargarTipoActivo();
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo guardar");
        }
      },
      error: function(xhr) {
        console.log("ERROR insert:", xhr.responseText);
        alertify.error("Error del servidor al guardar");
      }
    });
  }

  function toggleTipo(id, estadoActual) {
    $.ajax({
      type: "POST",
      url: "../../controller/activos/tipo_activo_toggle.php",
      data: { id: id, estado: estadoActual },
      dataType: "json",
      success: function(resp) {
        if (resp && resp.ok) {
          cargarTipoActivo();
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cambiar estado");
        }
      },
      error: function(xhr) {
        console.log("ERROR toggle:", xhr.responseText);
        alertify.error("Error del servidor al cambiar estado");
      }
    });
  }

  $(document).ready(function() {
    cargarTipoActivo();

    $('#btn_guardar_tipo').on('click', insertarTipoActivo);
    $('#btn_limpiar_tipo').on('click', limpiarTipo);
    $('#btn_refrescar_tipo').on('click', cargarTipoActivo);

    // Buscar (debounce)
    let t = null;
    $('#in_buscar_tipo').on('input', function() {
      clearTimeout(t);
      t = setTimeout(cargarTipoActivo, 250);
    });

    $('#sel_filtro_estado_tipo').on('change', cargarTipoActivo);

    // Enter para guardar
    $('#in_descripcion_tipo').on('keypress', function(e) {
      if (e.which === 13) insertarTipoActivo();
    });

    // Toggle
    $(document).on('click', '.btn_toggle_tipo', function() {
      const id = $(this).data('id');
      const estado = $(this).data('estado');
      toggleTipo(id, estado);
    });
  });
</script>
