<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok"=>false, "msg"=>$msg], $extra));
  exit;
}

function bindParams($stmt, $types, &$params) {
  $bind = [];
  $bind[] = $types;
  foreach ($params as $k => $v) $bind[] = &$params[$k];
  call_user_func_array([$stmt, 'bind_param'], $bind);
}

$q = trim($_GET['q'] ?? '');
$estado = trim($_GET['estado'] ?? 'all'); // all | 1 | 0

$where = " WHERE 1=1 ";
$types = "";
$params = [];

if ($estado === "1" || $estado === "0") {
  $where .= " AND ai.estado = ? ";
  $types .= "i";
  $params[] = (int)$estado;
}

if ($q !== "") {
  $where .= " AND (
      CAST(ai.id_asignacion AS CHAR) LIKE ?
      OR CAST(ai.id_usuario AS CHAR) LIKE ?
      OR u.nombre LIKE ?
      OR u.apellido LIKE ?
      OR CAST(ai.id_activo AS CHAR) LIKE ?
      OR a.codigo_csma LIKE ?
      OR a.codigo_equipo LIKE ?
    ) ";
  $types .= "sssssss";
  $like = "%$q%";
  array_push($params, $like, $like, $like, $like, $like, $like, $like);
}

$sql = "SELECT
          ai.id_asignacion,
          ai.id_activo,
          a.codigo_csma,
          a.codigo_equipo,
          ai.id_usuario,
          u.nombre,
          u.apellido,
          ai.estado
        FROM asignaciones_insumos ai
        JOIN activos a ON a.id_activo = ai.id_activo
        JOIN usuarios u ON u.id_usuario = ai.id_usuario
        $where
        ORDER BY ai.id_asignacion DESC";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando listado", ["error"=>mysqli_error($conexion)]);

if ($types !== "") bindParams($stmt, $types, $params);

if (!mysqli_stmt_execute($stmt)) fail("Error ejecutando listado", ["error"=>mysqli_stmt_error($stmt)]);

$res = mysqli_stmt_get_result($stmt);
$data = [];
while ($row = mysqli_fetch_assoc($res)) $data[] = $row;

echo json_encode(["ok"=>true, "data"=>$data]);
