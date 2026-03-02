<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$q = trim($_GET['q'] ?? '');
$estado = trim($_GET['estado'] ?? 'all'); // all | 1 | 0

$where = " WHERE 1=1 ";
$types = "";
$params = [];

if ($estado === "1" || $estado === "0") {
  $where .= " AND estado = ? ";
  $types .= "i";
  $params[] = (int)$estado;
}

if ($q !== "") {
  $where .= " AND (descripcion LIKE ? OR codigo LIKE ?) ";
  $types .= "ss";
  $params[] = "%$q%";
  $params[] = "%$q%";
}

$sql = "SELECT id_tipo_activo AS id, codigo, descripcion, estado
        FROM tipo_activo
        $where
        ORDER BY descripcion";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando LIST", ["error" => mysqli_error($conexion)]);

if ($types !== "") {
  mysqli_stmt_bind_param($stmt, $types, ...$params);
}

if (!mysqli_stmt_execute($stmt)) {
  fail("Error ejecutando LIST", ["error" => mysqli_stmt_error($stmt)]);
}

$res = mysqli_stmt_get_result($stmt);
$data = [];
while ($row = mysqli_fetch_assoc($res)) {
  $data[] = $row;
}

echo json_encode(["ok" => true, "data" => $data]);
