<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$tipo = trim($_POST['tipo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($descripcion === '') fail("La descripción es obligatoria");

$map = [
  "marcas" => ["table" => "marcas", "id" => "id_marcas"],
  "uso_zona" => ["table" => "uso_zona", "id" => "id_zona"],
  "estados_insumos" => ["table" => "estados_insumos", "id" => "id_estado"],
];

if (!isset($map[$tipo])) fail("Tipo inválido");

$table = $map[$tipo]["table"];

// Insert seguro (estado por defecto = 1)
$sql = "INSERT INTO `$table` (descripcion, estado) VALUES (?, 1)";
$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando INSERT", ["error" => mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "s", $descripcion);

if (!mysqli_stmt_execute($stmt)) {
  fail("Error insertando", ["error" => mysqli_stmt_error($stmt)]);
}

echo json_encode(["ok" => true, "id" => mysqli_insert_id($conexion)]);
