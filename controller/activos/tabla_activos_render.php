<?php
require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

/**
 * Bind dinámico para prepared statements (mysqli)
 */
function bindParams($stmt, $types, &$params) {
  $bind = [];
  $bind[] = $types;
  foreach ($params as $k => $v) {
    $bind[] = &$params[$k];
  }
  call_user_func_array([$stmt, 'bind_param'], $bind);
}

$id_tipo   = isset($_POST['id_tipo']) ? (int)$_POST['id_tipo'] : 0;
$id_marca  = isset($_POST['id_marca']) ? (int)$_POST['id_marca'] : 0;
$id_zona   = isset($_POST['id_zona']) ? (int)$_POST['id_zona'] : 0;
$id_estado = isset($_POST['id_estado']) ? (int)$_POST['id_estado'] : 0;

$codigo_csma   = trim($_POST['codigo_csma'] ?? '');
$codigo_equipo = trim($_POST['codigo_equipo'] ?? '');
$fecha         = trim($_POST['fecha'] ?? ''); // si viene vacío, no filtra por fecha

$where = " WHERE 1=1 ";
$types = "";
$params = [];

if ($id_tipo > 0) { $where .= " AND activos.id_tipo = ? ";   $types .= "i"; $params[] = $id_tipo; }
if ($id_marca > 0){ $where .= " AND activos.id_marca = ? ";  $types .= "i"; $params[] = $id_marca; }
if ($id_zona > 0) { $where .= " AND activos.id_zona = ? ";   $types .= "i"; $params[] = $id_zona; }
if ($id_estado > 0){$where .= " AND activos.id_estado = ? "; $types .= "i"; $params[] = $id_estado; }

if ($codigo_csma !== "") {
  $where .= " AND activos.codigo_csma LIKE ? ";
  $types .= "s";
  $params[] = "%{$codigo_csma}%";
}

if ($codigo_equipo !== "") {
  $where .= " AND activos.codigo_equipo LIKE ? ";
  $types .= "s";
  $params[] = "%{$codigo_equipo}%";
}

if ($fecha !== "") {
  $where .= " AND activos.fecha = ? ";
  $types .= "s";
  $params[] = $fecha;
}

$sql = "SELECT
          activos.id_activo,
          tipo_activo.descripcion  AS tipo,
          marcas.descripcion       AS marca,
          activos.codigo_csma,
          activos.codigo_equipo,
          uso_zona.descripcion     AS zona,
          estados_insumos.descripcion AS estado,
          activos.caracteristicas,
          activos.detalles,
          activos.fecha
        FROM activos
        JOIN tipo_activo     ON tipo_activo.id_tipo_activo = activos.id_tipo
        JOIN marcas          ON marcas.id_marcas = activos.id_marca
        JOIN uso_zona        ON uso_zona.id_zona = activos.id_zona
        JOIN estados_insumos ON estados_insumos.id_estado = activos.id_estado
        $where
        ORDER BY activos.id_activo DESC";

$stmt = mysqli_prepare($conexion, $sql);
if (!$stmt) {
  echo '<tr><td colspan="11">Error preparando consulta</td></tr>';
  exit;
}

if ($types !== "") {
  bindParams($stmt, $types, $params);
}

if (!mysqli_stmt_execute($stmt)) {
  echo '<tr><td colspan="11">Error ejecutando consulta</td></tr>';
  exit;
}

$res = mysqli_stmt_get_result($stmt);
$rows = 0;

while ($row = mysqli_fetch_assoc($res)) {
  $rows++;

  $id = htmlspecialchars($row['id_activo']);
  $tipo = htmlspecialchars($row['tipo']);
  $marca = htmlspecialchars($row['marca']);
  $csma = htmlspecialchars($row['codigo_csma']);
  $equipo = htmlspecialchars($row['codigo_equipo']);
  $zona = htmlspecialchars($row['zona']);
  $estado = htmlspecialchars($row['estado']);
  $car = htmlspecialchars($row['caracteristicas'] ?? '');
  $det = htmlspecialchars($row['detalles'] ?? '');
  $fec = htmlspecialchars($row['fecha']);

  echo "<tr class='table-sm'>
          <td>{$id}</td>
          <td>{$tipo}</td>
          <td>{$marca}</td>
          <td>{$csma}</td>
          <td>{$equipo}</td>
          <td>{$zona}</td>
          <td>{$estado}</td>
          <td>{$car}</td>
          <td>{$det}</td>
          <td>{$fec}</td>
          <td>
            <button type='button' class='btn btn-sm btn-warning btn_editar_fila' data-id='{$id}'>
              <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor'
                class='bi bi-pencil-square' viewBox='0 0 16 16'>
                <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
              </svg>
            </button>
          </td>
        </tr>";
}

if ($rows === 0) {
  echo "<tr><td colspan='11' class='text-center'>Sin resultados</td></tr>";
}
