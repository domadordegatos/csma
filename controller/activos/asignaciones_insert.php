<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok"=>false, "msg"=>$msg], $extra));
  exit;
}

$id_activo  = isset($_POST['id_activo']) ? (int)$_POST['id_activo'] : 0;
$id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
$estado     = isset($_POST['estado']) ? (int)$_POST['estado'] : 1;

if ($id_activo <= 0) fail("id_activo inválido");
if ($id_usuario <= 0) fail("id_usuario inválido");
if ($estado !== 0 && $estado !== 1) $estado = 1;

$sql = "INSERT INTO asignaciones_insumos (id_activo, id_usuario, estado)
        VALUES (?, ?, ?)";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando insert", ["error"=>mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "iii", $id_activo, $id_usuario, $estado);

if (!mysqli_stmt_execute($stmt)) fail("Error insertando", ["error"=>mysqli_stmt_error($stmt)]);

echo json_encode(["ok"=>true, "id"=>mysqli_insert_id($conexion)]);
