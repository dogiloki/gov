<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GOV</title>
	<link rel="stylesheet" type="text/css" href="css/products/view.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/products.js"></script>
</head>
<body>

<section class="content-products-view">
	<h3>MÁS VISTOS<h3><hr><br>
		<?php
		$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE quantity>=1 ORDER BY views DESC LIMIT 5");
		$contador_products=0;
		while($row_product=mysqli_fetch_array($rs_product)){
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
							if(isset($_SESSION['gov']['id'])){
								if($row_cart['id_user']==$_SESSION['gov']['id']){
									?><li style="background: #75a800; background: #444;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">QUITAR CARRITO</li><?php
								}else{
									?><li style="background: #75a800;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">CARRITO</li><?php
								}
							}else{
								?><li style="background: #75a800;" id='btn_<?php echo $row_product['id']; ?>' onclick="cart('<?php echo $row_product['id']; ?>')">CARRITO</li><?php
							}
						}
						if($veri_cart==false){
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
		?>
</section>

</body>
</html>