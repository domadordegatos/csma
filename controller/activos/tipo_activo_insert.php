<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$codigo = trim($_POST['codigo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($codigo === '') fail("El código es obligatorio");
if (!preg_match('/^\d+$/', $codigo)) fail("El código debe ser numérico");
if ($descripcion === '') fail("La descripción es obligatoria");

// Si quieres evitar códigos repetidos, activa esta validación opcional:
$sqlCheck = "SELECT 1 FROM tipo_activo WHERE codigo = ? LIMIT 1";
$stmtCheck = mysqli_prepare($conexion, $sqlCheck);
if ($stmtCheck) {
  mysqli_stmt_bind_param($stmtCheck, "s", $codigo);
  mysqli_stmt_execute($stmtCheck);
  $res = mysqli_stmt_get_result($stmtCheck);
  if ($res && mysqli_fetch_row($res)) {
    fail("Ya existe un tipo_activo con ese código");
  }
}

$sql = "INSERT INTO tipo_activo (codigo, descripcion, estado) VALUES (?, ?, 1)";
$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando INSERT", ["error" => mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "ss", $codigo, $descripcion);

if (!mysqli_stmt_execute($stmt)) {
  fail("Error insertando", ["error" => mysqli_stmt_error($stmt)]);
}

echo json_encode(["ok" => true, "id" => mysqli_insert_id($conexion)]);
