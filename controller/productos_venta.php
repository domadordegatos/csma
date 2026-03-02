<?php
session_start();
require_once "../model/conexion.php";

$conexion = conexion();
$action = $_GET['action'] ?? '';

// ============================
// 1) LISTAR PRODUCTOS ACTIVOS CON PREFERENCIAS
// ============================
if ($action === 'list') {
    header('Content-Type: text/html; charset=utf-8');

    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;

    // Base: solo productos activos y excluyendo operacionales
    $sqlBase = "SELECT id_producto, descripcion
                FROM productos
                WHERE estado = 1
                  AND id_producto NOT IN (14,15,16,25,26,37)";

    // Si el usuario tiene un ID, buscamos sus preferencias
    if ($id_usuario > 0) {
        $qPref = "SELECT productos FROM preferencias WHERE id_usuario = $id_usuario LIMIT 1";
        $rPref = mysqli_query($conexion, $qPref);

        if ($rPref && mysqli_num_rows($rPref) > 0) {
            $rowPref = mysqli_fetch_row($rPref);
            $lista = trim($rowPref[0] ?? '');

            if ($lista === '') {
                echo "<div class='text-white'>Este usuario no tiene productos permitidos.</div>";
                exit;
            }

            $listaEsc = mysqli_real_escape_string($conexion, $lista);

            // Filtramos los productos por las preferencias del usuario
            $sql = $sqlBase . " AND FIND_IN_SET(id_producto, '$listaEsc') > 0
                               ORDER BY id_producto ASC";
        } else {
            // Si el usuario no tiene preferencias, mostramos todos los productos
            $sql = $sqlBase . " ORDER BY id_producto ASC";
        }
    } else {
        $sql = $sqlBase . " ORDER BY id_producto ASC";
    }

    // Ejecutamos la consulta
    $res = mysqli_query($conexion, $sql);

    // Verificamos si hay resultados
    if (!$res || mysqli_num_rows($res) === 0) {
        echo "<div class='text-white'>No hay productos para mostrar.</div>";
        exit;
    }

    // Mostramos los productos
    while ($row = mysqli_fetch_assoc($res)) {
        $id = (int)$row['id_producto'];
        $desc = htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');

        echo '
            <button class="btn btn-info m-1" onclick="carrito_compras(' . $id . ')" title="' . $desc . '">
                <img src="../media/recursos/productos/' . $id . '.png"
                     width="80" height="80" alt="' . $desc . '" loading="lazy">
            </button>
        ';
    }
    exit;
}

// ===================================
// 2) AGREGAR AL CARRITO (VALIDANDO)
// ===================================
if ($action === 'add') {
    header('Content-Type: text/plain; charset=utf-8');

    $id = isset($_POST['idart']) ? (int)$_POST['idart'] : 0;
    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;

    if ($id <= 0) { echo 2; exit; }

    // Validar si el producto está permitido según las preferencias del usuario
    if ($id_usuario > 0) {
        $qPref = "SELECT productos FROM preferencias WHERE id_usuario = $id_usuario LIMIT 1";
        $rPref = mysqli_query($conexion, $qPref);

        if ($rPref && mysqli_num_rows($rPref) > 0) {
            $rowPref = mysqli_fetch_row($rPref);
            $lista = trim($rowPref[0] ?? '');

            // Si la lista está vacía o el producto no está en la lista, bloquear la compra
            if ($lista === '' || !in_array($id, array_map('intval', explode(',', $lista)), true)) {
                echo 3; // Producto no permitido según preferencias
                exit;
            }
        }
    }

    // Verificar que el producto es válido y está activo
    $sql = "SELECT id_producto, descripcion, precio
            FROM productos
            WHERE id_producto = $id
              AND estado = 1
              AND id_producto NOT IN (14,15,16,25,26,37)
            LIMIT 1";
    $res = mysqli_query($conexion, $sql);
    if (!$res || mysqli_num_rows($res) === 0) { echo 2; exit; }

    $ver = mysqli_fetch_row($res);

    $articulo = $ver[0] . "||" . $ver[1] . "||" . $ver[2] . "||";
    if (!isset($_SESSION['carrito_temp']) || !is_array($_SESSION['carrito_temp'])) {
        $_SESSION['carrito_temp'] = [];
    }
    $_SESSION['carrito_temp'][] = $articulo;

    echo 1;
    exit;
}

http_response_code(400);
echo "Bad request";