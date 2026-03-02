<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok" => false, "msg" => $msg], $extra));
  exit;
}

$id_tipo   = isset($_POST['id_tipo']) ? (int)$_POST['id_tipo'] : 0;
$id_marca  = isset($_POST['id_marca']) ? (int)$_POST['id_marca'] : 0;
$codigo_csma   = trim($_POST['codigo_csma'] ?? '');
$codigo_equipo = trim($_POST['codigo_equipo'] ?? '');
$id_zona   = isset($_POST['id_zona']) ? (int)$_POST['id_zona'] : 0;
$id_estado = isset($_POST['id_estado']) ? (int)$_POST['id_estado'] : 0;

$caracteristicas = trim($_POST['caracteristicas'] ?? '');
$detalles        = trim($_POST['detalles'] ?? '');
$fecha           = trim($_POST['fecha'] ?? '');

if ($id_tipo <= 0 || $id_marca <= 0 || $id_zona <= 0 || $id_estado <= 0) {
  fail("Faltan selects obligatorios (tipo/marca/zona/estado)");
}
if ($codigo_csma === '' || $codigo_equipo === '') {
  fail("Código CSMA y Código Equipo son obligatorios");
}

if ($fecha === '') {
  $fecha = date('Y-m-d');
}

// Permitir NULL si vienen vacíos
if ($caracteristicas === '') $caracteristicas = null;
if ($detalles === '') $detalles = null;

$sql = "INSERT INTO activos
  (id_tipo, id_marca, codigo_csma, codigo_equipo, id_zona, id_estado, caracteristicas, detalles, fecha)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
  fail("Error preparando consulta", ["error" => mysqli_error($conexion)]);
}

// i i s s i i s s s  => "iissiisss"
mysqli_stmt_bind_param(
  $stmt,
  "iissiisss",
  $id_tipo,
  $id_marca,
  $codigo_csma,
  $codigo_equipo,
  $id_zona,
  $id_estado,
  $caracteristicas,
  $detalles,
  $fecha
);

if (!mysqli_stmt_execute($stmt)) {
  fail("Error insertando", ["error" => mysqli_stmt_error($stmt)]);
}

echo json_encode([
  "ok" => true,
  "id_activo" => mysqli_insert_id($conexion)
]);
