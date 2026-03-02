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

$sql = "SELECT
          a.id_activo,
          a.codigo_csma,
          a.codigo_equipo,
          ta.descripcion AS tipo,
          m.descripcion  AS marca
        FROM activos a
        LEFT JOIN tipo_activo ta ON ta.id_tipo_activo = a.id_tipo
        LEFT JOIN marcas m ON m.id_marcas = a.id_marca
        WHERE CAST(a.id_activo AS CHAR) LIKE ?
           OR a.codigo_csma LIKE ?
           OR a.codigo_equipo LIKE ?
           OR ta.descripcion LIKE ?
           OR m.descripcion LIKE ?
        ORDER BY a.id_activo DESC
        LIMIT 15";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) fail("Error preparando búsqueda", ["error"=>mysqli_error($conexion)]);

$like = "%$q%";
mysqli_stmt_bind_param($stmt, "sssss", $like, $like, $like, $like, $like);

if (!mysqli_stmt_execute($stmt)) fail("Error ejecutando búsqueda", ["error"=>mysqli_stmt_error($stmt)]);

$res = mysqli_stmt_get_result($stmt);
$data = [];
while ($row = mysqli_fetch_assoc($res)) $data[] = $row;

echo json_encode(["ok"=>true, "data"=>$data]);
