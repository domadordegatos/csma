  
<?php
	session_start();
	unset($_SESSION['user']);
	header("location:../view/login/index.html");
 ?>