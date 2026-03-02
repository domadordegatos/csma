<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fetchAll($conexion, $sql) {
  $res = mysqli_query($conexion, $sql);
  if (!$res) {
    http_response_code(500);
    echo json_encode([
      "ok" => false,
      "error" => mysqli_error($conexion),
      "sql" => $sql
    ]);
    exit;
  }

  $rows = [];
  while ($row = mysqli_fetch_assoc($res)) {
    $rows[] = $row;
  }
  return $rows;
}

/*
  IMPORTANTE:
  - Devuelve SIEMPRE: id, nombre
  - Los selects consumen esto directamente.
*/

// TIPOS: si tu tabla tipo_activo NO tiene columna estado, déjalo así.
// Si SÍ tiene estado y quieres solo activos, agrega: WHERE estado = 1
$tipos   = fetchAll($conexion, "
  SELECT id_tipo_activo AS id, descripcion AS nombre
  FROM tipo_activo
  ORDER BY descripcion
");

$marcas  = fetchAll($conexion, "
  SELECT id_marcas AS id, descripcion AS nombre
  FROM marcas
  WHERE estado = 1
  ORDER BY descripcion
");

$zonas   = fetchAll($conexion, "
  SELECT id_zona AS id, descripcion AS nombre
  FROM uso_zona
  WHERE estado = 1
  ORDER BY descripcion
");

$estados = fetchAll($conexion, "
  SELECT id_estado AS id, descripcion AS nombre
  FROM estados_insumos
  WHERE estado = 1
  ORDER BY descripcion
");

echo json_encode([
  "ok" => true,
  "tipos" => $tipos,
  "marcas" => $marcas,
  "zonas" => $zonas,
  "estados" => $estados
]);
