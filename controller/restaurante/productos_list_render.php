<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$sql = "
  SELECT
    p.id_producto,
    p.descripcion,
    p.id_unidad,
    p.precio_unitario,
    p.estado,
    u.descripcion AS unidad
  FROM productos_restaurante p
  LEFT JOIN unidades_medida_restaurante u ON u.id_unidad = p.id_unidad
  ORDER BY p.id_producto DESC
";

$res = mysqli_query($conexion, $sql);
if (!$res) {
  echo '<tr><td colspan="6" class="text-center text-muted">Error cargando productos</td></tr>';
  exit;
}

$hay = false;
while ($r = mysqli_fetch_assoc($res)) {
  $hay = true;

  $id = (int)$r['id_producto'];
  $desc_raw = (string)($r['descripcion'] ?? '');
  $desc = htmlspecialchars(mb_strtoupper($desc_raw, 'UTF-8'), ENT_QUOTES, 'UTF-8');

  $unidad = htmlspecialchars(mb_strtoupper($r['unidad'] ?? '', 'UTF-8'), ENT_QUOTES, 'UTF-8');
  $id_unidad = (int)($r['id_unidad'] ?? 0);

  $precio = is_numeric($r['precio_unitario']) ? (float)$r['precio_unitario'] : 0.0;
  $precio_fmt = '$ ' . number_format($precio, 0, '.', ',');

  $estado = (int)$r['estado'];
  $badge = $estado === 1
    ? '<span class="badge bg-success">ACTIVO</span>'
    : '<span class="badge bg-danger">INACTIVO</span>';

  $rowClass = $estado === 1 ? 'table-success' : 'table-danger';
  $nuevo = $estado === 1 ? 0 : 1;
  $txtBtn = $estado === 1 ? 'Desactivar' : 'Activar';
  $btnClass = $estado === 1 ? 'btn-outline-danger' : 'btn-outline-success';

  // OJO: precio sin formato para ponerlo en input al editar
  $precio_edit = (int)$precio;

  echo '
    <tr class="'.$rowClass.'">
      <td><b>#'.$id.'</b></td>
      <td>'.$desc.'</td>
      <td class="text-center">'.$unidad.'</td>
      <td class="text-center">'.$precio_fmt.'</td>
      <td class="text-center">'.$badge.'</td>
      <td class="text-center">
        <button type="button"
          class="btn btn-sm btn-outline-primary btn_editar_producto"
          data-id="'.$id.'"
          data-desc="'.htmlspecialchars($desc_raw, ENT_QUOTES, 'UTF-8').'"
          data-unidad="'.$id_unidad.'"
          data-precio="'.$precio_edit.'">
          Editar
        </button>
        <button type="button"
          class="btn btn-sm '.$btnClass.' btn_toggle_producto"
          data-id="'.$id.'"
          data-nuevo="'.$nuevo.'">
          '.$txtBtn.'
        </button>
      </td>
    </tr>
  ';
}

if (!$hay) {
  echo '<tr><td colspan="6" class="text-center text-muted">Sin productos</td></tr>';
}
