<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/products/add.css">
	<script type="text/javascript" src="../js/app.js"></script>
	<script type="text/javascript" src="../js/products.js"></script>
</head>
<body>

<?php
session_cache_limiter("must-revalidate");
include("../../conexion.php");
session_start();
if(!isset($_SESSION['gov']['id']) || $_SESSION['gov']['admin']==0){
	header("location:../index.php");
}else{
	getInfoUser($_SESSION['gov']['id'],$conexion);
	if(!isset($_GET['category']) || !isset($_GET['seccion']) || !isset($_GET['status'])){
		header("location:add.php");
	}
	?>
	<header>
		<img src="../img/logo.png" width="75px" height="50px" class="logo" onclick="window.location='../index.php'">
		<div class="title" onclick="window.location='../index.php'">GENESIS OF VIDEOGAMER</div>
		<nav class="nav-header">
			<li class="op-header" title="Menu" onclick="modal('content-menu')">
				<img src="../../<?php echo $_SESSION['gov']['img']; ?>" width="30px" height="30px" style="margin-right: 5px; border-radius: 100%;">
			</li>
			<li class="op-header" onclick="window.location='../../close.php'" title="Salir"><img src="../../img/salir.png" width="20px" height="20px" style="margin-right: 5px;">SALIR</li>
		</nav>
		<section class="content-menu" id="content-menu" style="display: none;" onclick="modal('content-menu')">
			<div class="menu" onclick="modal('content-menu')">
				<div class="content-img">
					<img src="../../<?php echo $_SESSION['gov']['img']; ?>" class="img">
				</div>
				<div class="content-name">
					<?php echo $_SESSION['gov']['fullname']; ?>
				</div>
				<div class="content-email">
					<?php echo $_SESSION['gov']['email']; ?>
				</div>
				<hr><br>
				<li onclick="window.location='../users.php'"><img src="../img/usuarios.png" width="20px" height="20px" style="margin-right: 5px;">USUARIOS</li>
				<li onclick="window.location='../products/view.php'"><img src="../img/productos.png" width="20px" height="20px" style="margin-right: 5px;">PRODUCTOS</li>
				<li onclick="window.location='../statistics.php'"><img src="../img/estadisticas.png" width="20px" height="20px" style="margin-right: 5px;">ESTADÍSTICAS</li>
				<li onclick="window.location='../../index.php'"><img src="../img/administracion.png" width="20px" height="20px" style="margin-right: 5px;">PÁGINA DE USUARIO</li>
			</div>
		</section>
	</header>
	<?php
}
?>
	
	<section class="content-add">
		<section class="add">
			<div class="add-title"><img src="../img/regresar.png" style="cursor: pointer; margin-right: 20px;" width="70px" height="20px" onclick="window.history.back();">¿EN QUE FORMATO ESTA EL PRODUCTO?<br><br><hr></div>
			<nav class="content-nav">
				<li class="nav-op" onclick="window.location='add5.php?category=<?php echo $_GET['category'] ?>&seccion=<?php echo $_GET['seccion']; ?>&status=<?php echo $_GET['status']; ?>&format=Físico'">
					<div class="nav-title">Físico</div>
				</li>
				<li class="nav-op" onclick="window.location='add5.php?category=<?php echo $_GET['category'] ?>&seccion=<?php echo $_GET['seccion']; ?>&status=<?php echo $_GET['status']; ?>&format=Digital'">
					<div class="nav-title">Digital</div>
				</li>
			</nav>
			<!--<div class="img-pie"><img src="img/pie.png" width="100%" height="100%"></div>-->
		</section>
	</section>

</body>
</html>