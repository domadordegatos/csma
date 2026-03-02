<?php
ob_start(); // Captura cualquier salida accidental (echo/warnings) antes del JSON
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . "/../../model/conexion.php";
$conexion = conexion();

function respond_json($arr) {
  if (ob_get_length()) { ob_clean(); } // Limpia cualquier texto previo (ej: "root")
  echo json_encode($arr, JSON_UNESCAPED_UNICODE);
  exit;
}

function fail($msg, $extra = []) {
  respond_json(array_merge(["ok" => false, "msg" => $msg], $extra));
}

date_default_timezone_set('America/Bogota');

// =====================
// DATOS POST
// =====================
$fecha = date('Y-m-d');
$hora  = date('H:i:s');

$descripcion = trim($_POST['descripcion'] ?? '');
$descripcion = ($descripcion === '') ? null : $descripcion;

$ajustes = $_POST['ajuste'] ?? [];
if (!is_array($ajustes)) $ajustes = [];

// =====================
// SESIÓN / ADMIN ID
// =====================
if (session_status() === PHP_SESSION_NONE) session_start();

// 1) Si ya tienes id_user en sesión, úsalo
$id_admin = (int)($_SESSION['id_user'] ?? 0);

$usuario_sesion = '';
if ($id_admin <= 0) {
  // 2) Si NO hay id_user en sesión, usar nombre de usuario
  $usuario_sesion = $_SESSION['user'] ?? $_SESSION['usuario'] ?? $_SESSION['username'] ?? '';
  $usuario_sesion = trim((string)$usuario_sesion);

  if ($usuario_sesion === '') {
    fail("No se pudo identificar el usuario admin (sesión sin 'user' ni 'id_user').");
  }

  // Búsqueda robusta: ignora espacios y mayúsculas
  $stmtAdmin = mysqli_prepare(
    $conexion,
    "SELECT id_user, activacion, user
     FROM users_admins
     WHERE LOWER(TRIM(user)) = LOWER(TRIM(?))
     LIMIT 1"
  );
  if (!$stmtAdmin) {
    fail("Error preparando búsqueda de admin.", ["error" => mysqli_error($conexion)]);
  }

  mysqli_stmt_bind_param($stmtAdmin, "s", $usuario_sesion);
  mysqli_stmt_execute($stmtAdmin);
  $resAdmin = mysqli_stmt_get_result($stmtAdmin);
  $rowAdmin = $resAdmin ? mysqli_fetch_assoc($resAdmin) : null;
  mysqli_stmt_close($stmtAdmin);

  if (!$rowAdmin) {
    fail("Usuario admin no encontrado en users_admins.", [
      "usuario_sesion" => $usuario_sesion
    ]);
  }

  // Si quieres validar activo:
  if ((int)$rowAdmin['activacion'] !== 1) {
    fail("Usuario admin encontrado pero inactivo (estado != 1).", [
      "usuario_sesion" => $usuario_sesion,
      "estado" => (int)$rowAdmin['estado'],
      "user_en_bd" => $rowAdmin['user']
    ]);
  }

  $id_admin = (int)$rowAdmin['id_user'];
}

if ($id_admin <= 0) {
  fail("No se pudo resolver id_admin.", ["usuario_sesion" => $usuario_sesion]);
}

// =====================
// TRANSACCIÓN
// =====================
mysqli_begin_transaction($conexion);

try {
  // 1) Nuevo id_factura incremental (mismo para todos los productos)
  $sqlUlt = "SELECT id_factura
             FROM movimientos_inventario_restaurante
             ORDER BY id_factura DESC
             LIMIT 1
             FOR UPDATE";
  $resUlt = mysqli_query($conexion, $sqlUlt);
  if (!$resUlt) {
    throw new Exception("Error consultando id_factura: " . mysqli_error($conexion));
  }

  $rowUlt = mysqli_fetch_assoc($resUlt);
  $id_factura = $rowUlt ? ((int)$rowUlt['id_factura'] + 1) : 1;

  // 2) Traer productos activos
  $sqlProd = "SELECT id_producto, cantidad, precio_unitario
              FROM productos_restaurante
              WHERE estado = 1
              ORDER BY id_producto DESC";
  $resProd = mysqli_query($conexion, $sqlProd);
  if (!$resProd) {
    throw new Exception("Error consultando productos: " . mysqli_error($conexion));
  }

  // 3) UPDATE producto
  $stmtUpd = mysqli_prepare($conexion, "UPDATE productos_restaurante SET cantidad = ? WHERE id_producto = ?");
  if (!$stmtUpd) {
    throw new Exception("Error preparando UPDATE producto: " . mysqli_error($conexion));
  }

  // 4) INSERT movimiento
  $stmtIns = mysqli_prepare(
    $conexion,
    "INSERT INTO movimientos_inventario_restaurante
      (fecha, hora, id_factura, id_producto, id_admin, cant_anterior, cant_nueva, costo_ingreso, descripcion)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
  );
  if (!$stmtIns) {
    throw new Exception("Error preparando INSERT movimiento: " . mysqli_error($conexion));
  }

  $total = 0;

  while ($p = mysqli_fetch_assoc($resProd)) {
    $id_producto = (int)$p['id_producto'];

    $cant_anterior = is_numeric($p['cantidad']) ? (float)$p['cantidad'] : 0.0;
    $precio_unitario_mov = is_numeric($p['precio_unitario']) ? (float)$p['precio_unitario'] : 0.0;

    // Ajuste del input (si no viene, 0)
    $aj_raw = $ajustes[$id_producto] ?? 0;

    if (is_string($aj_raw)) $aj_raw = str_replace(',', '.', $aj_raw);
    if ($aj_raw === '' || $aj_raw === null) $aj_raw = 0;

    if (!is_numeric($aj_raw)) {
      throw new Exception("Ajuste inválido para producto ID {$id_producto}");
    }

    $ajuste = (float)$aj_raw;
    $cant_nueva = $cant_anterior + $ajuste;

    // UPDATE producto
    mysqli_stmt_bind_param($stmtUpd, "di", $cant_nueva, $id_producto);
    if (!mysqli_stmt_execute($stmtUpd)) {
      throw new Exception("Error actualizando producto {$id_producto}: " . mysqli_stmt_error($stmtUpd));
    }

    // INSERT movimiento (SIEMPRE)
    mysqli_stmt_bind_param(
      $stmtIns,
      "ssiiiddds",
      $fecha,
      $hora,
      $id_factura,
      $id_producto,
      $id_admin,
      $cant_anterior,
      $cant_nueva,
      $precio_unitario_mov,
      $descripcion
    );

    if (!mysqli_stmt_execute($stmtIns)) {
      throw new Exception("Error insertando movimiento producto {$id_producto}: " . mysqli_stmt_error($stmtIns));
    }

    $total++;
  }

  mysqli_stmt_close($stmtUpd);
  mysqli_stmt_close($stmtIns);

  mysqli_commit($conexion);

  respond_json([
    "ok" => true,
    "id_factura" => $id_factura,
    "total_productos" => $total
  ]);

} catch (Exception $e) {
  mysqli_rollback($conexion);
  fail("No se pudo guardar la actualización", [
    "error" => $e->getMessage(),
    "usuario_sesion" => $usuario_sesion,
    "id_admin" => $id_admin
  ]);
}
