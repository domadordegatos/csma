<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$id_factura = (int)($_POST['id_factura'] ?? $_GET['id_factura'] ?? 0);
if ($id_factura <= 0) {
  echo '<div class="text-muted">Factura inválida.</div>';
  exit;
}

/*
  Encabezado: fecha/hora/admin/descripcion
*/
$sqlHead = "
  SELECT
    m.id_factura,
    MIN(m.fecha) AS fecha,
    MIN(m.hora) AS hora,
    MAX(m.descripcion) AS descripcion,
    a.user AS admin_user
  FROM movimientos_inventario_restaurante m
  LEFT JOIN users_admins a ON a.id_user = m.id_admin
  WHERE m.id_factura = ?
  GROUP BY m.id_factura
  LIMIT 1
";

$stmtH = mysqli_prepare($conexion, $sqlHead);
mysqli_stmt_bind_param($stmtH, "i", $id_factura);
mysqli_stmt_execute($stmtH);
$resH = mysqli_stmt_get_result($stmtH);
$head = $resH ? mysqli_fetch_assoc($resH) : null;
mysqli_stmt_close($stmtH);

if (!$head) {
  echo '<div class="text-muted">No se encontró información de la factura.</div>';
  exit;
}

$fecha = htmlspecialchars($head['fecha'] ?? '', ENT_QUOTES, 'UTF-8');
$hora  = htmlspecialchars($head['hora'] ?? '', ENT_QUOTES, 'UTF-8');
$admin = htmlspecialchars($head['admin_user'] ?? '-', ENT_QUOTES, 'UTF-8');

$descripcion = $head['descripcion'];
$descripcion = ($descripcion === null) ? '' : trim((string)$descripcion);
$descripcion_html = $descripcion !== '' ? htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8') : '<span class="text-muted">-</span>';

/*
  Detalle: por producto
  - Trae nombre producto + unidad
  - Precio unitario "congelado" desde movimiento: costo_ingreso
  - cant_anterior, cant_nueva, delta
*/
$sqlDet = "
  SELECT
    m.id_producto,
    p.descripcion AS producto,
    u.descripcion AS unidad,
    m.costo_ingreso AS precio_unitario,
    m.cant_anterior,
    m.cant_nueva
  FROM movimientos_inventario_restaurante m
  LEFT JOIN productos_restaurante p ON p.id_producto = m.id_producto
  LEFT JOIN unidades_medida_restaurante u ON u.id_unidad = p.id_unidad
  WHERE m.id_factura = ?
  ORDER BY p.descripcion ASC
";

$stmtD = mysqli_prepare($conexion, $sqlDet);
mysqli_stmt_bind_param($stmtD, "i", $id_factura);
mysqli_stmt_execute($stmtD);
$resD = mysqli_stmt_get_result($stmtD);

$items = [];
while ($r = mysqli_fetch_assoc($resD)) $items[] = $r;
mysqli_stmt_close($stmtD);

if (count($items) === 0) {
  echo '<div class="text-muted">Sin items para esta factura.</div>';
  exit;
}

// Total compras (solo subidas)
$total_compras = 0.0;

// También calculamos costo_total_ingreso para badge superior
$costo_total = 0.0;
foreach ($items as $it) {
  $a = is_numeric($it['cant_anterior']) ? (float)$it['cant_anterior'] : 0.0;
  $n = is_numeric($it['cant_nueva']) ? (float)$it['cant_nueva'] : 0.0;
  $pu = is_numeric($it['precio_unitario']) ? (float)$it['precio_unitario'] : 0.0;
  if ($n > $a) $costo_total += ($n - $a) * $pu;
}
$costo_total_fmt = '$ ' . number_format($costo_total, 0, '.', ',');
?>

<!-- Encabezado factura -->
<div class="mb-3">
  <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:10px;">
    <h5 class="m-0">Factura #<?php echo (int)$id_factura; ?></h5>
    <span class="badge bg-success"><?php echo $costo_total_fmt; ?></span>
  </div>

  <div class="mt-2 small">
    <div><b>Fecha:</b> <?php echo $fecha; ?> &nbsp; <b>Hora:</b> <?php echo $hora; ?></div>
    <div><b>Admin:</b> <?php echo $admin; ?></div>
    <div class="mt-2"><b>Descripción:</b> <?php echo $descripcion_html; ?></div>
  </div>
