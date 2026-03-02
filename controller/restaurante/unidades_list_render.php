<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$sql = "SELECT id_unidad, descripcion, estado
        FROM unidades_medida_restaurante
        ORDER BY id_unidad DESC";

$res = mysqli_query($conexion, $sql);
if (!$res) {
  echo '<tr><td colspan="4" class="text-center text-muted">Error cargando unidades</td></tr>';
  exit;
}

$hay = false;
while ($r = mysqli_fetch_assoc($res)) {
  $hay = true;
  $id = (int)$r['id_unidad'];
  $desc = htmlspecialchars(mb_strtoupper($r['descripcion'] ?? '', 'UTF-8'), ENT_QUOTES, 'UTF-8');
  $estado = (int)$r['estado'];

  $badge = $estado === 1
    ? '<span class="badge bg-success">ACTIVA</span>'
    : '<span class="badge bg-danger">INACTIVA</span>';

  $rowClass = $estado === 1 ? 'table-success' : 'table-danger';
  $nuevo = $estado === 1 ? 0 : 1;
  $txtBtn = $estado === 1 ? 'Desactivar' : 'Activar';
  $btnClass = $estado === 1 ? 'btn-outline-danger' : 'btn-outline-success';

  echo '
    <tr class="'.$rowClass.'">
      <td><b>#'.$id.'</b></td>
      <td>'.$desc.'</td>
      <td class="text-center">'.$badge.'</td>
      <td class="text-center">
        <button type="button"
          class="btn btn-sm btn-outline-primary btn_editar_unidad"
          data-id="'.$id.'"
          data-desc="'.htmlspecialchars($r['descripcion'] ?? '', ENT_QUOTES, 'UTF-8').'">
          Editar
        </button>
        <button type="button"
          class="btn btn-sm '.$btnClass.' btn_toggle_unidad"
          data-id="'.$id.'"
          data-nuevo="'.$nuevo.'">
          '.$txtBtn.'
        </button>
      </td>
    </tr>
  ';
}

if (!$hay) {
  echo '<tr><td colspan="4" class="text-center text-muted">Sin unidades</td></tr>';
}
