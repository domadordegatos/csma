<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

$selected = (int)($_POST['selected'] ?? $_GET['selected'] ?? 0);

$sql = "SELECT id_unidad, descripcion, estado
        FROM unidades_medida_restaurante
        ORDER BY descripcion ASC";

$res = mysqli_query($conexion, $sql);
if (!$res) {
  echo '<option value="">Error cargando</option>';
  exit;
}

echo '<option value="">Seleccione...</option>';

while ($r = mysqli_fetch_assoc($res)) {
  $id = (int)$r['id_unidad'];
  $desc = htmlspecialchars(mb_strtoupper($r['descripcion'] ?? '', 'UTF-8'), ENT_QUOTES, 'UTF-8');
  $sel = ($selected === $id) ? 'selected' : '';
  $tagInactivo = ((int)$r['estado'] === 0) ? ' (INACTIVA)' : '';
  echo '<option value="'.$id.'" '.$sel.'>'.$desc.$tagInactivo.'</option>';
}
