<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GOV</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/account.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/security.js"></script>
</head>
<body>

<?php

	include("models/header.php");

?>

	<section class="content-datos">
		<fieldset class="datos">
			<legend>SEGURIDAD</legend>
			<div>
				<strong class="aviso" id="aviso_email"></strong>
				<div class="content-caja">
					<input type="text" value="EMAIL" class="caja-text" disabled>
					<input type="email" value="<?php echo $_SESSION['gov']['email']; ?>" class="caja" id="email" onkeyup="email()" placeholder="Email" autocomplete="off">
				</div>
				<strong class="aviso" id="aviso_user"></strong>
				<div class="content-caja">
					<input type="text" value="USUARIO *" class="caja-text" disabled>
					<input type="" value="<?php echo $_SESSION['gov']['user']; ?>" class="caja" id="user" onkeyup="user()" placeholder="Usuario" autocomplete="off">
				</div>
				<strong class="aviso" id="aviso_password"></strong>
				<div class="content-caja">
					<input type="text" value="CONTRASEÑA NUEVA" class="caja-text" disabled>
					<input type="password" placeholder="Cambiar contraseña" class="caja" id="password" onkeyup="password()">
				</div>
				<div class="content-caja">
					<input type="text" value="CONTRASEÑA ACTUAL *" class="caja-text" disabled>
					<input type="password" placeholder="********" class="caja" id="password_actual">
				</div>
				<strong class="aviso" id="aviso"></strong>
				<div class="content-caja" style="display: block;">
					<input type="button" value="GUARDAR CAMBIOS" class="btn" style="width: 100%;" onclick="update()">
				</div>
			</div>
		</fieldset>

		<section class="categorias">
			<h3>OPCIONES</h3><hr>
			<nav class="content-nav">
				<li class="nav-op" onclick="window.location='shopping.php'">
					<div class="nav-img"><img src="img/compras.png" width="120px" height="120px"></div>
					<div class="nav-title">COMPRAS</div>
				</li>
				<li class="nav-op" onclick="window.location='cart.php'">
					<div class="nav-img"><img src="img/carrito.png" width="120px" height="120px"></div>
					<div class="nav-title">CARRITO</div>
				</li>
				<li class="nav-op" onclick="window.location='record.php'">
					<div class="nav-img"><img src="img/historial.png" width="120px" height="120px"></div>
					<div class="nav-title">HISTORIAL</div>
				</li>
				<li class="nav-op" onclick="window.location='account.php'">
					<div class="nav-img"><img src="img/hombre.png" width="120px" height="120px" style="border-radius: 100%;"></div>
					<div class="nav-title">CUENTA</div>
				</li>
			</nav>
			<!--<div class="img-pie"><img src="img/pie.png" width="100%" height="100%"></div>-->
		</section>
	</section>

</body>
</html>