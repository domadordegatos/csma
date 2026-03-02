<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$fecha = trim($_POST['fecha'] ?? $_GET['fecha'] ?? '');

$sql = "
  SELECT
    id_factura,
    MIN(fecha) AS fecha,
    MIN(hora) AS hora,
    MAX(descripcion) AS descripcion,
    SUM(
      CASE
        WHEN cant_nueva > cant_anterior
          THEN (cant_nueva - cant_anterior) * costo_ingreso
        ELSE 0
      END
    ) AS costo_total_ingreso
  FROM movimientos_inventario_restaurante
  WHERE 1=1
";

$types = "";
$params = [];

if ($fecha !== "") {
  $sql .= " AND fecha = ? ";
  $types .= "s";
  $params[] = $fecha;
}

$sql .= "
  GROUP BY id_factura
  ORDER BY id_factura DESC
  LIMIT 20
";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
  echo '<tr><td colspan="6" class="text-center text-muted">Error preparando consulta</td></tr>';
  exit;
}

if ($types !== "") {
  $bind = [];
  $bind[] = $types;
  foreach ($params as $k => $v) $bind[] = &$params[$k];
  call_user_func_array([$stmt, 'bind_param'], $bind);
}

if (!mysqli_stmt_execute($stmt)) {
  echo '<tr><td colspan="6" class="text-center text-muted">Error ejecutando consulta</td></tr>';
  exit;
}

$res = mysqli_stmt_get_result($stmt);
$rows = [];
while ($r = mysqli_fetch_assoc($res)) $rows[] = $r;
mysqli_stmt_close($stmt);

if (count($rows) === 0) {
  echo '<tr><td colspan="6" class="text-center text-muted">Sin resultados</td></tr>';
  exit;
}

foreach ($rows as $r) {
  $id_factura = (int)$r['id_factura'];
  $f = htmlspecialchars($r['fecha'] ?? '', ENT_QUOTES, 'UTF-8');
  $h = htmlspecialchars($r['hora'] ?? '', ENT_QUOTES, 'UTF-8');

  $costo = is_numeric($r['costo_total_ingreso']) ? (float)$r['costo_total_ingreso'] : 0.0;
  $costo_fmt = '$ ' . number_format($costo, 0, '.', ',');

  $desc = $r['descripcion'];
  if ($desc === null) $desc = '';
  $desc = trim((string)$desc);

  $desc_short = $desc;
  if (mb_strlen($desc, 'UTF-8') > 10) {
    $desc_short = mb_substr($desc, 0, 20, 'UTF-8') . '...';
  }
  $desc_short = htmlspecialchars($desc_short, ENT_QUOTES, 'UTF-8');

  echo '
    <tr>
      <td><b>#' . $id_factura . '</b></td>
      <td>' . $f . '</td>
      <td>' . $h . '</td>
      <td>' . $costo_fmt . '</td>
      <td>' . ($desc_short !== '' ? $desc_short : '<span class="text-muted">-</span>') . '</td>
      <td>
        <button type="button"
                class="btn btn-sm btn-outline-primary btn_ver_factura"
                data-id="' . $id_factura . '">
          Ver
        </button>
      </td>
    </tr>
  ';
}
