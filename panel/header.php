<?php
session_cache_limiter("must-revalidate");
include("../conexion.php");
session_start();
if(!isset($_SESSION['gov']['id']) && $_SESSION['gov']['admin']==0){
	header("location:../index.php");
}else{
	getInfoUser($_SESSION['gov']['id'],$conexion);
	?>
	<header>
		<img src="img/logo.png" width="75px" height="50px" class="logo" onclick="window.location='index.php'">
		<div class="title" onclick="window.location='index.php'">GENESIS OF VIDEOGAMER</div>
		<nav class="nav-header">
			<li class="op-header" title="Menu" onclick="modal('content-menu')">
				<img src="../<?php echo $_SESSION['gov']['img']; ?>" width="30px" height="30px" style="margin-right: 5px; border-radius: 100%;">
			</li>
			<li class="op-header" onclick="window.location='../close.php'" title="Salir"><img src="../img/salir.png" width="20px" height="20px" style="margin-right: 5px;">SALIR</li>
		</nav>
		<section class="content-menu" id="content-menu" style="display: none;" onclick="modal('content-menu')">
			<div class="menu" onclick="modal('content-menu')">
				<div class="content-img">
					<img src="../<?php echo $_SESSION['gov']['img']; ?>" class="img">
				</div>
				<div class="content-name">
					<?php echo $_SESSION['gov']['fullname']; ?>
				</div>
				<div class="content-email">
					<?php echo $_SESSION['gov']['email']; ?>
				</div>
				<hr><br>
				<li onclick="window.location='users.php'"><img src="img/usuarios.png" width="20px" height="20px" style="margin-right: 5px;">USUARIOS</li>
				<li onclick="window.location='products/view.php'"><img src="img/productos.png" width="20px" height="20px" style="margin-right: 5px;">PRODUCTOS</li>
				<li onclick="window.location='statistics.php'"><img src="img/estadisticas.png" width="20px" height="20px" style="margin-right: 5px;">ESTADÍSTICAS</li>
				<li onclick="window.location='../index.php'"><img src="img/administracion.png" width="20px" height="20px" style="margin-right: 5px;">PÁGINA DE USUARIO</li>
			</div>
		</section>
	</header>
	<?php
}
?>