<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$sql = "SELECT
          p.id_producto,
          p.descripcion AS producto,
          p.cantidad,
          p.precio_unitario,
          u.descripcion AS unidad
        FROM productos_restaurante p
        LEFT JOIN unidades_medida_restaurante u ON u.id_unidad = p.id_unidad
        WHERE p.estado = 1
        ORDER BY p.id_producto DESC";

$res = mysqli_query($conexion, $sql);

if (!$res) {
  echo '<tr><td colspan="5">Error cargando productos</td></tr>';
  exit;
}

while ($row = mysqli_fetch_assoc($res)) {
  $id = (int)$row['id_producto'];

  // Producto en MAYÚSCULA (respeta acentos)
  $producto_raw = $row['producto'] ?? '';
  $producto_raw = mb_strtoupper($producto_raw, 'UTF-8');
  $producto = htmlspecialchars($producto_raw, ENT_QUOTES, 'UTF-8');

  $unidad = htmlspecialchars($row['unidad'] ?? '', ENT_QUOTES, 'UTF-8');

  // Cantidad: muestra 10 en vez de 10.000 (pero si algún día hay 10.5, lo muestra bien)
  $cantidad_db = is_numeric($row['cantidad']) ? (float)$row['cantidad'] : 0;
  $cantidad = rtrim(rtrim(number_format($cantidad_db, 3, '.', ''), '0'), '.');
  if ($cantidad === '') $cantidad = '0';

  // Precio sin decimales (según tu caso)
  $precio_db = is_numeric($row['precio_unitario']) ? (float)$row['precio_unitario'] : 0;
  $precio = number_format($precio_db, 0, '.', ',');

  echo '
    <tr>
      <td>' . $id . '</td>
      <td>' . $producto . ' <span class="text-muted">(' . $unidad . ')</span></td>
      <td>$ ' . $precio . '</td>
      <td>' . $cantidad . '</td>
      <td style="max-width: 160px;">
        <input
          type="number"
          step="1"
          class="form-control form-control-sm"
          placeholder="Ej: +10 o -2"
          name="ajuste[' . $id . ']"
          id="ajuste_' . $id . '"
          value="0">
      </td>
    </tr>
  ';
}
