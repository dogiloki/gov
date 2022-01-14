<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="Expires" content="0">
  		<meta http-equiv="Last-Modified" content="0">
  		<meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
  		<meta http-equiv="Pragma" content="no-cache">
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/products/product.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/products.js"></script>
</head>
<body>

<?php

	include("models/header.php");

	echo "<section class='content-product'>";
		$rs=mysqli_query($conexion,"SELECT * FROM products WHERE quantity>=1 AND id='".$_GET['id']."'");
		$veri_product=false;
		while($row=mysqli_fetch_array($rs)){
			$veri_product=true;
			echo "<title>".$row['title']."</title>";
			//AGREGAR AL HISTORIAL Y A VISTAS
			if(isset($_SESSION['gov']['id'])){
				$veri_record=false;
				$rs_record=mysqli_query($conexion,"SELECT * FROM record WHERE id_product='".$_GET['id']."' AND id_user='".$_SESSION['gov']['id']."'");
				while($row_record=mysqli_fetch_array($rs_record)){
					mysqli_query($conexion,"UPDATE record SET date=now() WHERE id='".$row_record['id']."'") or die ("ERROR EN EL SERVIDOR");
					$veri_record=true;
				}
				if($veri_record==false){
					mysqli_query($conexion,"INSERT INTO record VALUES ('".uniqid()."','".$_SESSION['gov']['id']."','".$_GET['id']."',now())") or die ("ERROR EN EL SERVIDOR");
				}
			}
			mysqli_query($conexion,"UPDATE products SET views=views+1 WHERE id='".$_GET['id']."'") or die ("ERROR EN EL SERVIDOR");
			//MOSTRAR EL PRODUCTO
			$img=explode("&",$row['img']);
			$color=explode("&",$row['color']);
			echo "<div class='content-product-sele-img'>";
			for($i=0; $i<sizeof($img)-1; $i++){
				?>
				<img src='<?php echo $img[$i]; ?>' class='product-sele-img' onclick="cambio_img('<?php echo $img[$i]; ?>')">
				<?php
			}
			echo "</div>";
			//IMAGEN
			echo "<div class='content-product-img'>";
				?>
				<img src='<?php echo $img[0]; ?>' class='product-img' id='img-sele' onmouseover="modal('myresult')" onmouseout="modal('myresult')" onclick="modal('myresult')">
				<?php
			echo "</div>";
			echo "<div class='product-info'>
				<h4 style='color: #c2c2c2; font-size: 14px;'>".$row['status']." - ".$row['sold']." vendidos</h4>
				<h1>".utf8_encode($row['title'])."</h1>";
				//ESTRELLAS
				echo "<h3>";
					for($e=1; $e<=5; $e++){
						if($e<=$row['stars']){
							echo "<img src='img/estrella.png' width='20px' height='20px'>";
						}else{
							echo "<img src='img/estrella_vacia.png' width='20px' height='20px'>";
						}
					}
				echo "</h3>";
				//PRECIO
				echo "<li class='content-product-price'>$".number_format($row['price'])." MXN</li>";
				//Colores
				echo "<input type='text' value='".$color[0]."' id='color_elegido' name='color_elegido' readonly hidden>";
				if($row['color']!='&'){
						echo "<div class='content-product-color'><h4>COLORES</h4>";
							for($i=0; $i<sizeof($color)-1; $i++){
								if($i==0){
									?>
									<li style="background: <?php echo $color[$i]; ?>; border: 3px solid #f5f5f5;" class="product-color" id="<?php echo $i; ?>color<?php echo $row['id']; ?>" onclick="color('<?php echo $i; ?>color<?php echo $row['id']; ?>','<?php echo $color[$i]; ?>')"></li>
									<?php
								}else{
									?>
									<li style="background: <?php echo $color[$i]; ?>" class="product-color" id="<?php echo $i; ?>color<?php echo $row['id']; ?>" onclick="color('<?php echo $i; ?>color<?php echo $row['id']; ?>','<?php echo $color[$i]; ?>')"></li>
									<?php
								}
							}
						echo "</div>";
					}
				//CANTIDAD
				?>
					<?php
					if($row['format']!='Digital'){
						?>
						<li class='content-product-cantidad'><h4>CANTIDAD:</h4>
						<button class='product-btn-cantidad' style='color: #75a800;' onclick="cantidad('menos','<?php echo $row['id']; ?>','')"> -- </button>
						<strong id='cantidad<?php echo $row['id']; ?>' name='cantidad_elegido'>1</strong>
						<button class='product-btn-cantidad' style='color: #2e82ff' onclick="cantidad('mas','<?php echo $row['id']; ?>','')"> + </button>
						<div style="font-size: 12px; color: #c2c2c2;">(disponibles: <?php echo $row['quantity'] ?>)</div>
						<?php
					}else{
						?>
						<div style="font-size: 12px; color: #c2c2c2;">(disponibles: <?php echo $row['quantity'] ?>)</div>
						<strong id='cantidad<?php echo $row['id']; ?>' name='cantidad_elegido' style='display: none;'>1</strong>
						<?php
					}
					?>
				</li>
				<?php
				//BOTONES PARA CARRITO Y COMPRAR
				?>
				<br><br><strong style='color: #c9321c;' id='aviso'></strong>
				<?php
				if(isset($_SESSION['gov']['id']) && download($conexion,$_GET['id'])==true){
					?>
					<button class='product-btn' style='background: #F86400; margin-top: 20px;' onclick="window.location='download.php?id=<?php echo $_GET['id']; ?>'">Descargar</button>
					<?php
				}else{
					if(isset($_SESSION['gov']['id'])){
						?>
						<button class='product-btn' style='background: #F86400; margin-top: 20px;' onclick="comprar('<?php echo $row['id']; ?>','<?php echo $row['format']; ?>')">Comprar</button>
						<?php
					}else{
						?>
						<button class='product-btn' style='background: #F86400; margin-top: 20px;' onclick="window.location='login.php'">Comprar</button>
						<?php
					}
				}
				if(isset($_SESSION['gov']['id']) && download($conexion,$_GET['id'])==false){
					$rs_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_product='".$row['id']."' AND id_user='".$_SESSION['gov']['id']."'");
						$veri_cart=false;
						while($row_cart=mysqli_fetch_array($rs_cart)){
							$veri_cart=true;
							if($row_cart['id_user']==$_SESSION['gov']['id']){
								?><button class='product-btn' id='btn_<?php echo $row['id']; ?>' style='background: #444; margin-top: 5px;'  onclick="cart('<?php echo $row['id']; ?>')">Quitar del carrito</button><?php
							}else{
								?><button class='product-btn' id='btn_<?php echo $row['id']; ?>' style='background: #75a800; margin-top: 5px;'  onclick="cart('<?php echo $row['id']; ?>')">Añadir al carrito</button><?php
							}
						}
						if($veri_cart==false){
							?><button class='product-btn' id='btn_<?php echo $row['id']; ?>' style='background: #75a800; margin-top: 5px;'  onclick="cart('<?php echo $row['id']; ?>')">Añadir al carrito</button><?php
						}	
				}else{
					?><button class='product-btn' id='btn_<?php echo $row['id']; ?>' style='background: #75a800; margin-top: 5px;'  onclick="window.location='login.php'">Añadir al carrito</button><?php
				}
				//DESCRIPCIÓN
				?>
				<div class='content-product-description' title='Más información' onclick="modal('content-modal-moreInfo')">
				<?php
					echo "<h4 style='margin-bottom: 5px;'>DESCRIPCIÓN</h4><hr>
						<p>".utf8_encode($row['description'])."</p>
					</div>";
			echo "</div>";

			//MODAL MÁS INFORMACIÓN
			echo "<section class='content-modal-moreInfo' id='content-modal-moreInfo' style='display: none;'>
				<div class='modal-moreInfo'>";
				?>
				<h2 onclick="modal('content-modal-moreInfo')">X</h2>
				<?php
				echo "<h1>DESCRIPCIÓN</h1>
					<p>".utf8_encode($row['description'])."</p>
				</div>
			</section>";
		}
	echo "</section>";
	if($veri_product==false){
		echo "<script>window.location='index.php'</script>";
	}
	/* Opiniones */
	echo "<h4 style='color: #c2c2c2; margin-left: 50px;'>OPINIONES</h4>";
	echo "<section class='content-opinions'>";
		$rs_opinion=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$_GET['id']."' ORDER BY date DESC LIMIT 5");
		$veri_opinion=false;
		while($row_opinion=mysqli_fetch_array($rs_opinion)){
			$veri_opinion=true;
			echo "<div class='opinion'>";
				$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_opinion['id_user']."'");
				while($row_user=mysqli_fetch_array($rs_user)){
					echo "<strong><img src='".$row_user['img']."' style='max-width: 20px; margin-right: 5px;'>".$row_user['name']." ".$row_user['surname'];
						for($s=1; $s<=5; $s++){
							if($s<=$row_opinion['stars']){
								echo "<img src='img/estrella.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
							}else{
								echo "<img src='img/estrella_vacia.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
							}
						}
					echo "</strong>";
				}
				echo "<p class='text-opinion'>".$row_opinion['opinion']."</p>";
			echo "</div>";
		}
		if($veri_opinion==false){
			echo "<strong style='color: #c2c2c2;'>No hay opiniones</strong>";
		}
	echo "</section>";
	/* Hacer pregunta */
	echo "<h4 style='color: #c2c2c2; margin-left: 50px;'>PREGUNTAR ALGO</h4>";
	?>
	<form action="controllers/products.php?question=add&id_product=<?php echo $_GET['id']; ?>" method="post" class="content-add-question">
		<textarea class="text-question" placeholder="Escribe algo..." name='text' required></textarea>
		<input type="submit" value="PUBLICAR" style="background: none; padding: 2px 5px 2px 5px; cursor: pointer; color: #F86400; border: 1px solid #c2c2c2; margin-top: 5px;">
	</form>
	<?php
	/* Preguntas */
	echo "<h4 style='color: #c2c2c2; margin-left: 50px;'>PREGUNTAS</h4>";
	echo "<section class='content-opinions'>";
		$rs_question=mysqli_query($conexion,"SELECT * FROM questions WHERE id_product='".$_GET['id']."' ORDER BY date DESC LIMIT 5");
		$veri_opinion=false;
		while($row_question=mysqli_fetch_array($rs_question)){
			$veri_opinion=true;
			echo "<div class='opinion'>";
				$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_question['id_user']."'");
				while($row_user=mysqli_fetch_array($rs_user)){
					echo "<strong style='display: flex; align-content: center;'><img src='".$row_user['img']."' style='max-width: 20px; margin-right: 5px;'>".$row_user['name']." ".$row_user['surname'];
					echo "</strong>";
				}
				echo "<p class='text-opinion'>".$row_question['question']."</p>";
				echo "<p class='text-answer'>".$row_question['answer']."</p>";
			echo "</div>";
		}
		if($veri_opinion==false){
			echo "<strong style='color: #c2c2c2;'>No hay preguntas</strong>";
		}
	echo "</section>";

	include("models/products/explore.php");
?>
<div id="myresult" class="img-zoom-result"></div>
<script type="text/javascript">
	function cambio_img(op){
    	document.getElementById('myresult').style.display='';
		document.getElementById('img-sele').src=op;
		imageZoom("img-sele","myresult");
		document.getElementById('myresult').style.display='none';
	}
	window.onload=function(){
		imageZoom("img-sele","myresult");
		document.getElementById('myresult').style.display='none';
	}
	imageZoom("img-sele","myresult");
</script>

</body>
</html>