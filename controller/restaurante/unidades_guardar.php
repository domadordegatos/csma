<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function out($ok, $msg) {
  echo json_encode(["ok"=>$ok, "msg"=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}

$id_unidad = trim($_POST['id_unidad'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');

if ($descripcion === '') out(false, "Descripción requerida");

if ($id_unidad === '') {
  // Crear
  $stmt = mysqli_prepare($conexion,
    "INSERT INTO unidades_medida_restaurante (descripcion, estado) VALUES (?, 1)"
  );
  mysqli_stmt_bind_param($stmt, "s", $descripcion);
  $ok = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  if (!$ok) out(false, "No se pudo crear la unidad");
  out(true, "Unidad creada");
} else {
  // Editar
  $id = (int)$id_unidad;
  $stmt = mysqli_prepare($conexion,
    "UPDATE unidades_medida_restaurante SET descripcion = ? WHERE id_unidad = ?"
  );
  mysqli_stmt_bind_param($stmt, "si", $descripcion, $id);
  $ok = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  if (!$ok) out(false, "No se pudo actualizar la unidad");
  out(true, "Unidad actualizada");
}
