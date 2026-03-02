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
  <title>Administrar Catálogos</title>
</head>

<?php if (in_array(17, $permisos_array)) { ?>

<body>
  <div class="container-fluid py-3">
    <div class="row g-3">

      <!-- IZQUIERDA: Formulario -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header">
            <strong>Agregar catálogo</strong>
          </div>
          <div class="card-body">

            <div class="mb-2">
              <label class="form-label mb-1">¿Qué quieres agregar?</label>
              <select id="sel_catalogo" class="form-control form-control-sm">
                <option value="marcas">Marcas</option>
                <option value="uso_zona">Zonas</option>
                <option value="estados_insumos">Estados insumos</option>
              </select>
            </div>

            <div class="mb-2">
              <label class="form-label mb-1">Descripción</label>
              <input id="in_descripcion_catalogo" type="text" class="form-control form-control-sm"
                     placeholder="Ej: Samsung / SALON 1 / FUNCIONAL">
            </div>

            <div class="d-flex gap-2">
              <button id="btn_guardar_catalogo" type="button" class="btn btn-sm btn-primary">
                Guardar
              </button>

              <button id="btn_limpiar_catalogo" type="button" class="btn btn-sm btn-secondary">
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
            <strong id="lbl_titulo_lista">Listado</strong>
            <button id="btn_refrescar_lista" type="button" class="btn btn-sm btn-outline-secondary">
              Refrescar
            </button>
          </div>

          <div class="card-body">
            <div class="mb-2">
              <input id="in_buscar" type="text" class="form-control form-control-sm" placeholder="Buscar por descripción...">
            </div>

            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Descripción</th>
                    <th style="width: 110px;">Estado</th>
                    <th style="width: 90px;">Acción</th>
                  </tr>
                </thead>
                <tbody id="tbody_catalogos">
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
  function tituloPorTipo(tipo) {
    if (tipo === 'marcas') return 'Marcas';
    if (tipo === 'uso_zona') return 'Zonas';
    if (tipo === 'estados_insumos') return 'Estados insumos';
    return 'Listado';
  }

  function limpiarFormulario() {
    $('#in_descripcion_catalogo').val('').focus();
  }

  function cargarLista() {
    const tipo = $('#sel_catalogo').val();
    const q = $('#in_buscar').val().trim();

    $('#lbl_titulo_lista').text('Listado: ' + tituloPorTipo(tipo));
    $('#tbody_catalogos').html('<tr><td colspan="4" class="text-center text-muted">Cargando...</td></tr>');

    $.ajax({
      type: "GET",
      url: "../../controller/activos/catalogos_admin_list.php",
      data: { tipo: tipo, q: q },
      dataType: "json",
      success: function(resp) {
        if (!resp || resp.ok !== true) {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cargar el listado");
          $('#tbody_catalogos').html('<tr><td colspan="4" class="text-center text-muted">Error</td></tr>');
          return;
        }

        if (!resp.data || resp.data.length === 0) {
          $('#tbody_catalogos').html('<tr><td colspan="4" class="text-center text-muted">Sin resultados</td></tr>');
          return;
        }

        let html = '';
        resp.data.forEach(r => {
          const estadoTxt = (String(r.estado) === "1") ? "Activo" : "Inactivo";
          const estadoBadge = (String(r.estado) === "1")
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>';

          const btnTxt = (String(r.estado) === "1") ? "Desact." : "Act.";
          const btnClass = (String(r.estado) === "1") ? "btn-outline-danger" : "btn-outline-success";

          html += `
            <tr>
              <td>${r.id}</td>
              <td>${(r.descripcion || '').toString()}</td>
              <td>${estadoBadge}</td>
              <td>
                <button type="button"
                        class="btn btn-sm ${btnClass} btn_toggle_estado"
                        data-id="${r.id}"
                        data-estado="${r.estado}">
                  ${btnTxt}
                </button>
              </td>
            </tr>
          `;
        });

        $('#tbody_catalogos').html(html);
      },
      error: function(xhr) {
        console.log("ERROR list:", xhr.responseText);
        alertify.error("Error del servidor al listar");
        $('#tbody_catalogos').html('<tr><td colspan="4" class="text-center text-muted">Error</td></tr>');
      }
    });
  }

  function insertarCatalogo() {
    const tipo = $('#sel_catalogo').val();
    const descripcion = $('#in_descripcion_catalogo').val().trim();

    if (!descripcion) {
      alertify.error("La descripción es obligatoria");
      return;
    }

    $.ajax({
      type: "POST",
      url: "../../controller/activos/catalogos_admin_insert.php",
      data: { tipo: tipo, descripcion: descripcion },
      dataType: "json",
      success: function(resp) {
        if (resp && resp.ok) {
          alertify.success("Guardado correctamente");
          limpiarFormulario();
          cargarLista();
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

  function toggleEstado(id, estadoActual) {
    const tipo = $('#sel_catalogo').val();

    $.ajax({
      type: "POST",
      url: "../../controller/activos/catalogos_admin_toggle.php",
      data: { tipo: tipo, id: id, estado: estadoActual },
      dataType: "json",
      success: function(resp) {
        if (resp && resp.ok) {
          cargarLista();
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

  // Eventos
  $(document).ready(function() {
    cargarLista();

    $('#btn_guardar_catalogo').on('click', insertarCatalogo);
    $('#btn_limpiar_catalogo').on('click', limpiarFormulario);

    $('#sel_catalogo').on('change', function() {
      limpiarFormulario();
      cargarLista();
    });

    $('#btn_refrescar_lista').on('click', cargarLista);

    // Buscar (debounce simple)
    let t = null;
    $('#in_buscar').on('input', function() {
      clearTimeout(t);
      t = setTimeout(cargarLista, 250);
    });

    // Toggle estado
    $(document).on('click', '.btn_toggle_estado', function() {
      const id = $(this).data('id');
      const estado = $(this).data('estado');
      toggleEstado(id, estado);
    });

    // Enter para guardar
    $('#in_descripcion_catalogo').on('keypress', function(e) {
      if (e.which === 13) insertarCatalogo();
    });
  });
</script>
