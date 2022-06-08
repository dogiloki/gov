<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>INGRESAR</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/login.js"></script>
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

		<section class="content-login">
			<div class="content-datos-login">
				<div style="display: block; width: 100%;">
					<div class="login-title">INGRESAR</div>
					<div class="login-content-caja">
						<strong id="aviso_user"></strong>
						Email<input type="text" placeholder="Correo electrónico" id="user" class="login-caja" onkeyup="login2()" autocomplete="off">
					</div>
					<div class="login-content-caja">
						Contraseña<input type="password" placeholder="Contraseña" id="password" class="login-caja" onkeyup="login2()">
					</div>
					<label style="display: flex; align-items: center;">
						<input type="checkbox" style="margin: 5px; cursor: pointer;" id="save_login">Guardar mi usuario
					</label>
					<div>
						<div class="aviso" id="aviso"></div>
						<input type="button" value="INGRESAR" class="login-btn" id="login_btn" onclick="login()">
					</div>
				</div>
			</div>
		</section>

<?php
if(isset($_COOKIE['save_user'])){
?>
		<section class="content-save">
			<h3>USUARIOS GUARDADOS</h3><hr><br>
			<?php
				$ids=explode("&",$_COOKIE['save_user']);
				for($a=0; $a<sizeof($ids)-1; $a++){
					$rs_save=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$ids[$a]."'");
					$contador=0;
					while($row_save=mysqli_fetch_array($rs_save)){
						$contador++;
						if($contador==1){
							echo "<div class='users'>";
						}
						echo "<div class='user' id='user".$row_save['id']."' onmouseover=\"modal('btn_delete_save".$row_save['id']."')\" onmouseout=\"modal('btn_delete_save".$row_save['id']."')\" onclick=\"save_login_info('".$row_save['id']."')\">
							<div style='max-width: 120px; max-height: 120px; border-radius: 10px; overflow: hidden;'>
								<img src='".$row_save['img']."' class='img'>
							</div><hr>
							<strong class='name'>".$row_save['name']."</strong>
							<img src='img/delete.png' class='btn_delete_save' id='btn_delete_save".$row_save['id']."' style='display: none;' onclick=\"delete_save('".$row_save['id']."');modal('modal_save_login')\">
						</div>";
						if($contador==5){
							$contador=0;
							echo "</div>";
						}
					}
				}
			?>
		</section>


		<center><section class="modal" id="modal_save_login" onclick="modal('modal_save_login')" style="display: none;">
			<div class="content-modal-login" onclick="modal('modal_save_login')">
				<div style='max-width: 200px; max-height: 200px; border-radius: 10px; overflow: hidden;'>
					<img src="" id="img_save" class="img" style="max-width: 200px;">
				</div>
				<strong class='name' id="name_save"></strong>
				<div style="margin-right: 20px;">
					<input type="password" placeholder="Contraseña" class="login-caja" id="password_save" onkeyup="login_save2()">
				</div>
				<div style="margin-right: 10px;">
					<div class="aviso" id="aviso_save"></div>
					<input type="button" value="INGRESAR" class="login-btn" id="login_btn" onclick="login_save()">
				</div>
			</div>
		</section></center>
	
	</div>
<?php
}
?>
</body>
</html>