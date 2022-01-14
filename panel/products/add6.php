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
	if(!isset($_GET['id'])){
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
			IMAGENES DEL PRODUCTO</div>
			<div class="content-fomru">
				<input type="file" class="caja" id="img" multiple>
				<div class="content-caja" id="img">
					<?php
					$rs=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_GET['id']."'");
					while($row=mysqli_fetch_array($rs)){
						$img=explode("&",$row['img']);
						for($a=0; $a<sizeof($img)-1; $a++){
							?>
						<img src='../../<?php echo $img[$a]; ?>' class="img-product" onclick="delete_img('<?php echo $_GET['id']; ?>','<?php echo $img[$a]; ?>')" title="ELIMINAR">
							<?php
						}
					}
					?>
				</div>
				<strong id="num"></strong>
				<progress value="0" max="100" id="carga" style="width: 100%"></progress>
				<div class="content-btn">
					<input type="reset" class="btn" id="btn" value="Eliminar imagenes" onclick="delete_img('<?php echo $_GET['id']; ?>','')">
					<input type="submit" class="btn" id="btn" value="Subir imagenes" onclick="add_img('<?php echo $_GET['id']; ?>')">
					<input type="reset" class="btn" id="btn" value="Administrar productos" onclick="window.location='view.php'">
					<input type="reset" class="btn" id="btn" value="Ver productos" onclick="window.location='../../product.php?id=<?php echo $_GET['id']; ?>'">
				</div>
			</div>
		</section>
	</section>

</body>
</html>