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
  <title>Asignaciones de Activos</title>
</head>

<?php if (in_array(19, $permisos_array)) { ?>

<body>
  <div class="container-fluid py-3">
    <h3 class="text-white mb-3">Asignaciones de activos (responsables)</h3>

    <div class="row g-3">

      <!-- IZQUIERDA: Formulario -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header">
            <strong id="lbl_form_title">Nueva asignación</strong>
          </div>

          <div class="card-body">
            <input type="hidden" id="in_id_asignacion" value="">
            <input type="hidden" id="in_id_usuario" value="">
            <input type="hidden" id="in_id_activo" value="">

            <!-- BUSCAR USUARIO -->
            <div class="mb-2 position-relative">
              <label class="form-label mb-1">Usuario (buscar por id / nombre / apellido / documento)</label>
              <input id="in_buscar_usuario" type="text" class="form-control form-control-sm"
       placeholder="Buscar por ID, nombre, apellido o tarjeta (id_tarjeta)">
              <div id="sug_usuarios" class="list-group position-absolute w-100" style="z-index: 9999; display:none;"></div>
              <small class="text-muted d-block mt-1" id="lbl_usuario_sel">Ningún usuario seleccionado</small>
            </div>

            <!-- BUSCAR ACTIVO -->
            <div class="mb-2 position-relative">
              <label class="form-label mb-1">Activo (buscar por id / CSMA / Equipo)</label>
              <input id="in_buscar_activo" type="text" class="form-control form-control-sm"
                     placeholder="Ej: 6 o 2025... o 0BCH...">
              <div id="sug_activos" class="list-group position-absolute w-100" style="z-index: 9999; display:none;"></div>
              <small class="text-muted d-block mt-1" id="lbl_activo_sel">Ningún activo seleccionado</small>
            </div>

            <!-- ESTADO -->
            <div class="mb-3">
              <label class="form-label mb-1">Estado</label>
              <select id="sel_estado_asig" class="form-control form-control-sm">
                <option value="1" selected>Activo (1)</option>
                <option value="0">Inactivo (0)</option>
              </select>
              <small class="text-muted">Por defecto: 1</small>
            </div>

            <!-- BOTONES -->
            <div class="d-flex gap-2">
              <button id="btn_guardar_asig" type="button" class="btn btn-sm btn-primary">
                Guardar
              </button>
              <button id="btn_cancelar_edicion" type="button" class="btn btn-sm btn-secondary" disabled>
                Cancelar edición
              </button>
            </div>

          </div>
        </div>
      </div>

      <!-- DERECHA: Listado -->
      <div class="col-12 col-lg-6">
        <div class="card shadow-sm">
          <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Listado: asignaciones</strong>
            <button id="btn_refrescar_asig" type="button" class="btn btn-sm btn-outline-secondary">
              Refrescar
            </button>
          </div>

          <div class="card-body">
            <div class="row g-2 mb-2">
              <div class="col-12 col-md-6">
                <input id="in_buscar_asig" type="text" class="form-control form-control-sm"
                       placeholder="Buscar por usuario o por activo...">
              </div>
              <div class="col-12 col-md-6">
                <select id="sel_filtro_estado_asig" class="form-control form-control-sm">
                  <option value="all" selected>Todos</option>
                  <option value="1">Activos</option>
                  <option value="0">Inactivos</option>
                </select>
              </div>
            </div>

            <div class="table-responsive">
              <table class="table table-sm table-striped align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th style="width:80px;">ID</th>
                    <th>Activo</th>
                    <th>Usuario</th>
                    <th style="width:110px;">Estado</th>
                    <th style="width:140px;">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tbody_asig">
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
  // =========================
  // Helpers
  // =========================
  function escapeHtml(s) {
    return String(s ?? '')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function setModoEdicion(on) {
    $('#btn_cancelar_edicion').prop('disabled', !on);
    $('#lbl_form_title').text(on ? 'Editar asignación' : 'Nueva asignación');
  }

  function limpiarForm() {
    $('#in_id_asignacion').val('');
    $('#in_id_usuario').val('');
    $('#in_id_activo').val('');
    $('#in_buscar_usuario').val('');
    $('#in_buscar_activo').val('');
    $('#lbl_usuario_sel').text('Ningún usuario seleccionado');
    $('#lbl_activo_sel').text('Ningún activo seleccionado');
    $('#sel_estado_asig').val('1');
    setModoEdicion(false);
    ocultarSugerencias();
  }

  function ocultarSugerencias() {
    $('#sug_usuarios').hide().empty();
    $('#sug_activos').hide().empty();
  }

  // =========================
  // Autocomplete: Usuarios (ID / Nombre / Apellido / Tarjeta)
  // =========================
  let tUser = null;

  function buscarUsuarios(q) {
    $.ajax({
      type: "GET",
      // OJO: ajusta esta ruta si tus controllers están en otra carpeta
      url: "../../controller/activos/usuarios_search.php",
      data: { q: q },
      dataType: "json",
      success: function (resp) {
        if (!resp || !resp.ok) return;

        const arr = resp.data || [];
        if (arr.length === 0) {
          $('#sug_usuarios').hide().empty();
          return;
        }

        let html = '';
        arr.forEach(u => {
          const tarjeta = u.id_tarjeta ? String(u.id_tarjeta) : '';
          const nombreCompleto = `${u.nombre ?? ''} ${u.apellido ?? ''}`.trim();

          // Formato requerido: ID - NOMBRE APELLIDO - TARJETA
          const label = `${u.id_usuario} - ${nombreCompleto} - ${tarjeta}` +
                        (u.grado ? ` (Grado ${u.grado})` : '');

          html += `<button type="button" class="list-group-item list-group-item-action sug_user"
                          data-id="${escapeHtml(u.id_usuario)}"
                          data-nombre="${escapeHtml(u.nombre ?? '')}"
                          data-apellido="${escapeHtml(u.apellido ?? '')}"
                          data-grado="${escapeHtml(u.grado ?? '')}"
                          data-tarjeta="${escapeHtml(tarjeta)}"
                          data-label="${escapeHtml(label)}">
                    ${escapeHtml(label)}
                  </button>`;
        });

        $('#sug_usuarios').html(html).show();
      },
      error: function (xhr) {
        console.log("ERROR usuarios_search:", xhr.responseText);
      }
    });
  }

  $(document).on('input', '#in_buscar_usuario', function () {
    const q = $(this).val().trim();

    // si el usuario vuelve a escribir, se "des-selecciona"
    $('#in_id_usuario').val('');
    $('#lbl_usuario_sel').text('Ningún usuario seleccionado');

    clearTimeout(tUser);
    if (q.length < 1) {
      $('#sug_usuarios').hide().empty();
      return;
    }

    tUser = setTimeout(() => buscarUsuarios(q), 200);
  });

  $(document).on('click', '.sug_user', function () {
    const id = $(this).data('id');
    const label = $(this).data('label') || '';

    $('#in_id_usuario').val(id);
    $('#in_buscar_usuario').val(label);
    $('#lbl_usuario_sel').text('Seleccionado: ' + label);

    $('#sug_usuarios').hide().empty();
  });

  // =========================
  // Autocomplete: Activos
  // =========================
  let tAct = null;

  function buscarActivos(q) {
    $.ajax({
      type: "GET",
      // OJO: ajusta esta ruta si tus controllers están en otra carpeta
      url: "../../controller/activos/activos_search.php",
      data: { q: q },
      dataType: "json",
      success: function (resp) {
        if (!resp || !resp.ok) return;

        const arr = resp.data || [];
        if (arr.length === 0) {
          $('#sug_activos').hide().empty();
          return;
        }

        let html = '';
        arr.forEach(a => {
          const label = `${a.id_activo} - CSMA: ${a.codigo_csma} - EQ: ${a.codigo_equipo}` +
                        (a.tipo ? ` - ${a.tipo}` : '') +
                        (a.marca ? ` - ${a.marca}` : '');

          html += `<button type="button" class="list-group-item list-group-item-action sug_act"
                          data-id="${escapeHtml(a.id_activo)}"
                          data-label="${escapeHtml(label)}">
                    ${escapeHtml(label)}
                  </button>`;
        });

        $('#sug_activos').html(html).show();
      },
      error: function (xhr) {
        console.log("ERROR activos_search:", xhr.responseText);
      }
    });
  }

  $(document).on('input', '#in_buscar_activo', function () {
    const q = $(this).val().trim();

    $('#in_id_activo').val('');
    $('#lbl_activo_sel').text('Ningún activo seleccionado');

    clearTimeout(tAct);
    if (q.length < 1) {
      $('#sug_activos').hide().empty();
      return;
    }

    tAct = setTimeout(() => buscarActivos(q), 200);
  });

  $(document).on('click', '.sug_act', function () {
    const id = $(this).data('id');
    const label = $(this).data('label');

    $('#in_id_activo').val(id);
    $('#in_buscar_activo').val(label);
    $('#lbl_activo_sel').text('Seleccionado: ' + label);

    $('#sug_activos').hide().empty();
  });

  // Click fuera: ocultar sugerencias
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#in_buscar_usuario, #sug_usuarios').length) {
      $('#sug_usuarios').hide();
    }
    if (!$(e.target).closest('#in_buscar_activo, #sug_activos').length) {
      $('#sug_activos').hide();
    }
  });

  // =========================
  // Listado asignaciones
  // =========================
  function cargarAsignaciones() {
    const q = $('#in_buscar_asig').val().trim();
    const estado = $('#sel_filtro_estado_asig').val();

    $('#tbody_asig').html('<tr><td colspan="5" class="text-center text-muted">Cargando...</td></tr>');

    $.ajax({
      type: "GET",
      // OJO: ajusta esta ruta si tus controllers están en otra carpeta
      url: "../../controller/activos/asignaciones_list.php",
      data: { q: q, estado: estado },
      dataType: "json",
      success: function (resp) {
        if (!resp || resp.ok !== true) {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cargar el listado");
          $('#tbody_asig').html('<tr><td colspan="5" class="text-center text-muted">Error</td></tr>');
          return;
        }

        const arr = resp.data || [];
        if (arr.length === 0) {
          $('#tbody_asig').html('<tr><td colspan="5" class="text-center text-muted">Sin resultados</td></tr>');
          return;
        }

        let html = '';
        arr.forEach(r => {
          const badge = (String(r.estado) === "1")
            ? '<span class="badge bg-success">Activo</span>'
            : '<span class="badge bg-secondary">Inactivo</span>';

          const btnTxt = (String(r.estado) === "1") ? "Desact." : "Act.";
          const btnClass = (String(r.estado) === "1") ? "btn-outline-danger" : "btn-outline-success";

          const activoLabel = `${r.id_activo} - CSMA:${r.codigo_csma} - EQ:${r.codigo_equipo}`;

          // Usuario con tarjeta (si el backend la devuelve; si no, no rompe)
          const tarjeta = r.id_tarjeta ? ` - ${r.id_tarjeta}` : '';
          const usuarioLabel = `${r.id_usuario} - ${r.nombre} ${r.apellido}${tarjeta}`;

          html += `
            <tr>
              <td>${escapeHtml(r.id_asignacion)}</td>
              <td>${escapeHtml(activoLabel)}</td>
              <td>${escapeHtml(usuarioLabel)}</td>
              <td>${badge}</td>
              <td class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-warning btn_edit_asig"
                        data-id="${escapeHtml(r.id_asignacion)}">
                  Editar
                </button>
                <button type="button" class="btn btn-sm ${btnClass} btn_toggle_asig"
                        data-id="${escapeHtml(r.id_asignacion)}"
                        data-estado="${escapeHtml(r.estado)}">
                  ${btnTxt}
                </button>
              </td>
            </tr>
          `;
        });

        $('#tbody_asig').html(html);
      },
      error: function (xhr) {
        console.log("ERROR list asignaciones:", xhr.responseText);
        alertify.error("Error del servidor al listar asignaciones");
        $('#tbody_asig').html('<tr><td colspan="5" class="text-center text-muted">Error</td></tr>');
      }
    });
  }

  // Buscar listado (debounce)
  let tList = null;
  $(document).on('input', '#in_buscar_asig', function () {
    clearTimeout(tList);
    tList = setTimeout(cargarAsignaciones, 250);
  });

  $(document).on('change', '#sel_filtro_estado_asig', cargarAsignaciones);
  $(document).on('click', '#btn_refrescar_asig', cargarAsignaciones);

  // =========================
  // Insert / Update
  // =========================
  function guardarAsignacion() {
    const id_asignacion = $('#in_id_asignacion').val().trim();
    const id_usuario = $('#in_id_usuario').val().trim();
    const id_activo = $('#in_id_activo').val().trim();
    const estado = $('#sel_estado_asig').val();

    if (!id_usuario) {
      alertify.error("Selecciona un usuario (desde la búsqueda)");
      return;
    }
    if (!id_activo) {
      alertify.error("Selecciona un activo (desde la búsqueda)");
      return;
    }

    const data = {
      id_asignacion: id_asignacion,
      id_usuario: id_usuario,
      id_activo: id_activo,
      estado: estado
    };

    const url = id_asignacion
      ? "../../controller/activos/asignaciones_update.php"
      : "../../controller/activos/asignaciones_insert.php";

    $.ajax({
      type: "POST",
      url: url,
      data: data,
      dataType: "json",
      success: function (resp) {
        if (resp && resp.ok) {
          alertify.success(id_asignacion ? "Asignación actualizada" : "Asignación creada");
          limpiarForm();
          cargarAsignaciones();
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo guardar");
        }
      },
      error: function (xhr) {
        console.log("ERROR guardar asignación:", xhr.responseText);
        alertify.error("Error del servidor al guardar");
      }
    });
  }

  $(document).on('click', '#btn_guardar_asig', guardarAsignacion);

  $(document).on('click', '#btn_cancelar_edicion', function () {
    limpiarForm();
  });

  // =========================
  // Editar: cargar una asignación al form
  // =========================
  $(document).on('click', '.btn_edit_asig', function () {
    const id = $(this).data('id');

    $.ajax({
      type: "GET",
      url: "../../controller/activos/asignaciones_get.php",
      data: { id_asignacion: id },
      dataType: "json",
      success: function (resp) {
        if (!resp || !resp.ok) {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cargar la asignación");
          return;
        }

        const a = resp.data;

        $('#in_id_asignacion').val(a.id_asignacion);
        $('#in_id_usuario').val(a.id_usuario);
        $('#in_id_activo').val(a.id_activo);
        $('#sel_estado_asig').val(String(a.estado));

        // Usuario: ID - Nombre Apellido - Tarjeta
        const tarjeta = a.id_tarjeta ? String(a.id_tarjeta) : '';
        const labelUser = `${a.id_usuario} - ${a.nombre} ${a.apellido} - ${tarjeta}` + (a.grado ? ` (Grado ${a.grado})` : '');
        $('#in_buscar_usuario').val(labelUser);
        $('#lbl_usuario_sel').text('Seleccionado: ' + labelUser);

        // Activo
        const labelAct = `${a.id_activo} - CSMA: ${a.codigo_csma} - EQ: ${a.codigo_equipo}` +
                         (a.tipo ? ` - ${a.tipo}` : '') +
                         (a.marca ? ` - ${a.marca}` : '');
        $('#in_buscar_activo').val(labelAct);
        $('#lbl_activo_sel').text('Seleccionado: ' + labelAct);

        setModoEdicion(true);
        ocultarSugerencias();
      },
      error: function (xhr) {
        console.log("ERROR get asignación:", xhr.responseText);
        alertify.error("Error del servidor al cargar");
      }
    });
  });

  // =========================
  // Toggle estado
  // =========================
  $(document).on('click', '.btn_toggle_asig', function () {
    const id = $(this).data('id');
    const estado = $(this).data('estado');

    $.ajax({
      type: "POST",
      url: "../../controller/activos/asignaciones_toggle.php",
      data: { id_asignacion: id, estado: estado },
      dataType: "json",
      success: function (resp) {
        if (resp && resp.ok) {
          cargarAsignaciones();
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cambiar estado");
        }
      },
      error: function (xhr) {
        console.log("ERROR toggle:", xhr.responseText);
        alertify.error("Error del servidor al cambiar estado");
      }
    });
  });

  // Init
  $(document).ready(function () {
    limpiarForm();
    cargarAsignaciones();
  });
</script>