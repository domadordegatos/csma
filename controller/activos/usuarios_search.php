<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok"=>false, "msg"=>$msg], $extra));
  exit;
}

$q = trim($_GET['q'] ?? '');
if ($q === '') { echo json_encode(["ok"=>true, "data"=>[]]); exit; }

// Si el usuario escribe solo números, también buscaremos exacto por id_usuario o id_tarjeta (mejor UX)
$soloNumeros = preg_match('/^\d+$/', $q) === 1;

if ($soloNumeros) {
  $sql = "SELECT id_usuario, nombre, apellido, grado, id_tarjeta
          FROM usuarios
          WHERE id_usuario = ?
             OR id_tarjeta = ?
             OR nombre LIKE ?
             OR apellido LIKE ?
          ORDER BY nombre
          LIMIT 15";

  $stmt = mysqli_prepare($conexion, $sql);
  if (!$stmt) fail("Error preparando búsqueda", ["error"=>mysqli_error($conexion)]);

  $like = "%$q%";
  mysqli_stmt_bind_param($stmt, "iiss", $q, $q, $like, $like);

} else {
  $sql = "SELECT id_usuario, nombre, apellido, grado, id_tarjeta
          FROM usuarios
          WHERE CAST(id_usuario AS CHAR) LIKE ?
             OR CAST(id_tarjeta AS CHAR) LIKE ?
             OR nombre LIKE ?
             OR apellido LIKE ?
          ORDER BY nombre
          LIMIT 15";

  $stmt = mysqli_prepare($conexion, $sql);
  if (!$stmt) fail("Error preparando búsqueda", ["error"=>mysqli_error($conexion)]);

  $like = "%$q%";
  mysqli_stmt_bind_param($stmt, "ssss", $like, $like, $like, $like);
}

if (!mysqli_stmt_execute($stmt)) {
  fail("Error ejecutando búsqueda", ["error"=>mysqli_stmt_error($stmt)]);
}

$res = mysqli_stmt_get_result($stmt);
$data = [];
while ($row = mysqli_fetch_assoc($res)) {
  $data[] = $row;
}

echo json_encode(["ok"=>true, "data"=>$data]);
