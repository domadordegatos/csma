<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$tipo = trim($_POST['tipo'] ?? '');
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$estadoActual = trim($_POST['estado'] ?? '');

if ($id <= 0) fail("ID inválido");

$map = [
  "marcas" => ["table" => "marcas", "id" => "id_marcas"],
  "uso_zona" => ["table" => "uso_zona", "id" => "id_zona"],
  "estados_insumos" => ["table" => "estados_insumos", "id" => "id_estado"],
];

if (!isset($map[$tipo])) fail("Tipo inválido");

$table = $map[$tipo]["table"];
$idcol = $map[$tipo]["id"];

// Toggle 1<->0
$nuevoEstado = (string)$estadoActual === "1" ? 0 : 1;

$sql = "UPDATE `$table` SET estado = ? WHERE `$idcol` = ?";
$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando UPDATE", ["error" => mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "ii", $nuevoEstado, $id);

if (!mysqli_stmt_execute($stmt)) {
  fail("Error actualizando", ["error" => mysqli_stmt_error($stmt)]);
}

echo json_encode(["ok" => true, "estado" => $nuevoEstado]);
