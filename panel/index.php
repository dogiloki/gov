<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php
	include("header.php");
?>

	<center><div class="content">

		<section class="categorias">
			<nav class="content-nav">
				<li class="nav-op" onclick="window.location='users.php'">
					<div class="nav-img"><img src="img/usuarios.png" width="220px" height="220px"></div>
					<div class="nav-title">USUARIOS</div>
				</li>
				<li class="nav-op" onclick="window.location='products/view.php'">
					<div class="nav-img"><img src="img/productos.png" width="220px" height="220px"></div>
					<div class="nav-title">PRODUCTOS</div>
				</li>
				<li class="nav-op" onclick="window.location='statistics.php'">
					<div class="nav-img"><img src="img/estadisticas.png" width="220px" height="220px"></div>
					<div class="nav-title">ESTADÍSTICAS</div>
				</li>
			</nav>
			<!--<div class="img-pie"><img src="img/pie.png" width="100%" height="100%"></div>-->
		</section>
	
	</div></center>

	

</body>
</html>