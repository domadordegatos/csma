<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$id_activo = isset($_GET['id_activo']) ? (int)$_GET['id_activo'] : 0;
if ($id_activo <= 0) fail("ID inválido");

$sql = "SELECT id_activo, id_tipo, id_marca, codigo_csma, codigo_equipo,
               id_zona, id_estado, caracteristicas, detalles, fecha
        FROM activos
        WHERE id_activo = ? LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando consulta", ["error" => mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "i", $id_activo);

if (!mysqli_stmt_execute($stmt)) {
  fail("Error ejecutando consulta", ["error" => mysqli_stmt_error($stmt)]);
}

$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);

if (!$row) fail("No se encontró el activo");

echo json_encode(["ok" => true, "data" => $row]);
