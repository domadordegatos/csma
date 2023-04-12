<?php
	session_start();
	if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
          require_once "../../model/conexion.php";
          $conexion=conexion();
    $sql="SELECT * FROM users_admins JOIN roles ON roles.id_rol = users_admins.estado  where users_admins.user = '$user'";
    $result=mysqli_query($conexion,$sql); $ver=mysqli_fetch_row($result);
 ?>

<nav class="navbar navbar-expand-lg navbar-light sticky-top" style="background-color: #e3f2fd; padding: 0.2rem 1rem;">
  <a class="navbar-brand" style="padding-bottom: 0px;" href="../home/index.php">
    <img src="../media/recursos/logo.png" width="40px" height="40px" alt="">
    Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Control
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="../recargar/index.php">Control Cafetería</a>
          <a class="dropdown-item" href="../a_gestion/index.php">Control Almuerzos
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
        </div>
      </li>
      <?php } ?>
      <?php if($ver[5] == 3 || $ver[5] == 1){ ?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Ventas
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <a class="dropdown-item" href="../vender/index.php">Ventas Cafetería</a>
          <a class="dropdown-item" href="../a_vender/index.php">Ventas Almuerzos
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
        </div>
      </li>
      <?php } ?>

<!-- ----------------------------------------asistencias -->
      <?php if($ver[5] == 3 || $ver[5] == 1){ ?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Asistencia
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <a class="dropdown-item" href="../as_registro/index.php">Registro</a>
          <a class="dropdown-item" href="../as_consulta/index.php">Consulta</a>
        </div>
      </li>
      <?php } ?>

      <!-- ----------------------------------------asistencias -->


      <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_ventas" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Movimientos
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_ventas">
          <a class="dropdown-item" href="../revisar/index.php">Movimientos Cafetería</a>
          <a class="dropdown-item" href="../a_revisar/index.php">Movimientos Almuerzos
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
        </div>
      </li>
      <?php } ?>

      <li class="nav-item dropdown active">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_funcionalidades" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Funcionalidades
          <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown_funcionalidades">
        <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
          <a class="dropdown-item" href="../usuarios/index.php">Registro Usuarios
          <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
          <a class="dropdown-item" href="../transferencias/index.php">Transferencias
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
          <?php } ?>
          <a class="dropdown-item" href="../a_carga/index.php">Cargar Almuerzos
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
          <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
          <a class="dropdown-item" href="../a_pedidos/index.php">Registro Pedidos
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
          <a class="dropdown-item" href="../precios/index.php">Actualizacion Precios
            <span class="position-absolute text-white top-0 start-100 translate-middle badge rounded-pill bg-danger">
              BETA
            </span>
          </a>
          <?php } ?>
        </div>
      </li>
    </ul>
    <ul class="navbar-nav">
      <li class="d-flex align-items-center mr-3">
        <?php echo "Usuario: ";?><strong>
          <?php echo $user."--"; ?>
        </strong>
        <?php echo "Rol: "; ?><strong>
          <?php echo $ver[6]; ?>
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