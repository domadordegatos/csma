<?php
session_start();
require_once "../model/conexion.php";

$conexion = conexion();
$action = $_GET['action'] ?? '';

$excluir = "(14,15,16,25,26,37)";

function totalProductosVendibles($conexion, $excluir){
    $q = "SELECT COUNT(*) FROM productos
          WHERE estado = 1 AND id_producto NOT IN $excluir";
    $r = mysqli_query($conexion, $q);
    if($r){ return (int)mysqli_fetch_row($r)[0]; }
    return 0;
}

function obtenerListaPreferencias($conexion, $id_usuario){
    $id_usuario = (int)$id_usuario;
    $q = "SELECT productos FROM preferencias WHERE id_usuario = $id_usuario LIMIT 1";
    $r = mysqli_query($conexion, $q);

    if($r && mysqli_num_rows($r) > 0){
        $row = mysqli_fetch_row($r);
        return trim($row[0] ?? '');
    }
    return null; // no existe registro => sin restricciones
}

/* =========================
   RENDER GRID PRODUCTOS
   ========================= */
if ($action === 'render') {
    header('Content-Type: text/html; charset=utf-8');

    $id_usuario = isset($_GET['id_usuario']) ? (int)$_GET['id_usuario'] : 0;
    if($id_usuario <= 0){
        echo "<div class='text-white'>Busca un usuario para cargar productos.</div>";
        exit;
    }

    $total = totalProductosVendibles($conexion, $excluir);

    $lista = obtenerListaPreferencias($conexion, $id_usuario);
    $tienePref = ($lista !== null);

    $permitidos = [];
    if($tienePref && $lista !== ''){
        foreach(explode(",", $lista) as $p){
            $p = (int)trim($p);
            if($p > 0) $permitidos[$p] = true;
        }
    }

    echo '<input type="hidden" id="pref_has_record" value="'.($tienePref ? '1':'0').'">';
    echo '<input type="hidden" id="pref_total_products" value="'.$total.'">';

    $sql = "SELECT id_producto, descripcion
            FROM productos
            WHERE estado = 1
              AND id_producto NOT IN $excluir
            ORDER BY id_producto ASC";
    $res = mysqli_query($conexion, $sql);

    if(!$res || mysqli_num_rows($res) === 0){
        echo "<div class='text-white'>No hay productos para mostrar.</div>";
        exit;
    }

    while($row = mysqli_fetch_assoc($res)){
        $id = (int)$row['id_producto'];
        $desc = htmlspecialchars($row['descripcion'] ?? '', ENT_QUOTES, 'UTF-8');

        // Si NO hay registro => todo seleccionado (sin restricciones)
        // Si SÍ hay registro => solo seleccionados los de la lista
        $selected = (!$tienePref) ? true : isset($permitidos[$id]);

        echo '
            <button type="button"
                class="btn btn-info m-1 product-btn '.($selected ? 'selected':'').'"
                data-id="'.$id.'"
                onclick="toggle_pref(this)"
                title="'.$desc.'">
                <span class="checkmark">✓</span>
                <img src="../media/recursos/productos/'.$id.'.png"
                     width="80" height="80" alt="'.$desc.'" loading="lazy">
            </button>
        ';
    }
    exit;
}

/* =========================
   GUARDAR PREFERENCIAS
   ========================= */
if ($action === 'save') {
    header('Content-Type: application/json; charset=utf-8');

    $id_usuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
    $ids = $_POST['ids'] ?? [];

    if($id_usuario <= 0){
        echo json_encode(["ok"=>false,"msg"=>"Usuario inválido"]);
        exit;
    }

    // Aceptar ids como array o como string "1,2,3"
    if (is_string($ids)) {
        $ids = array_filter(array_map('trim', explode(',', $ids)));
    }
    if(!is_array($ids)) $ids = [];

    $ids = array_map('intval', $ids);
    $ids = array_values(array_unique($ids));
    sort($ids);

    // seguridad: quitar operacionales
    $bloq = [14,15,16,25,26,37];
    $ids = array_values(array_filter($ids, function($x) use ($bloq){
        return $x > 0 && !in_array($x, $bloq, true);
    }));

    $total = totalProductosVendibles($conexion, $excluir);

    // Si selecciona TODOS => "sin restricciones" => borramos registro
    if($total > 0 && count($ids) === $total){
        mysqli_query($conexion, "DELETE FROM preferencias WHERE id_usuario = $id_usuario");
        echo json_encode(["ok"=>true,"mode"=>"sin_restriccion","msg"=>"Guardado: sin restricciones (se permiten todos)."]);
        exit;
    }

    // Si no seleccionó nada, no guardar vacío
    if(count($ids) === 0){
        echo json_encode(["ok"=>false,"msg"=>"No seleccionaste ningún producto. Selecciona al menos uno, o usa 'Seleccionar todo' para dejar sin restricciones."]);
        exit;
    }

    $lista = implode(",", $ids);
    $listaEsc = mysqli_real_escape_string($conexion, $lista);

    $q = "INSERT INTO preferencias (id_usuario, productos)
          VALUES ($id_usuario, '$listaEsc')
          ON DUPLICATE KEY UPDATE productos = VALUES(productos)";

    if(mysqli_query($conexion, $q)){
        echo json_encode(["ok"=>true,"mode"=>"restriccion","msg"=>"Preferencias guardadas."]);
    } else {
        echo json_encode(["ok"=>false,"msg"=>"Error guardando: ".mysqli_error($conexion)]);
    }
    exit;
}

http_response_code(400);
echo "Bad request";