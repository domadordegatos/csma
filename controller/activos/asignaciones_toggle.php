<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok"=>false, "msg"=>$msg], $extra));
  exit;
}

$id = isset($_POST['id_asignacion']) ? (int)$_POST['id_asignacion'] : 0;
$estadoActual = trim($_POST['estado'] ?? '');

if ($id <= 0) fail("ID inválido");
$nuevoEstado = ((string)$estadoActual === "1") ? 0 : 1;

$sql = "UPDATE asignaciones_insumos SET estado = ? WHERE id_asignacion = ?";
$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando toggle", ["error"=>mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "ii", $nuevoEstado, $id);

if (!mysqli_stmt_execute($stmt)) fail("Error en toggle", ["error"=>mysqli_stmt_error($stmt)]);

echo json_encode(["ok"=>true, "estado"=>$nuevoEstado]);
