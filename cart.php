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
	<title>CARRITO</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/products/cart.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/products.js"></script>
</head>
<body>

<?php

	include("models/header.php");
	if(!isset($_SESSION['gov']['id'])){
		header("location:index.php");
	}
	$format="";
	if(isset($_GET['format'])){
		$format=$_GET['format'];
	}else{
		$format='Físico';
	}
	if($format=='Digital'){
		?>
		<input type="button" value="IR A CARRITO FÍSICO" class="btn-format" onclick="window.location='cart.php?format=Físico'">
		<?php
	}else{
		?>
		<input type="button" value="IR A CARRITO DIGITAL" class="btn-format" onclick="window.location='cart.php?format=Digital'">
		<?php
	}
	echo "<fieldset class='content-cart'>";
		?>
			<legend class="btn-cart" onclick="window.location='controllers/products.php?cart=remove_all&format=<?php echo $format; ?>'">VACIAR CARRITO <?php echo strtoupper($format); ?></legend>
		<?php
		$total_cart=mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."'"));
		$rs_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."' ORDER BY date DESC");
		$veri_cart=false;
		while($row_cart=mysqli_fetch_array($rs_cart)){
			$veri_cart=true;
			$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_cart['id_product']."'");
			while($row_product=mysqli_fetch_array($rs_product)){
				$img=explode("&",$row_product['img']);
				$color=explode("&",$row_product['color']);
				echo "<article class='cart-product' id='cart_".$row_cart['id']."'>";
					?>
						<div class='cart-content-img' onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'">
							<img src='<?php echo $img[0]; ?>'>
						</div>
					<?php
					echo "<div class='cart-content-info'>";
					?>
						<strong class='cart-title' onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'">
							<?php echo utf8_encode($row_product['title']); ?>
						</strong>
					<?php
					echo "<div class='cart-price'>$".number_format($row_product['price'])." MXN";
						if($format!='Digital'){
							?>
								<div class='cart-cantidad'>Cantidad: 
									<button class='product-btn-cantidad' style='color: #75a800;' onclick="cantidad('menos','<?php echo $row_product['id']; ?>','<?php echo $row_cart['id']; ?>')"> -- </button>
									<div id='cantidad<?php echo $row_product['id']; ?>'>
										<?php
										if($row_cart['quantity']>=$row_product['quantity']){
											mysqli_query($conexion,"UPDATE cart SET quantity='".$row_product['quantity']."' WHERE id='".$row_cart['id']."'") or die ("ERROR EN SERVIDOR");
											echo $row_product['quantity'];
										}else{
											echo $row_cart['quantity'];
										}
										if($row_product['quantity']<=0){
											echo "<script>delete_cart_product('".$row_product['id']."');modal('cart_".$row_cart['id']."');</script>";
										}
										?>
									</div>
									<button class='product-btn-cantidad' style='color: #2e82ff' onclick="cantidad('mas','<?php echo $row_product['id']; ?>','<?php echo $row_cart['id']; ?>')"> + </button>
								</div>
								<div style="font-size: 12px; color: #c2c2c2;">(disponibles: <?php echo $row_product['quantity'] ?>)</div>
							<?php
						}else{
							?>
							<div style="font-size: 12px; color: #c2c2c2;">(disponibles: <?php echo $row_product['quantity'] ?>)</div>
							<div id='cantidad<?php echo $row_product['id']; ?>' style='display: none;'>
								<?php
								if($row_cart['quantity']>=$row_product['quantity']){
									mysqli_query($conexion,"UPDATE cart SET quantity='".$row_product['quantity']."' WHERE id='".$row_cart['id']."'") or die ("ERROR EN SERVIDOR");
											echo $row_product['quantity'];
								}else{
									echo $row_cart['quantity'];
								}
								if($row_product['quantity']<=0){
									echo "<script>delete_cart_product('".$row_product['id']."');modal('cart_".$row_cart['id']."');</script>";
								}
								?>
							</div>
							<?php
						}
						?>
						</div>
						<div class="cart-cantidad">
								<?php
								echo "<input type='text' value='".$row_cart['color']."' id='color_elegido".$row_cart['id']."' name='color_elegido' readonly hidden>";
								if($row_product['color']!='&'){
									echo "<div class='content-product-color'>color elegido";
										?>
											<li style="background: <?php echo $row_cart['color']; ?>" class="product-color" id="<?php echo $row_cart['id']; ?>" onclick="modal('modal<?php echo $row_cart['id']; ?>')"></li>
										<?php
									echo "</div>";
									?>
									<section class='modal' style='display: none;' id='modal<?php echo $row_cart['id']; ?>' onclick="modal('modal<?php echo $row_cart['id']; ?>')">
										<div class='content-modal' onclick="modal('modal<?php echo $row_cart['id']; ?>')"><h1>Elige un color</h1><br>
									<?php
									for($i=0; $i<sizeof($color)-1; $i++){
										?>
											<input type="button"  class="product-color2" style="background: <?php echo $color[$i]; ?>" onclick="color_cart('<?php echo $row_cart['id']; ?>','<?php echo $color[$i]; ?>')">
										<?php
									}
									echo "</div></section>";
								}
								?>
						</div>
						<?php
						echo "<div class='cart-content-option'>";
							if(download($conexion,$row_product['id'])==true){
								?>
								<li class='cart-option' onclick="window.location='download.php?id=<?php echo $row_product['id']; ?>'">Descargar</li>
								<?php
							}else{
								?>
								<li class='cart-option' onclick="comprar_cart('<?php echo $row_product['id']; ?>','<?php echo $row_cart['id']; ?>','<?php echo $format; ?>')">Comprar</li>
								<?php
							}
							?>
							<li class='cart-option' onclick="delete_cart_product('<?php echo $row_product['id']; ?>');modal('cart_<?php echo $row_cart['id']; ?>')">Quitar del carrito</li>
						<?php
						echo "</div>
					</div>";
				echo "</article>";
			}
		}
		if($veri_cart==false){
			echo "<h3 style='color: #fff; padding: 10px;'>EL CARRITO ".strtoupper($format)." ESTA VACÍO</h3>";
		}else{
			?>
			<article class="cart-product" style="margin-bottom: 100px; display: block;">
				<?php
				if($format=='Digital'){
					?>
					<div class="content-total-cart" id="cantidad_productos_digital"></div>
					<div class="content-total-cart" id='total_cart_digital'></div>
					<?php
				}else{
					?>
					<div class="content-total-cart" id="cantidad_productos_fisico"></div>
					<div class="content-total-cart" id='total_cart_fisico'></div>
					<?php
				}
				?>
				<input type="button" value="COMPRAR CARRITO" class="content-total-cart" style="padding: 5px 20px 5px 20px; color: #f5f5f5; font-size: 20px; background: #666; cursor: pointer; border: 1px solid #c2c2c2; border-radius: 5px;" onclick="window.location='buy.php?id_product=cart&format=<?php echo $format; ?>'">
			</article>
			<?php
		}
	echo "</fieldset>";

?>

</body>
</html>