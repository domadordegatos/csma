<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function out($ok, $msg) {
  echo json_encode(["ok"=>$ok, "msg"=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}

$id = (int)($_POST['id_unidad'] ?? 0);
$estado = (int)($_POST['estado'] ?? 0);

if ($id <= 0) out(false, "ID inválido");

$stmt = mysqli_prepare($conexion,
  "UPDATE unidades_medida_restaurante SET estado = ? WHERE id_unidad = ?"
);
mysqli_stmt_bind_param($stmt, "ii", $estado, $id);
$ok = mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

if (!$ok) out(false, "No se pudo cambiar el estado");
out(true, "Estado actualizado");
