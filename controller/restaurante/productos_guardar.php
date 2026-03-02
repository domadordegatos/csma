<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function out($ok, $msg) {
  echo json_encode(["ok"=>$ok, "msg"=>$msg], JSON_UNESCAPED_UNICODE);
  exit;
}

$id_producto = trim($_POST['id_producto'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$id_unidad = (int)($_POST['id_unidad'] ?? 0);

// cantidad SOLO en creación
$cantidad = $_POST['cantidad'] ?? 0;
if (is_string($cantidad)) $cantidad = str_replace(',', '.', $cantidad);
$cantidad = is_numeric($cantidad) ? (float)$cantidad : 0;

$precio = $_POST['precio_unitario'] ?? 0;
if (is_string($precio)) $precio = str_replace(',', '.', $precio);
$precio = is_numeric($precio) ? (float)$precio : 0;

if ($descripcion === '') out(false, "Descripción requerida");
if ($id_unidad <= 0) out(false, "Unidad requerida");

if ($id_producto === '') {
  // Crear (estado=1)
  $stmt = mysqli_prepare($conexion,
    "INSERT INTO productos_restaurante (descripcion, id_unidad, cantidad, precio_unitario, estado)
     VALUES (?, ?, ?, ?, 1)"
  );
  mysqli_stmt_bind_param($stmt, "sidd", $descripcion, $id_unidad, $cantidad, $precio);
  $ok = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  if (!$ok) out(false, "No se pudo crear el producto");
  out(true, "Producto creado");
} else {
  // Editar (NO cambia cantidad)
  $id = (int)$id_producto;
  $stmt = mysqli_prepare($conexion,
    "UPDATE productos_restaurante
     SET descripcion = ?, id_unidad = ?, precio_unitario = ?
     WHERE id_producto = ?"
  );
  mysqli_stmt_bind_param($stmt, "sidi", $descripcion, $id_unidad, $precio, $id);
  $ok = mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  if (!$ok) out(false, "No se pudo actualizar el producto");
  out(true, "Producto actualizado");
}
