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
	<script type="text/javascript" src="js/account.js"></script>
</head>
<body>

<?php

	include("models/header.php");

?>

	<section class="content-datos">
		<fieldset class="datos">
			<legend>TUS DATOS</legend>
			<div style="width: 100%;">
				<div class="content-caja">
					<input type="text" value="NOMBRE *" class="caja-text" disabled>
					<input type="text" value="<?php echo $_SESSION['gov']['name']; ?>" class="caja" id="name">
				</div>
				<strong class="aviso" id="aviso_phone"></strong>
				<div class="content-caja">
					<input type="text" value="TELEFONO" class="caja-text" disabled>
					<input type="text" value="<?php echo $_SESSION['gov']['phone']; ?>" class="caja" id="phone" onkeyup="phone()" autocomplete="off">
				</div>
				<div class="content-caja">
					<input type="text" value="CÓDIGO POSTAL" class="caja-text" disabled>
					<input type="text" value="<?php echo $_SESSION['gov']['cp']; ?>" class="caja" id="cp" onkeyup="cp()" autocomplete="off">
				</div>
				<div class="content-caja">
					<input type="text" value="COLONIA" class="caja-text" disabled>
					<input type="text" value="<?php echo getCodePostal('colonia',$_SESSION['gov']['cp'],$conexion); ?>" class="caja" style="cursor: default;" disabled id="colonia">
				</div>
				<div class="content-caja">
					<input type="text" value="MUNICIPIO" class="caja-text" disabled>
					<input type="text" value="<?php echo getCodePostal('municipio',$_SESSION['gov']['cp'],$conexion); ?>" class="caja" style="cursor: default;" disabled id="municipio">
				</div>
				<div class="content-caja">
					<input type="text" value="ESTADO" class="caja-text" disabled>
					<input type="text" value="<?php echo getCodePostal('estado',$_SESSION['gov']['cp'],$conexion); ?>" class="caja" style="cursor: default;" disabled id="estado">
				</div>
			</div>
			<div style="width: 100%;">
				<strong class="aviso" id="aviso_birth"></strong>
				<div class="content-caja">
					<input type="text" value="FECHA DE NACIMIENTO *" class="caja-text" disabled>
					<input type="date" value="<?php echo $_SESSION['gov']['birth']; ?>" class="caja" id="birth" onchange="birth()" onkeyup="birth()">
				</div>
				<div class="content-caja">
					<input type="text" value="SEXO *" class="caja-text" disabled>
					<?php
					if($_SESSION['gov']['sexo']=='Hombre'){
						?>
						<select id="sexo" class="caja">
							<option selected value="Hombre">Hombre</option>
							<option value="Mujer">Mujer</option>
						</select>
						<?php
					}else{
						?>
						<select id='sexo' class="caja">
							<option value="Hombre">Hombre</option>
							<option selected value="Mujer">Mujer</option>
						</select>
						<?php
					}
					?>
				</div>
				<div class="content-caja" style="display: block;">
					<div style="max-width: 200px; max-height: 200px; border-radius: 10px; overflow: hidden;">
						<img src="<?php echo $_SESSION['gov']['img']; ?>" style='max-width: 200px;'>
					</div>
					<input type="file" id="img" class="caja">
					<strong id="pro"></strong>
					<progress value="0" max="100" id="carga" style="width: 100%;"></progress><br>
					<input type="button" value="GUARDAR CAMBIOS" class="btn" onclick="update()">
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
				<li class="nav-op" onclick="window.location='security.php'">
					<div class="nav-img"><img src="img/seguridad.png" width="120px" height="120px"></div>
					<div class="nav-title">SEGURIDAD</div>
				</li>
			</nav>
			<!--<div class="img-pie"><img src="img/pie.png" width="100%" height="100%"></div>-->
		</section>
	</section>

</body>
</html>