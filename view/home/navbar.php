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
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
      <li class="nav-item active">
        <a class="nav-link" href="../recargar/index.php">Control</a>
      </li>
      <?php } ?>
      <?php if($ver[5] == 3 || $ver[5] == 1){ ?>
      <li class="nav-item active">
        <a class="nav-link" href="../vender/index.php">Ventas</a>
      </li>
      <?php } ?>
      <?php if($ver[5] == 2 || $ver[5] == 3){ ?>
      <li class="nav-item active">
        <a class="nav-link" href="../revisar/index.php">Movimientos</a>
      </li>
      <?php } ?>
      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Dropdown
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">Disabled</a>
      </li> -->
    </ul>
    <ul class="navbar-nav">
      <li class="d-flex align-items-center mr-3"><?php echo "Usuario: ";?><strong><?php echo $user."--"; ?></strong> <?php echo "Rol: "; ?><strong> <?php echo $ver[6]; ?></strong></li>
        <li><a href="../../controller/salir.php" class="btn btn-info">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V2zm1 13h8V2H4v13z"/>
                <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0z"/>
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