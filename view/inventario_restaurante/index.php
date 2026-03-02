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
  <title>Inventario Restaurante</title>
</head>

<?php if (in_array(20, $permisos_array)) { ?>

<body onload="iniciarPagina();">

  <div class="container-x w-100">
    <div class="separador1 mx-5">

      <!-- FORM: manda descripcion + ajustes -->
      <form id="form_carga_dia">

        <!-- Barra superior compacta: título + descripción + botón -->
        <div class="d-flex align-items-center justify-content-between flex-wrap my-3">

          <!-- Título -->
          <h3 class="text-white m-0 me-3">Inventario - Restaurante</h3>

          <!-- Descripción + botón en una sola línea -->
          <div class="d-flex align-items-center flex-grow-1 gap-2">

            <textarea
              id="descripcion"
              name="descripcion"
              class="form-control form-control-sm mx-4"
              rows="1"
              style="resize:none; height: 32px;"
              placeholder="Descripción / eventualidades del día (opcional)"></textarea>

            <button id="btn_cargar_dia" type="submit" class="btn btn-success btn-sm flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
                <path fill-rule="evenodd"
                  d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V11.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z"/>
                <path
                  d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.394 4.579A3.5 3.5 0 0 1 13.5 13H11a.5.5 0 0 1 0-1h2.5a2.5 2.5 0 0 0 .1-4.998.5.5 0 0 1-.43-.4C12.776 4.3 10.708 3 8 3a4.53 4.53 0 0 0-2.94 1.08.5.5 0 0 1-.653-.037z"/>
                <path
                  d="M3.5 13a3.5 3.5 0 0 1-.12-6.998.5.5 0 0 1 .4.43A3.5 3.5 0 0 1 7 9.5a.5.5 0 0 1-1 0A2.5 2.5 0 0 0 3.5 7a2.5 2.5 0 0 0 0 5H5a.5.5 0 0 1 0 1H3.5z"/>
              </svg>
              &nbsp;Guardar actualización
            </button>

          </div>
        </div>

        <!-- Tabla -->
        <table class="table table-sm table-striped">
          <tr class="table-light">
            <td>ID</td>
            <td>Producto (Unidad)</td>
            <td>Precio Unitario</td>
            <td>Cantidad</td>
            <td>Ajuste (+ / -)</td>
          </tr>
          <tbody id="tabla_productos"></tbody>
        </table>

      </form>

    </div>
  </div>

</body>

<?php } ?>
</html>

<script>
  function iniciarPagina() {
    cargar_tabla_productos();
    eventos();
  }

  function cargar_tabla_productos() {
    $("#tabla_productos").load(
      "../../controller/restaurante/tabla_productos_render.php"
    );
  }

  function eventos() {
    $("#form_carga_dia").on("submit", function (e) {
      e.preventDefault();

      // Por seguridad: si algún input viniera vacío, lo dejamos en 0
      $('input[name^="ajuste["]').each(function () {
        if ($(this).val() === "" || $(this).val() === null) $(this).val("0");
      });

      $("#btn_cargar_dia").prop("disabled", true);

      $.ajax({
        type: "POST",
        url: "../../controller/restaurante/guardar_actualizacion_dia.php",
        data: $("#form_carga_dia").serialize(),
        dataType: "json",
        success: function (resp) {
          if (!resp || resp.ok !== true) {
            alertify.error((resp && resp.msg) ? resp.msg : "No se pudo guardar la actualización");
            $("#btn_cargar_dia").prop("disabled", false);
            return;
          }

          alertify.success("Actualización guardada. Factura #" + resp.id_factura);

          // Limpieza visual
          $("#descripcion").val("");

          // Recarga tabla (cantidades ya actualizadas)
          cargar_tabla_productos();

          $("#btn_cargar_dia").prop("disabled", false);
        },
        error: function (xhr, status, error) {
          console.log("AJAX status:", status);
          console.log("AJAX error:", error);
          console.log("HTTP:", xhr.status);
          console.log("ResponseText:", xhr.responseText);
          alertify.error("Error guardando la actualización");
          $("#btn_cargar_dia").prop("disabled", false);
        }
      });
    });
  }
</script>
