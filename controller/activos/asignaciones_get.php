<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function fail($msg, $extra = []) {
  echo json_encode(array_merge(["ok"=>false, "msg"=>$msg], $extra));
  exit;
}

$id = isset($_GET['id_asignacion']) ? (int)$_GET['id_asignacion'] : 0;
if ($id <= 0) fail("ID inválido");

$sql = "SELECT
          ai.id_asignacion,
          ai.id_activo,
          a.codigo_csma,
          a.codigo_equipo,
          ta.descripcion AS tipo,
          m.descripcion  AS marca,
          ai.id_usuario,
          u.nombre,
          u.apellido,
          u.grado,
          ai.estado
        FROM asignaciones_insumos ai
        JOIN activos a ON a.id_activo = ai.id_activo
        LEFT JOIN tipo_activo ta ON ta.id_tipo_activo = a.id_tipo
        LEFT JOIN marcas m ON m.id_marcas = a.id_marca
        JOIN usuarios u ON u.id_usuario = ai.id_usuario
        WHERE ai.id_asignacion = ?
        LIMIT 1";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando get", ["error"=>mysqli_error($conexion)]);

mysqli_stmt_bind_param($stmt, "i", $id);

if (!mysqli_stmt_execute($stmt)) fail("Error ejecutando get", ["error"=>mysqli_stmt_error($stmt)]);

$res = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($res);
if (!$row) fail("No encontrado");

echo json_encode(["ok"=>true, "data"=>$row]);
