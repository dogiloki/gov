<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BUSQUEDA</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<link rel="stylesheet" type="text/css" href="css/products/view.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/products.js"></script>
</head>
<body>

<?php
	
	include("models/header.php");
	if(isset($_POST['search']) && isset($_POST['category']) && isset($_GET['page']) && $_POST['search']!='%'){
		$search=$_POST['search'];
		$category=$_POST['category'];
	}else{
		return header("location:index.php");
	}
	$filtrado="";
	if(isset($_POST['seccion'])){
		if($_POST['seccion']!=''){
			$filtrado="seccion='".$_POST['seccion']."' AND ";
			$seccion=$_POST['seccion'];
		}else{
			$seccion="";
		}
	}else{
		$seccion="";
	}
	if(isset($_POST['format'])){
		if($_POST['format']!=''){
			$filtrado.="format='".$_POST['format']."' AND ";
			$format=$_POST['format'];
		}else{
			$format="";	
		}
	}else{
		$format="";
	}
	if(isset($_POST['status'])){
		if($_POST['status']!=''){
			$filtrado.="status='".$_POST['status']."' AND";
			$status=$_POST['status'];
		}else{
			$status="";
		}
	}else{
		$status="";
	}
	?>
	<section class="content-products-view">
		<div>
			<li class="btn-options">
				<div class="menu-filter" title="Ver filtros" onclick="modal('content-filter')">▼</div>
				<div onclick="modal('modal-filter')">FILTRAR</div>
			</li>
			<section class="content-filter" id='content-filter' style="display: none;">
				<li><strong>CATEGORÍA: </strong><?php if($category!=''){ echo strtoupper($category); }else { echo "TODOS"; } ?></li>
				<li><strong>SECCIÓN: </strong><?php if($seccion!=''){ echo strtoupper($seccion); }else { echo "TODOS"; } ?></li>
				<li><strong>FORMATO: </strong><?php if($format!=''){ echo strtoupper($format); }else { echo "TODOS"; } ?></li>
				<li><strong>ESTADO: </strong><?php if($status!=''){ echo strtoupper($status); }else { echo "TODOS"; } ?></li>
				<form action="search.php?page=1" method="post">
					<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
					<input type="text" name="category" value="Todos" readonly hidden>
					<input type="text" name="seccion" value="" readonly hidden>
					<input type="text" name="format" value="" readonly hidden>
					<input type="text" name="status" value="" readonly hidden>
					<button style="padding: 5px; color: #f5f5f5; background: none; border: 1px solid #c2c2c2; margin-left: 5px; font-size: 12px; cursor: pointer; color: #75a800;" onclick="filtro('status','')">ELIMINAR FILTROS</button>
				</form>
			</section>
			<!--<li class="btn-options" onclick="modal('modal-filter')"><div class="menu-filter" title="Ver filtros">▼</div>ORDENAR</li>-->
		</div>
		<h3>BUSQUEDA: <?php echo $search; ?><h3><hr><br>
		<?php
		$likes=explode(" ",$search);
		$like="";
		for($a=0; $a<sizeof($likes); $a++){
			$like.="title LIKE '%".$likes[$a]."%' OR description LIKE '%".$likes[$a]."%' OR category LIKE '%".$likes[$a]."%' OR seccion LIKE '%".$likes[$a]."%' OR ";
		}
		$like=substr($like,0,strlen($like)-4);
		if($category=='Todos'){
			$sql="SELECT * FROM products WHERE ".$filtrado." (".$like.") AND quantity>0 ORDER BY views DESC";
		}else{
			$sql="SELECT * FROM products WHERE ".$filtrado." category LIKE '%".$category."%' AND (".$like.") AND quantity>0 ORDER BY views DESC";
		}
		//Limite de busqueda
		$page=0;
		$num_row=mysqli_num_rows(mysqli_query($conexion,$sql));
		if(isset($_GET['page'])){
			if($page==1){
				$page=0;
			}else{
				$page=$_GET['page'];
				$page=($page-1)*20;
			}
		}
		$sql.=" LIMIT ".$page.",20";
		//Mostrar consulta
		$contador_products=0;
		$veri_product=false;
		$rs_product=mysqli_query($conexion,$sql);
		while($row_product=mysqli_fetch_array($rs_product)){
			$veri_product=true;
			$img=explode("&",$row_product['img']);
			$contador_products++;
			if($contador_products==1){
				echo "<article class='products'>";
			}
			?>
			<div class='product' onmouseover="modal('content-options<?php echo $row_product['id']; ?>')" onmouseout="modal('content-options<?php echo $row_product['id']; ?>')" id='product<?php echo $row_product['id']; ?>'>
			<?php
			echo "<div class='content-status'>".$row_product['status']."</div>
				<div class='content-img'><img src='".$img[0]."' width='100%' height='100%'></div>
				<span></span>
				<div class='content-title'>".utf8_encode($row_product['title'])."</div>
				<div class='content-price'>$".number_format($row_product['price'])." MXN</div>";
				?>
				<div class="content-options" id='content-options<?php echo $row_product['id']; ?>' style="display: none;">
					<?php
					if(isset($_SESSION['gov']['id']) && download($conexion,$row_product['id'])==false){
						$rs_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_product='".$row_product['id']."' AND id_user='".$_SESSION['gov']['id']."'");
						$veri_cart=false;
						while($row_cart=mysqli_fetch_array($rs_cart)){
							$veri_cart=true;
							if(isset($_SESSION['gov']['id']) && download($conexion,$row_product['id'])==false){
								if($row_cart['id_user']==$_SESSION['gov']['id']){
									?><li style="background: #75a800; background: #444;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">QUITAR CARRITO</li><?php
								}else{
									?><li style="background: #75a800;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">CARRITO</li><?php
								}
							}else{
								?><li style="background: #75a800;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">CARRITO</li><?php
							}
						}
						if($veri_cart==false && download($conexion,$row_product['id'])==false){
							?><li style="background: #75a800;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">CARRITO</li><?php
						}
					}else{
						if(!isset($_SESSION['gov']['id'])){
							?><li style="background: #75a800;" onclick="window.location='login.php'">CARRITO</li><?php
						}
					}
					if(isset($_SESSION['gov']['id'])){
							if($row_product['format']=='Digital' && download($conexion,$row_product['id'])==true){
								?>
								<li style="background: #e55d15;" onclick="window.location='download.php?id=<?php echo $row_product['id']; ?>'">DESCARGAR</li>
								<?php
							}else{
								?>
								<li style="background: #e55d15;" onclick="window.location='buy.php?id_product=<?php echo $row_product['id']; ?>&cantidad=1&format=<?php echo $row_product['format']; ?>'">COMPRAR</li>
								<?php
							}
						}else{
							?>
							<li style="background: #e55d15;" onclick="window.location='login.php'">COMPRAR</li>
							<?php
						}
					?>
					<li style="background: #2e82ff;" onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'">DETALLES</li>
				</div>
				<?php
				if($contador_products==5){
					$contador_products=0;
					echo "</article>";
				}
			echo "</div>";
		}
		echo "</article>";
		if($veri_product==false){
			echo "<strong style='color: #666;'>sin productos</strong>";
		}
		echo "<section class='content-page'>";
		for($a=1; $a<=ceil($num_row/20); $a++){
			if($num_row>=20){
				?>
				<form action="search.php?page=<?php echo $a; ?>" method="post">
					<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
					<input type="text" name="category" value="<?php echo $category; ?>" readonly hidden>
					<input type="text" name="seccion" value="<?php echo $seccion; ?>" readonly hidden>
					<input type="text" name="format" value="<?php echo $format; ?>" readonly hidden>
					<input type="text" name="status" value="<?php echo $status; ?>" readonly hidden>
					<?php
					if($_GET['page']==$a){
						?><input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px; background: #353535;" class="page" id="page<?php echo $a; ?>"><?php
					}else{
						?><input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px;" class="page" id="page<?php echo $a; ?>"><?php
					}
					?>
				</form>
				<?php
			}
		}
		echo "</section>";
		?>
	</section>
	<?php
	include("models/products/explore.php");

