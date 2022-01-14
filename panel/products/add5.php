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
	if(!isset($_GET['category']) || !isset($_GET['seccion']) || !isset($_GET['status']) || !isset($_GET['format'])){
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
			<div class="add-title"><img src="../img/regresar.png" style="cursor: pointer; margin-right: 20px;" width="70px" height="20px" onclick="window.history.back();">DATOS DEL PRODUCTO<br><br><hr></div>
			<div class="content-fomru">
				<div class="content-caja">TITULO DEL PRODUCTO
					<input type="text" placeholder="Titulo" class="caja" id="title" required>
				</div>
				<div class="content-caja">DESCRIPCIÓN DEL PRODUCTO
					<textarea placeholder="Descripción" class="caja" id="description" required></textarea>
				</div>
				<div class="content-caja">CANTIDAD DEL PRODUCTO
					<input type="number" placeholder="Cantidad" class="caja" id="quantity" required>
				</div>
				<div class="content-caja">PRECIO DEL PRODUCTO
					<input type="number" step="0.01" placeholder="Precio" class="caja" id="price" required>
				</div>
				<div class="content-caja">PRECIO DE ENVÍO DEL PRODUCTO
					<input type="number" placeholder="Envío" class="caja" id="shipping" required>
				</div>
				<input type="text" id="num_color" value="1" readonly hidden>
				<?php
					if($_GET['format']=='Digital'){
						?>
						<div class="content-caja">
							<input type="file" class="caja" id="file" required>
						</div>
						<?php
					}
					if($_GET['format']=='Digital'){
						?>
						<input type="text" placeholder="Color" class="caja" id="color1" value="" required readonly hidden>
						<?php
					}else{
						if($_GET['category']=='juegos' || $_GET['category']=='tarjetas'){
							?>
							<input type="text" placeholder="Color" class="caja" id="color1" value="" required readonly hidden>
							<?php
						}else{
							?>
							<div class="content-caja">COLOR DEL PRODUCTO
								<input type="button" value=" - " style="font-size: 18px; padding: 0px 5px 0px 5px; color: red; cursor: pointer;" onclick="color('remove')">
								<input type="button" value=" + " style="font-size: 18px; padding: 0px 5px 0px 5px; color: green; cursor: pointer;" onclick="color('add')">
								<div id='content-color'>
									<input type='color' placeholder='Color' class='caja' id='color1' required>
								</div>
							</div>
							<?php
						}
					}
				?>
				<div class="content-btn">
					<progress value="0" max="100" id="carga" style="width: 100%; margin-right: 10px;"></progress>
					<input type="button" value="Registrar producto" class="btn" id="btn" title="carga" onclick="register_product('<?php echo $_GET['category']; ?>','<?php echo $_GET['seccion']; ?>','<?php echo $_GET['status']; ?>','<?php echo $_GET['format']; ?>')">
				</div>
			</div>
		</section>
	</section>

</body>
</html>