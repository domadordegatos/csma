<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$tipo = trim($_GET['tipo'] ?? '');
$q = trim($_GET['q'] ?? '');

$map = [
  "marcas" => ["table" => "marcas", "id" => "id_marcas"],
  "uso_zona" => ["table" => "uso_zona", "id" => "id_zona"],
  "estados_insumos" => ["table" => "estados_insumos", "id" => "id_estado"],
];

if (!isset($map[$tipo])) fail("Tipo inválido");

$table = $map[$tipo]["table"];
$idcol = $map[$tipo]["id"];

$where = "";
$params = [];
$types = "";

if ($q !== "") {
  $where = " WHERE descripcion LIKE ? ";
  $types = "s";
  $params[] = "%$q%";
}

$sql = "SELECT `$idcol` AS id, descripcion, estado
        FROM `$table`
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