</div>

<!-- Detalle -->
<div class="table-responsive">
  <table class="table table-sm table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th>Producto</th>
        <th class="text-center">Unidad</th>
        <th class="text-center">Precio</th>
        <th class="text-center">Total</th>
        <th class="text-center">Anterior</th>
        <th class="text-center">Nueva</th>
        <th class="text-center">Diferencia</th>
        <th class="text-center">Estado</th>
      </tr>
    </thead>

    <tbody>
      <?php
      foreach ($items as $it) {
        $producto = htmlspecialchars(mb_strtoupper($it['producto'] ?? '', 'UTF-8'), ENT_QUOTES, 'UTF-8');
        $unidad   = htmlspecialchars($it['unidad'] ?? '', ENT_QUOTES, 'UTF-8');

        $a  = is_numeric($it['cant_anterior']) ? (float)$it['cant_anterior'] : 0.0;
        $n  = is_numeric($it['cant_nueva']) ? (float)$it['cant_nueva'] : 0.0;
        $pu = is_numeric($it['precio_unitario']) ? (float)$it['precio_unitario'] : 0.0;

        // Formatos (sin decimales)
        $a_fmt  = number_format($a, 0, '.', ',');
        $n_fmt  = number_format($n, 0, '.', ',');
        $pu_fmt = '$ ' . number_format($pu, 0, '.', ',');

        $delta = $n - $a;

        // Diferencia texto
        if ($delta > 0) {
          $dif_txt = '+' . number_format($delta, 0, '.', ',');
          $estado_txt = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            fill="currentColor" class="bi bi-caret-up-square-fill text-success" viewBox="0 0 16 16">
              <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 9h8a.5.5 0 0 0 .374-.832l-4-4.5a.5.5 0 0 0-.748 0l-4 4.5A.5.5 0 0 0 4 11"/>
            </svg>';
        } elseif ($delta < 0) {
          $dif_txt = '-' . number_format(abs($delta), 0, '.', ',');
          $estado_txt = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            fill="currentColor" class="bi bi-caret-down-square-fill text-danger" viewBox="0 0 16 16">
              <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm4 4a.5.5 0 0 0-.374.832l4 4.5a.5.5 0 0 0 .748 0l4-4.5A.5.5 0 0 0 12 6z"/>
            </svg>';
        } else {
          $dif_txt = '0';
          $estado_txt = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
            fill="currentColor" class="bi bi-three-dots text-secondary" viewBox="0 0 16 16">
              <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3"/>
            </svg>';
        }

        // Total por producto: solo si sube
        $total_item = 0.0;
        $total_item_txt = '<span class="text-muted">-</span>';
        if ($delta > 0) {
          $total_item = $delta * $pu;
          $total_compras += $total_item;
          $total_item_txt = '$ ' . number_format($total_item, 0, '.', ',');
        }

        echo '
          <tr>
            <td>' . $producto . '</td>
            <td class="text-center">' . $unidad . '</td>
            <td class="text-center">' . $pu_fmt . '</td>
            <td class="text-center"><b>' . $total_item_txt . '</b></td>
            <td class="text-center">' . $a_fmt . '</td>
            <td class="text-center">' . $n_fmt . '</td>
            <td class="text-center"><b>' . $dif_txt . '</b></td>
            <td class="text-center">' . $estado_txt . '</td>
          </tr>
        ';
      }

      // Fila total final (total compras)
      echo '
        <tr class="table-light">
          <td colspan="3" class="text-end"><b>TOTAL COMPRAS</b></td>
          <td class="text-center"><b>$ ' . number_format($total_compras, 0, '.', ',') . '</b></td>
          <td colspan="4"></td>
        </tr>
      ';
      ?>
    </tbody>
  </table>
</div>
