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
  <title>Inventario</title>
</head>

<?php if (in_array(16, $permisos_array)) { ?>

<body onload="iniciarPagina();">
    <h3 class="text-white my-3 ml-3">Gestión de activos Csma</h3>
  <div class="container-x w-100">
    <div class="separador1 mx-3">
      <table class="table table-sm table-striped">

        <!-- FILA SUPERIOR: Agregar / Editar / Filtros -->
        <tr>
          <td>
            <button id="btn_agregar_activo" type="button" class="btn btn-sm btn-info">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                   class="bi bi-plus-circle" viewBox="0 0 16 16">
                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
              </svg>
            </button>
          </td>

          <td><select id="sel_tipo" class="form-control form-control-sm"></select></td>
          <td><select id="sel_marca" class="form-control form-control-sm"></select></td>

          <td>
            <input id="in_codigo_csma" type="text" class="form-control form-control-sm" placeholder="codigo_csma">
          </td>
          <td>
            <input id="in_codigo_equipo" type="text" class="form-control form-control-sm" placeholder="codigo_equipo">
          </td>

          <td><select id="sel_zona" class="form-control form-control-sm"></select></td>
          <td><select id="sel_estado" class="form-control form-control-sm"></select></td>

          <td>
            <input id="in_caracteristicas" type="text" class="form-control form-control-sm" placeholder="caracteristicas">
          </td>
          <td>
            <input id="in_detalles" type="text" class="form-control form-control-sm" placeholder="detalles">
          </td>

          <td>
            <input id="in_fecha" type="date" class="form-control form-control-sm">
          </td>

          <td>
            <input type="hidden" id="in_id_activo" value="">
            <button id="btn_guardar_cambios" type="button" class="btn btn-sm btn-warning" disabled>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                   class="bi bi-pencil-square" viewBox="0 0 16 16">
                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
              </svg>
            </button>
          </td>
        </tr>

        <!-- CABECERA -->
        <tr class="table-light">
          <td>#</td>
          <td>Tipo</td>
          <td>Marca</td>
          <td>#Csma</td>
          <td>#Equipo</td>
          <td>Zona</td>
          <td>Estado</td>
          <td>Info</td>
          <td>Detalles</td>
          <td>Fecha</td>
          <td>Edit</td>
        </tr>

        <!-- CUERPO -->
        <tbody id="tabla_consulta" style="overflow-x: hidden;"></tbody>
      </table>
    </div>

    <div class="separador2"></div>
  </div>
</body>

<?php } ?>

</html>

