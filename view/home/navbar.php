<?php
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    require_once "../../model/conexion.php";
    $conexion = conexion();

    // Obtener la descripción de los permisos desde la tabla 'roles' usando el id_rol
    $sql = "SELECT roles.descripcion 
            FROM users_admins 
            JOIN roles ON roles.id_rol = users_admins.estado 
            WHERE users_admins.user = '$user'";

    $result = mysqli_query($conexion, $sql);
    $permisos = mysqli_fetch_row($result);  // Permisos del rol

    // Limpiar la cadena y convertirla a un array
    $permisos_limpios = str_replace(' ', '', $permisos[0]); // Eliminar espacios si los hubiera
    $permisos_array = explode('-', $permisos_limpios); // Convertir la cadena en un array
?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #e3f2fd; padding: 0.2rem 1rem;">
  <link rel="icon" href="./logo_ico.ico" type="image/x-icon" />
  <a class="navbar-brand" style="padding-bottom: 0px;" href="../home/index.php">
    <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
    Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Control
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <?php if (in_array(1, $permisos_array)): ?>
          <a class="dropdown-item" href="../recargar/index.php">Control Cafetería</a>
           <?php endif; ?>
          <?php if (in_array(2, $permisos_array)): ?>
          <a class="dropdown-item" href="../a_gestion/index.php">Control Almuerzos</a>
           <?php endif; ?>
        </div>
      </li>
      
      
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Ventas
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <?php if (in_array(3, $permisos_array)): ?>
          <a class="dropdown-item" href="../vender/index.php">Ventas Cafetería</a>
           <?php endif; ?>
          <?php if (in_array(4, $permisos_array)): ?>
          <a class="dropdown-item" href="../a_vender/index.php">Ventas Almuerzos</a>
           <?php endif; ?>
           <?php if (in_array(23, $permisos_array)): ?>
          <a class="dropdown-item" href="../preferencias/index.php">Creación de Preferencias</a>
           <?php endif; ?>
        </div>
      </li>
      

<!-- ----------------------------------------asistencias -->
      
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Asistencia
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <?php if (in_array(5, $permisos_array)): ?>
          <a class="dropdown-item" href="../as_registro/index.php">Registro</a>
           <?php endif; ?>
          <?php if (in_array(6, $permisos_array)): ?>
          <a class="dropdown-item" href="../as_consulta/index.php">Consulta</a>
           <?php endif; ?>
        </div>
      </li>
      

      <!-- ----------------------------------------asistencias -->


      
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Movimientos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <?php if (in_array(7, $permisos_array)): ?>
          <a class="dropdown-item" href="../revisar/index.php">Movimientos Cafetería</a>
           <?php endif; ?>
          <?php if (in_array(8, $permisos_array)): ?>
          <a class="dropdown-item" href="../a_revisar/index.php">Movimientos Almuerzos</a>
           <?php endif; ?>
        </div>
      </li>
      

      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_funcionalidades" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Funcionalidades
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_funcionalidades">
          <?php if (in_array(9, $permisos_array)): ?>
          <a class="dropdown-item" href="../usuarios/index.php">Registro Usuarios</a>
           <?php endif; ?>
          <?php if (in_array(10, $permisos_array)): ?>
          <a class="dropdown-item" href="../transferencias/index.php">Transferencias</a>
           <?php endif; ?>
          <?php if (in_array(11, $permisos_array)): ?>
          <a class="dropdown-item" href="../a_carga/index.php">Cargar Almuerzos</a>
           <?php endif; ?>
          <?php if (in_array(12, $permisos_array)): ?>
          <a class="dropdown-item" href="../a_pedidos/index.php">Registro Pedidos</a>
           <?php endif; ?>
          <?php if (in_array(13, $permisos_array)): ?>
          <a class="dropdown-item" href="../precios/index.php">Actualizacion Precios</a>
           <?php endif; ?>
          <a><hr class="dropdown-divider"></a>
          <?php if (in_array(14, $permisos_array)): ?>
          <a class="dropdown-item" href="../arqueo_cafeteria/index.php">Arqueo Cafetería</a>
           <?php endif; ?>
          <?php if (in_array(15, $permisos_array)): ?>
          <a class="dropdown-item" href="../arqueo_almuerzos/index.php">Arqueo Almuerzos</a>
           <?php endif; ?>
        </div>
      </li>

    <li class="nav-item dropdown active">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Inventario Activos
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
        <?php if (in_array(16, $permisos_array)): ?>
        <a class="dropdown-item" href="../inventario/index.php">Activos csma</a>
        <?php endif; ?>
        <?php if (in_array(17, $permisos_array)): ?>
        <a class="dropdown-item" href="../gestion_item_inventario/index.php">Catalogos del inventario</a>
        <?php endif; ?>
        <?php if (in_array(18, $permisos_array)): ?>
        <a class="dropdown-item" href="../gestion_item_inventario/index2.php">Catalogo tipo de activo</a>
        <?php endif; ?>
        <?php if (in_array(19, $permisos_array)): ?>
        <a class="dropdown-item" href="../gestion_item_inventario/index3.php">Asignación Activos</a>
        <?php endif; ?>
      </div>
    </li>

        <li class="nav-item dropdown active">
      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown"
        aria-haspopup="true" aria-expanded="false">
        Inventario Restaurante
      </a>
      <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
        <?php if (in_array(20, $permisos_array)): ?>
        <a class="dropdown-item" href="../inventario_restaurante/index.php">Gestion Inventario</a>
        <?php endif; ?>
        <?php if (in_array(21, $permisos_array)): ?>
        <a class="dropdown-item" href="../inventario_restaurante/index2.php">Gestion Movimientos</a>
        <?php endif; ?>
        <?php if (in_array(22, $permisos_array)): ?>
        <a class="dropdown-item" href="../inventario_restaurante/index3.php">Productos</a>
        <?php endif; ?>
      </div>
    </li>
    </ul>
    <ul class="navbar-nav">
      <li class="d-flex align-items-center mr-3">
        <?php echo "Usuario: ";?><strong>
          <?php echo $user."--"; ?>
        </strong>
        <?php echo "Rol: "; ?><strong>
          <?php echo $permisos[0]; ?>
        </strong>
      </li>
      <li><a href="../../controller/salir.php" class="btn btn-warning">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-door-closed"
            viewBox="0 0 16 16">
            <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z" />
            <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z" />
          </svg>
          Salir</a></li>
    </ul>
  </div>
</nav>
<?php
} else {
 /*  echo "sin sesion"; */
	header("location:../login/index.html");
	}
?>