?>

	<section class="modal-filter" id="modal-filter" onclick="modal('modal-filter')" style="display: none;">
		<form action="search.php?page=1" method="post" class="content-modal-filter" onclick="modal('modal-filter')">
			<input type="text" id="search_filter" name="search" value="<?php echo $search; ?>" readonly hidden>
			<input type="text" id="category_filter" name="category" value="<?php echo $category; ?>" readonly hidden>
			<input type="text" id="seccion" name="seccion" value="<?php echo $seccion; ?>" readonly hidden>
			<input type="text" id="format" name="format" value="<?php echo $format; ?>" readonly hidden>
			<input type="text" id="status" name="status" value="<?php echo $status; ?>" readonly hidden>
			<h1 style="text-align: center;">FILTRADO</h1>
			<fieldset class="datos-filter">
				<legend class="text-filter">CATEGORÍAS</legend>
				<?php
				$rs_filter=mysqli_query($conexion,"SELECT DISTINCT category FROM seccion");
				while($row_filter=mysqli_fetch_array($rs_filter)){
					?>
					<button id='filter_<?php echo $row_filter['category']; ?>' class='option-filter' onclick="filtro('category','<?php echo $row_filter['category']; ?>')">
						<?php echo strtoupper($row_filter['category']); ?>
					</button>
					<?php
				}
				?>
			</fieldset>
			<fieldset class="datos-filter">
				<legend class="text-filter">SECCIONES</legend>
				<?php
				$rs_filter=mysqli_query($conexion,"SELECT DISTINCT seccion FROM seccion WHERE category='".$_POST['category']."'");
				while($row_filter=mysqli_fetch_array($rs_filter)){
					?>
					<button id='filter_<?php echo $row_filter['seccion']; ?>' class='option-filter' onclick="filtro('seccion','<?php echo $row_filter['seccion']; ?>')">
						<?php echo strtoupper($row_filter['seccion']); ?>
					</button>
					<?php
				}
				if(mysqli_num_rows(mysqli_query($conexion,"SELECT DISTINCT seccion FROM seccion WHERE category='".$_POST['category']."'"))==0){
					?>
					<strong style="color: #666; font-size: 14px;">ELIGE UNA CATEGORÍA</strong>
					<?php
				}
				?>
			</fieldset>
			<fieldset class="datos-filter">
				<legend class="text-filter">FORMATO</legend>
				<?php
				$rs_filter=mysqli_query($conexion,"SELECT DISTINCT format FROM products");
				while($row_filter=mysqli_fetch_array($rs_filter)){
					?>
					<button id='filter_<?php echo $row_filter['format']; ?>' class='option-filter' onclick="filtro('format','<?php echo $row_filter['format']; ?>')">
						<?php echo strtoupper($row_filter['format']); ?>
					</button>
					<?php
				}
				?>
			</fieldset>
			<fieldset class="datos-filter">
				<legend class="text-filter">ESTADO</legend>
				<?php
				$rs_filter=mysqli_query($conexion,"SELECT DISTINCT status FROM products");
				while($row_filter=mysqli_fetch_array($rs_filter)){
					?>
					<button id='filter_<?php echo $row_filter['status']; ?>' class='option-filter' onclick="filtro('status','<?php echo $row_filter['status']; ?>')">
						<?php echo strtoupper($row_filter['status']); ?>
					</button>
					<?php
				}
				?>
			</fieldset>
		</form>
	</section>

</body>
</html>