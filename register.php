<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>REGISTRARSE</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/register.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/register.js"></script>
</head>
<body>

<?php
if(!isset($_SESSION['gov']['id'])){
	include("models/header.php");
}else{
	return header("location:index.php");
}
?>

	<div class="content">
	
		<section class="content-register">
			<div class="register-title">REGISTRARSE</div>
			<div class="content-datos-register">
				<div style="display: block; width: 100%;">
					<div class="register-content-caja">
						Nombre<input type="text" placeholder="Nombre" id="name" class="register-caja" autocomplete="off">
					</div>
					<div class="register-content-caja">
						Fecha de nacimiento<input type="date" placeholder="Fecha de nacimiento" id="birth" class="register-caja" onchange="birth()">
					</div>
					<div class="register-content-caja">
						<strong id="aviso_user"></strong>
						Email<input type="email" placeholder="Correo electrónico" id="user_register" class="register-caja" onkeyup="email();" autocomplete="off">
					</div>
					<!--<div class="register-content-caja">
						Apellidos(s)<input type="text" placeholder="Apellidos(s)" id="surname" class="register-caja" autocomplete="off">
					</div>
					<div class="register-content-caja">
						Código postal<input type="text" placeholder="Código postal" id="cp" class="register-caja" onkeyup="cp()" autocomplete="off">
					</div>
					<div class="register-content-caja">
						Colonia<input type="text" placeholder="Colonia" id="colonia" class="register-caja" readonly disabled="false">
					</div>
					<div class="register-content-caja">
						Municipio<input type="text" placeholder="Municipio" id="municipio" class="register-caja" readonly disabled="false">
					</div>
					<div class="register-content-caja">
						Estado<input type="text" placeholder="Estado" id="estado" class="register-caja" readonly disabled="false">
					</div>-->
				</div>
				<div style="display: block; width: 100%;">
					<!--<div class="register-content-caja">
						<strong id="aviso_phone"></strong>
						Telefono<input type="text" placeholder="Telefono" id="phone" class="register-caja" onkeyup="phone()" autocomplete="off">
					</div>-->
					<div class="register-content-caja">
						<strong id="aviso_password"></strong>
						Contraseña<input type="password" placeholder="Contraseña" id="password" class="register-caja" onkeyup="password()" onchange="password()">
					</div>
					<div class="register-content-caja">
						<strong id="aviso_password_veri"></strong>
						Repite tu contraseña<input type="password" placeholder="Repite tu contraseña" id="password_veri" class="register-caja" onkeyup="password_veri()" onchange="password_veri()">
					</div>
					<div class="register-content-caja">
						<input type="text" id="sexo" value="" readonly hidden>
						<img src="img/hombre.png" class="register-sexo" title="Hombre" id="img-Hombre" onclick="cambio_sexo('Hombre')">
						<img src="img/mujer.png" class="register-sexo" title="Mujer" id="img-Mujer" onclick="cambio_sexo('Mujer')">
					</div>
					<div>
						<div class="aviso" id="aviso"></div>
						<input type="button" value="REGISTRARSE" class="register-btn" id="register-btn" onclick="register()">
					</div>
				</div>
			</div>
		</section>

		<section class="modal-registrado" id="modal_registrado" style="display: none;">
			<div class="content-modal-register">
				<div class="aviso-modal-register">Se envio un código a tú correo<br>para verificar que puedes acceder</div>
				<input type="text" id="id_user" required readonly hidden>
				<input type="text" id="code" class="register-caja" placeholder="CÓDIGO" style="width: auto; margin-bottom: 10px;" required>
				<strong id="aviso_active"></strong>
				<input type="button" value="ACEPTAR" class="btn-modal-register" onclick="active()">
			</div>
		</section>
	
	</div>

</body>
</html>