<script>
  // =========================
  // Estado para filtrado
  // =========================
  let filtroFechaActiva = false;   // solo filtrar por fecha si el usuario la toca
  let bloqueandoEventos = false;   // evita filtrar mientras cargas datos por JS (editar fila)
  let tFiltro = null;

  function iniciarPagina() {
    cargar_catalogos();
    setFechaHoy();
    cargar_tabla(); // carga inicial (sin filtros por fecha)
  }

  // =========================
  // Fecha hoy
  // =========================
  function setFechaHoy() {
    const hoy = new Date();
    const yyyy = hoy.getFullYear();
    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
    const dd = String(hoy.getDate()).padStart(2, '0');
    $('#in_fecha').val(`${yyyy}-${mm}-${dd}`);
  }

  // =========================
  // Catálogos (selects)
  // =========================
  function llenarSelect(selector, data, placeholder) {
    const $select = $(selector);
    if ($select.length === 0) return;

    $select.empty();
    $select.append(new Option(placeholder, ""));

    (data || []).forEach(item => {
      // backend devuelve {id, nombre}
      $select.append(new Option(item.nombre, item.id));
    });
  }

  function cargar_catalogos() {
    $.ajax({
      type: "GET",
      url: "../../controller/activos/catalogos_activos.php",
      dataType: "json",
      success: function (resp) {
        if (!resp || resp.ok !== true) {
          alertify.error("No se pudieron cargar los catálogos");
          return;
        }

        llenarSelect('#sel_tipo', resp.tipos, 'Tipo...');
        llenarSelect('#sel_marca', resp.marcas, 'Marca...');
        llenarSelect('#sel_zona', resp.zonas, 'Zona...');
        llenarSelect('#sel_estado', resp.estados, 'Estado...');
      },
      error: function (xhr) {
        console.log("ERROR catalogos:", xhr.responseText);
        alertify.error("Error cargando catálogos");
      }
    });
  }

  // =========================
  // FILTROS: habilitados solo si NO hay texto manual en caracteristicas/detalles y NO editas
  // =========================
  function puedeFiltrar() {
    const editando = ($('#in_id_activo').val() || '') !== '' || $('#btn_agregar_activo').prop('disabled') === true;
    const manual = $('#in_caracteristicas').val().trim() !== '' || $('#in_detalles').val().trim() !== '';
    return !editando && !manual && !bloqueandoEventos;
  }

  function obtenerFiltros() {
    return {
      id_tipo: $('#sel_tipo').val() || '',
      id_marca: $('#sel_marca').val() || '',
      id_zona: $('#sel_zona').val() || '',
      id_estado: $('#sel_estado').val() || '',
      codigo_csma: $('#in_codigo_csma').val().trim(),
      codigo_equipo: $('#in_codigo_equipo').val().trim(),
      // importante: fecha SOLO si el usuario cambió la fecha manualmente
      fecha: filtroFechaActiva ? ($('#in_fecha').val() || '') : ''
    };
  }

  function cargar_tabla() {
    const filtros = obtenerFiltros();

    // Render filtrado (devuelve <tr>...</tr>)
    $('#tabla_consulta').load(
      "../../controller/activos/tabla_activos_render.php",
      filtros,
      function (response, status, xhr) {
        if (status === "error") {
          console.log("ERROR load tabla:", xhr.responseText);
          alertify.error("Error cargando la tabla");
        }
      }
    );
  }

  function autoFiltrar() {
    if (!puedeFiltrar()) return;
    cargar_tabla();
  }

  function debounceAutoFiltrar() {
    clearTimeout(tFiltro);
    tFiltro = setTimeout(autoFiltrar, 250);
  }

  // =========================
  // Limpieza (vuelve a modo agregar)
  // =========================
  function limpiar_form_activo() {
    $('#in_id_activo').val('');
    $('#sel_tipo').val('');
    $('#sel_marca').val('');
    $('#in_codigo_csma').val('');
    $('#in_codigo_equipo').val('');
    $('#sel_zona').val('');
    $('#sel_estado').val('');
    $('#in_caracteristicas').val('');
    $('#in_detalles').val('');

    filtroFechaActiva = false;
    setFechaHoy();

    $('#btn_guardar_cambios').prop('disabled', true);
    $('#btn_agregar_activo').prop('disabled', false);

    // al limpiar, vuelve a cargar sin filtros
    cargar_tabla();
  }

  // =========================
  // INSERT
  // =========================
  function agregar_activo() {
    const data = {
      id_tipo: $('#sel_tipo').val(),
      id_marca: $('#sel_marca').val(),
      codigo_csma: $('#in_codigo_csma').val().trim(),
      codigo_equipo: $('#in_codigo_equipo').val().trim(),
      id_zona: $('#sel_zona').val(),
      id_estado: $('#sel_estado').val(),
      caracteristicas: $('#in_caracteristicas').val().trim(),
      detalles: $('#in_detalles').val().trim(),
      fecha: $('#in_fecha').val()
    };

    if (!data.id_tipo || !data.id_marca || !data.id_zona || !data.id_estado) {
      alertify.error("Selecciona tipo, marca, zona y estado");
      return;
    }
    if (data.codigo_csma === "" || data.codigo_equipo === "") {
      alertify.error("Código CSMA y Código Equipo son obligatorios");
      return;
    }

    $.ajax({
      type: "POST",
      url: "../../controller/activos/insertar_activo.php",
      data: data,
      dataType: "json",
      success: function (resp) {
        if (resp && resp.ok) {
          alertify.success("Activo agregado correctamente");
          limpiar_form_activo(); // recarga tabla también
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo insertar");
        }
      },
      error: function (xhr) {
        console.log("ERROR insertar:", xhr.responseText);
        alertify.error("Error del servidor al insertar");
      }
    });
  }

  $(document).on('click', '#btn_agregar_activo', function () {
    agregar_activo();
  });

  // =========================
  // EDITAR: cargar fila a formulario
  // =========================
  $(document).on('click', '.btn_editar_fila', function () {
    const id = $(this).data('id');
    bloqueandoEventos = true;

    $.ajax({
      type: "GET",
      url: "../../controller/activos/get_activo.php",
      data: { id_activo: id },
      dataType: "json",
      success: function (resp) {
        if (!resp || !resp.ok) {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo cargar el activo");
          bloqueandoEventos = false;
          return;
        }

        const a = resp.data;

        $('#in_id_activo').val(a.id_activo);

        $('#sel_tipo').val(a.id_tipo);
        $('#sel_marca').val(a.id_marca);
        $('#in_codigo_csma').val(a.codigo_csma);
        $('#in_codigo_equipo').val(a.codigo_equipo);
        $('#sel_zona').val(a.id_zona);
        $('#sel_estado').val(a.id_estado);
        $('#in_caracteristicas').val(a.caracteristicas ?? '');
        $('#in_detalles').val(a.detalles ?? '');
        $('#in_fecha').val(a.fecha);

        // modo edición ON
        $('#btn_guardar_cambios').prop('disabled', false);
        $('#btn_agregar_activo').prop('disabled', true);

        // al editar, no filtrar por fecha
        filtroFechaActiva = false;

        alertify.message("Editando activo #" + a.id_activo);
        bloqueandoEventos = false;
      },
      error: function (xhr) {
        console.log("ERROR get_activo:", xhr.responseText);
        alertify.error("Error del servidor al cargar el activo");
        bloqueandoEventos = false;
      }
    });
  });

  // =========================
  // UPDATE: guardar cambios
  // =========================
  $(document).on('click', '#btn_guardar_cambios', function () {
    const id_activo = $('#in_id_activo').val();

    if (!id_activo) {
      alertify.error("No hay un activo seleccionado para editar");
      return;
    }

    const data = {
      id_activo: id_activo,
      id_tipo: $('#sel_tipo').val(),
      id_marca: $('#sel_marca').val(),
      codigo_csma: $('#in_codigo_csma').val().trim(),
      codigo_equipo: $('#in_codigo_equipo').val().trim(),
      id_zona: $('#sel_zona').val(),
      id_estado: $('#sel_estado').val(),
      caracteristicas: $('#in_caracteristicas').val().trim(),
      detalles: $('#in_detalles').val().trim(),
      fecha: $('#in_fecha').val()
    };

    if (!data.id_tipo || !data.id_marca || !data.id_zona || !data.id_estado) {
      alertify.error("Selecciona tipo, marca, zona y estado");
      return;
    }
    if (data.codigo_csma === "" || data.codigo_equipo === "") {
      alertify.error("Código CSMA y Código Equipo son obligatorios");
      return;
    }

    $.ajax({
      type: "POST",
      url: "../../controller/activos/actualizar_activo.php",
      data: data,
      dataType: "json",
      success: function (resp) {
        if (resp && resp.ok) {
          alertify.success("Cambios guardados");
          limpiar_form_activo(); // vuelve a modo agregar y recarga tabla
        } else {
          alertify.error(resp && resp.msg ? resp.msg : "No se pudo actualizar");
        }
      },
      error: function (xhr) {
        console.log("ERROR update:", xhr.responseText);
        alertify.error("Error del servidor al actualizar");
      }
    });
  });

  // =========================
  // EVENTOS DE FILTRADO
  // =========================
  // Selects: filtran al cambiar (si aplica)
  $(document).on('change', '#sel_tipo,#sel_marca,#sel_zona,#sel_estado', function () {
    debounceAutoFiltrar();
  });

  // Inputs texto para filtrar: csma / equipo (debounce)
  $(document).on('input', '#in_codigo_csma,#in_codigo_equipo', function () {
    debounceAutoFiltrar();
  });

  // Fecha: activa filtro solo si el usuario cambia manualmente
  $(document).on('change', '#in_fecha', function () {
    if (!bloqueandoEventos) filtroFechaActiva = true;
    debounceAutoFiltrar();
  });

  // Si el usuario escribe en caracteristicas/detalles, NO filtrar.
  // Si los borra (quedan vacíos), vuelve a permitir filtrado.
  $(document).on('input', '#in_caracteristicas,#in_detalles', function () {
    const manual = $('#in_caracteristicas').val().trim() !== '' || $('#in_detalles').val().trim() !== '';
    if (!manual) debounceAutoFiltrar();
  });

</script>
