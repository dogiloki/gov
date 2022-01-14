<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="../css/header.css">
	<link rel="stylesheet" type="text/css" href="../css/products/view.css">
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

	<?php
		if(isset($_POST['search'])){
			$search=$_POST['search'];
			$s=explode(" ",$_POST['search']);
			$like="WHERE ";
			for($a=0; $a<sizeof($s); $a++){
				$like.="title LIKE '%".$s[$a]."%' OR category LIKE '%".$s[$a]."%' OR seccion LIKE '%".$s[$a]."%' OR price LIKE '%".$s[$a]."%' OR description LIKE '%".$s[$a]."%' OR format LIKE '%".$s[$a]."%' OR status LIKE '%".$s[$a]."%'";
			}
		}else{
			$search="";
			$like="";
		}
		$num_row=mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM products ".$like));
		if(isset($_GET['page'])){
			$page=$_GET['page'];
			if($page>ceil($num_row/20)){
				$page=ceil($num_row/20);
			}
		}else{
			$page=1;
		}
	?>
	<form action="view.php" method="post">
		<?php
		if(isset($_POST['search'])){
			echo "<input type='search' name='search' placeholder='Buscar...' value='".$_POST['search']."' class='caja'>";
		}else{
			echo "<input type='search' name='search' placeholder='Buscar...' class='caja'>";
		}
		?>
	</form>
	<fieldset class="content-products">
		<legend class="btn-add" onclick="window.location='add.php'">AGREGAR</legend>
			<?php
				if($page==1){
					$rs=mysqli_query($conexion,"SELECT * FROM products ".$like." ORDER BY date_register DESC LIMIT 0,20");
				}else{
					$rs=mysqli_query($conexion,"SELECT * FROM products ".$like." ORDER BY date_register DESC LIMIT ".(($page-1)*20).",20");
				}
				$contador=0;
				while($row=mysqli_fetch_array($rs)){
					$img=explode("&",$row['img']);
					$contador++;
					$estrellas="";
					for($a=1; $a<=5; $a++){
						if($a<=$row['stars']){
							$estrellas.="<img src='../../img/estrella.png' width='20px' height='20px'>";
						}else{
							$estrellas.="<img src='../../img/estrella_vacia.png' width='20px' height='20px'>";
						}
					}
					if($contador==1){
						echo "<section class='products'>";
					}
					?>
					<li class='product' id='<?php echo $row['id']; ?>' onclick="modal2('<?php echo $row['id']; ?>')">
					<?php
						echo "<div class='content-img-products'>
							<img src='../../".$img[0]."' width='100%' height='100%' loaging='lazy' id='img-view".$row['id']."'>
						</div>
						<div class='content-title' id='title-view".$row['id']."'>".utf8_encode($row['title'])."</div>
						<div class='content-sold' id='sold-view".$row['id']."'>Vendidos: ".$row['sold']."</div>
						<div class='content-views' id='views-view".$row['id']."'>Visitas: ".$row['views']."</div>
						<div id='stars-view'>".$estrellas."</div>
					</li>";
					if($contador==5){
						$contador=0;
						echo "</section>";
					}
					?>
					<section class="modal" id='modal-option<?php echo $row['id']; ?>' style="display: none;" onclick="modal2('<?php echo $row['id']; ?>')">
						<div class="content-option" id="content-option">
							<li class="option" onclick="getUpdate('opinion','<?php echo $row['id']; ?>',true)">VER OPINIONES</li>
							<li class="option" onclick="getUpdate('question','<?php echo $row['id']; ?>',true)">VER PREGUNTAS</li>
							<hr>
							<li class="option" onclick="getUpdate('info','<?php echo $row['id']; ?>',true)">EDITAR INFORMACIÓN</li>
							<li class="option" onclick="getUpdate('img','<?php echo $row['id']; ?>',true)">EDITAR IMAGENES</li>
							<?php
								if($row['color']!='&'){
									?>
									<li class="option" onclick="getUpdate('color','<?php echo $row['id']; ?>',true)">EDITAR COLORES</li>
									<?php
								}
								if($row['format']=='Digital'){
									?>
									<li class="option" onclick="getUpdate('file','<?php echo $row['id']; ?>',true)">EDITAR ARCHIVO</li>
									<?php
								}
							?>
							<hr>
							<li class="option" onclick="window.location='../controllers/products.php?v=clonar&id=<?php echo $row['id']; ?>'">CLONAR PRODUCTO</li>
							<li class="option" onclick="delete_product('<?php echo $row['id']; ?>')">ELIMINAR</li>
						</div>
					</section>
					<?php
				}
				echo "</section>";
				echo "<section class='content-page'>";
				for($a=1; $a<=ceil($num_row/20); $a++){
					if($num_row>=20){
						if($page==$a){
							?>
							<form action="view.php?page=<?php echo $a; ?>" method="post">
								<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
								<input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px; background: #353535;" class="page" id="page<?php echo $a; ?>">
							</form>
							<?php
						}else{
							?>
							<form action="view.php?page=<?php echo $a; ?>" method="post">
								<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
								<input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px;" class="page" id="page<?php echo $a; ?>">
							</form>
							<?php
						}
					}
				}
				echo "</section>";
			?>
	</fieldset>

	<section class="modal" id='modal-info' style="display: none;" onclick="modal('modal-info')">
		<div class="content-modal" onclick="modal('modal-info')">
			<div class="content-caja">
				TITULO DEL PRODUCTO
				<input type="text" placeholder="Titulo del producto" id="title" class="caja">
			</div>
			<div class="content-caja">
				DESCRIPCIÓN
				<textarea type="text" placeholder="Titulo del producto" id="description" class="caja" style="max-width: 100%; min-width: 100%"></textarea>
			</div>
			<div class="content-caja">
				CANTIDAD DE PRODUCTOS
				<input type="number" placeholder="Titulo del producto" id="quantity" class="caja">
			</div>
			<div class="content-caja">
				PRECIO DEL PRODUCTO
				<input type="number" placeholder="Titulo del producto" id="price" class="caja">
			</div>
			<div class="content-caja">
				PRECIO DE ENVIO
				<input type="number" placeholder="Titulo del producto" id="shipping" class="caja">
			</div>
			<div class="content-caja">
				ESTADO DEL PRODUCTO
				<input type="text" id="status" value="" readonly hidden>
				<input type="button" value="NUEVO" id="Nuevo" class="btn-status" onclick="cambiar_status('Nuevo')">
				<input type="button" value="USADO" id="Usado" class="btn-status" onclick="cambiar_status('Usado')">
				<input type="button" value="REACONDICIONADO" id="Reacondicionado" class="btn-status" onclick="cambiar_status('Reacondicionado')">
			</div>
			<strong id='espere_info'></strong>
			<div class="content-btn">
				<input type="button" class="btn" id="btn" value="CERRAR" onclick="modal('modal-info')">
				<input type="button" class="btn" id="btn" value="GUARDAR CAMBIOS" onclick="update_info()">
			</div><br>
		</div>
	</section>

	<section class="modal" id='modal-img' style="display: none;" onclick="modal('modal-img')">
		<div class="content-modal" onclick="modal('modal-img')">
			<h2>IMAGENES</h2><br>
			<input type="file" id="img" multiple>
			<div id="content-img"></div><hr>
			<strong id="num"></strong>
			<progress value="0" max="100" id="carga" style="width: 100%"></progress>
			<strong id='espere_img'></strong>
			<div class="content-btn">
				<input type="button" class="btn" id="btn" value="CERRAR" onclick="modal('modal-img')">
				<input type="button" class="btn" id="btn" value="ELIMINAR IMAGENES" onclick="delete_img_view_all()">
				<input type="button" class="btn" id="btn" value="CAMBIAR PORTADA" onclick="getUpdate('img-sele','',true)">
				<input type="button" class="btn" id="btn" value="SUBIR IMAGENES" onclick="add_img_view()">
			</div><br>
		</div>
	</section>
	<section class="modal" id='modal-img-sele' style="display: none;" onclick="modal('modal-img-sele')">
		<div class="content-modal" onclick="modal('modal-img-sele')">
			<h2>ELIGE LA PORTADA DEL PRODUCTO</h2><br>
			<div id="content-img-sele"></div><hr>
		</div>
	</section>

	<section class="modal" id='modal-color' style="display: none;" onclick="modal('modal-color')">
		<div class="content-modal" onclick="modal('modal-color')">
			<h2>COLORES</h2><br>
			<input type="text" id="num_color" value="1" readonly hidden>
			<div id="content-color-view"></div><hr>
			<input type="button" value=" - " style="font-size: 18px; padding: 0px 5px 0px 5px; color: red; cursor: pointer;" onclick="color('remove')">
			<input type="button" value=" + " style="font-size: 18px; padding: 0px 5px 0px 5px; color: green; cursor: pointer;" onclick="color('add')">
			<div id='content-color'>
				<input type='color' placeholder='Color' class='caja' id='color1' required>
			</div><hr>
			<strong id='espere_color'></strong>
			<div class="content-btn">
				<input type="button" class="btn" id="btn" value="CERRAR" onclick="modal('modal-color')">
				<input type="button" class="btn" id="btn" value="AGREGAR COLORES" onclick="add_color_view()">
			</div><br>
		</div>
	</section>

	<section class="modal" id='modal-file' style="display: none;" onclick="modal('modal-file')">
		<div class="content-modal" onclick="modal('modal-file')">
			<h2>ARCHIVO</h2><br>
			<strong id="title_file"></strong><br><br>
			<input type="file" id="file_view"><br><br>
			<progress value="0" max="100" id="carga_view" style="width: 100%"></progress>
			<strong id='espere_file'></strong>
			<div class="content-btn">
				<input type="button" class="btn" id="btn" value="CERRAR" onclick="modal('modal-file')">
				<input type="button" class="btn" id="btn" value="SUBIR ARCHIVO" onclick="add_file_view()">
			</div><br>
		</div>
	</section>

	<section class="modal" id='modal-opinion' style="display: none;" onclick="modal('modal-opinion')">
		<div class="content-modal" onclick="modal('modal-opinion')">
			<h2>OPINIONES</h2><br>
			<div class="content-opinions" id="content-opinions"></div>
		</div>
	</section>

	<section class="modal" id='modal-question' style="display: none;" onclick="modal('modal-question')">
		<div class="content-modal" onclick="modal('modal-question')">
			<h2>PREGUNTAS</h2><br>
			<div class="content-questions" id="content-questions"></div>
		</div>
	</section>
	<section class="modal" id='modal-info-answer' style="display: none;" onclick="modal('modal-info-answer')">
		<div class="content-modal" onclick="modal('modal-info-answer')">
			<h2 id="text-question"></h2><br>
			<textarea class="caja" id="text-answer" placeholder="Escribe una respuesta..."></textarea>
			<div class="content-btn" id="content-btn-answer">
			</div><br>
		</div>
	</section>

</body>
</html>