<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GOV</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/products/buy.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/buy.js"></script>
</head>
<body>

<?php

	include("models/header.php");
	if(!isset($_SESSION['gov']['id'])){
		return header("location:index.php");
	}
	$format="";
	if(isset($_GET['format'])){
		$format=$_GET['format'];
	}else{
		$format='Físico';
	}
?>

<?php
if($format!='Digital'){
?>
	<section class="content-datos">
		<fieldset class="datos">
			<legend class="datos-title">Datos de entrega</legend>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<div class="content-caja">
					Nombre / Quien lo recibe *
					<input type="text" class="caja" id="name_recibe" placeholder="Nombre quien recibe la entrega">
				</div>
				<div class="content-caja">
					Dirección (calle, número de casa o interior, torre, empresa) *
					<input type="text" class="caja" id="direccion" placeholder="Dirección de entrega">
				</div>
				<div class="content-caja">
					Telefono *
					<input type="text" class="caja" id="phone" placeholder="Telefono" value="<?php echo $_SESSION['gov']['phone']; ?>" style="cursor: pointer;" title="cambiar" readonly onclick="modal('modal_phone')">
				</div>
				<div class="content-caja">
					Referencias *
					<input type="text" class="caja" id="referencias" placeholder="Referencias nos ayuda a ubicarnos mejor">
				</div>
			</div>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<div class="content-caja">
					Código postal *
					<input type="text" class="caja" id="cp" placeholder="Código postal" value="<?php echo $_SESSION['gov']['cp']; ?>" style="cursor: pointer;" title="cambiar" readonly onclick="modal('modal_cp')" onkeyup="cp()">
				</div>
				<div class="content-caja">
					Colonia *
					<input type="text" class="caja" id="colonia" placeholder="Colonia" value="<?php echo getCodePostal('colonia',$_SESSION['gov']['cp'],$conexion); ?>" style="cursor: default; opacity: 0.7;" readonly>
				</div>
				<div class="content-caja">
					Municipio *
					<input type="text" class="caja" id="municipio" placeholder="Municipio" value="<?php echo getCodePostal('municipio',$_SESSION['gov']['cp'],$conexion); ?>" style="cursor: default; opacity: 0.7;" readonly>
				</div>
				<div class="content-caja">
					Estado *
					<input type="text" class="caja" id="estado" placeholder="Estado" value="<?php echo getCodePostal('estado',$_SESSION['gov']['cp'],$conexion); ?>" style="cursor: default; opacity: 0.7;" readonly>
				</div>
			</div>
		</fieldset>
	</section>
<?php
}else{
	?>
	<section class="content-datos" style="display: none;">
		<fieldset class="datos">
			<legend class="datos-title">Datos de entrega</legend>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<div class="content-caja">
					Nombre / Quien lo recibe *
					<input type="text" class="caja" id="name_recibe" value='null' placeholder="Nombre quien recibe la entrega">
				</div>
				<div class="content-caja">
					Dirección (calle, número de casa o interior, torre, empresa) *
					<input type="text" class="caja" id="direccion" value='null' placeholder="Dirección de entrega">
				</div>
				<div class="content-caja">
					Telefono *
					<input type="text" class="caja" id="phone" value='null' placeholder="Telefono" value="<?php echo $_SESSION['gov']['phone']; ?>" style="cursor: pointer;" title="cambiar" readonly onclick="modal('modal_phone')">
				</div>
				<div class="content-caja">
					Referencias *
					<input type="text" class="caja" id="referencias" value='null' placeholder="Referencias nos ayuda a ubicarnos mejor">
				</div>
			</div>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<div class="content-caja">
					Código postal *
					<input type="text" class="caja" id="cp" placeholder="Código postal" value="null" style="cursor: pointer;" title="cambiar" readonly onclick="modal('modal_cp')" onkeyup="cp()">
				</div>
				<div class="content-caja">
					Colonia *
					<input type="text" class="caja" id="colonia" placeholder="Colonia" value="null" style="cursor: default; opacity: 0.7;" readonly>
				</div>
				<div class="content-caja">
					Municipio *
					<input type="text" class="caja" id="municipio" placeholder="Municipio" value="null" style="cursor: default; opacity: 0.7;" readonly>
				</div>
				<div class="content-caja">
					Estado *
					<input type="text" class="caja" id="estado" placeholder="Estado" value="null" style="cursor: default; opacity: 0.7;" readonly>
				</div>
			</div>
		</fieldset>
	</section>
<?php
}
?>

	<section class="content-datos">
		<?php
			$cantidad_buy=0;
			$total_buy=0;
			$id_product="";
			$cantidad=0;
			$color="";
			if($_GET['id_product']=='cart'){
				$cantidad_buy=mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."'"));
				$total_buy=total_pago_cart($conexion,$format);
				$id_product="cart";
				$cantidad=null;
				$color="";
			}else{
				$cantidad_buy=1;
				$id_product=$_GET['id_product'];
				$cantidad=$_GET['cantidad'];
				$rs_buy=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_GET['id_product']."'");
				while($row_buy=mysqli_fetch_array($rs_buy)){
					$color_buy=explode("&",$row_buy['color']);
					$total_buy=$row_buy['price']*$_GET['cantidad'];
					if($row_buy['color']!='&' || $row_buy['color']!=''){
						if(isset($_GET['color'])){
							$color=$_GET['color'];
						}else{
							$color=$color_buy[0];
						}
					}else{
						$color="";
					}
				}
			}
		?>
		<fieldset class="datos">
			<legend class="datos-title">Método de pago (débito)</legend>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<!--<div class="content-caja">
					Titular de la tarjeta *
					<input type="text" class="caja" id="titular" placeholder="Nombre a quien esta la tarjeta">
				</div>-->
				<div class="content-caja">
					<div id='aviso_tarjeta' class="aviso"></div>Número de tarjera *
					<input type="text" class="caja" id="num_tarjeta" placeholder="Número de tarjeta">
				</div>
				<div class="content-caja">
					Fecha expiración *
					<input type="text" class="caja" id="exp_dia" placeholder="MES" style="width: 50px;"> <span style="font-size: 20px;">/</span>
					<input type="text" class="caja" id="exp_mes" placeholder="AÑO" style="width: 50px;">
				</div>
				<div class="content-caja">
					Código de seguridad *
					<input type="text" class="caja" id="cvv" placeholder="CVV" style="width: 50px;">
				</div>
			</div>
			<div style="display: block; margin: 0px 60px 0px 60px;">
				<div class="content-caja">
					<div class="content-info">
						Productos a comprar: <?php echo $cantidad_buy; ?>
					</div>
				</div>
				<div class="content-caja">
					<div class="content-info">
						Total a pagar: $<?php echo number_format($total_buy)." MXN"; ?>
					</div>
				</div>
				<div class="content-caja">
					<strong class="aviso" id="aviso_pago" style="color: #ff6d5c; display: block; margin-bottom: 10px;"></strong>
					<input type="button" value="FINALIZAR COMPRA" style="padding: 5px 20px 5px 20px; color: #f5f5f5; font-size: 20px; background: #666; cursor: pointer; border: 1px solid #c2c2c2; border-radius: 5px;" onclick="comprar('<?php echo $total_buy; ?>','<?php echo $id_product; ?>','<?php echo $cantidad; ?>','<?php echo $color; ?>','<?php echo $_GET['format']; ?>')">
				</div>
			</div>
		</fieldset>
	</section>

	<section class="content-datos" style="margin-bottom: 100px;">
		<fieldset class="datos" style="display: block;">
			<legend class="datos-title">Productos a comprar</legend>
			<?php
				if($_GET['id_product']=='cart'){
					if(!isset($_GET['id_product'])){
						echo "<script>window.location='index.php'</script>";
						return 0;
					}
					$rs_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."'");
					if(mysqli_num_rows($rs_cart)==0){
						echo "<script>window.location='index.php'</script>";
						return 0;
					}
					$contador=0;
					while($row_cart=mysqli_fetch_array($rs_cart)){
						$contador++;
						if($contador==1){
							echo "<section class='content-products'>";
						}
						$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_cart['id_product']."'");
						
						while($row_product=mysqli_fetch_array($rs_product)){
							$img=explode("&",$row_product['img']);
							echo "<div class='product'>";
								?>
									<div class='content-img' style="cursor: pointer;" onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'"><img src='<?php echo $img[0]; ?>' width='100%' height='100%'></div>
									<div class='content-title' style="cursor: pointer;" onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'"><?php echo utf8_encode($row_product['title']); ?></div>
								<?php
								echo "<div class='content-precio'>Precio: $".number_format($row_product['price'])."</div>";
								echo "<div class='content-cantidad'>Cantidad: ".$row_cart['quantity']."</div>";
								if($row_cart['color']!=''){
									echo "<div class='content-color'>Color: <li style='cursor: default; background: ".$row_cart['color']."; border: 1px solid #c2c2c2;' class='product-color'></li></div>";
								}
								echo "<div class='content-precio'>Total de pago: $".number_format($row_product['price']*$row_cart['quantity'])."</div>";
							echo "</div>";
						}
						if($contador==3){
							$contador=0;
							echo "</section>";
						}
					}
				}else{
					if(!isset($_GET['id_product']) || !isset($_GET['cantidad'])){
						return header("location:index.php");
					}
					$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_GET['id_product']."'");
					if(mysqli_num_rows($rs_product)==0){
						echo "<script>window.location='index.php'</script>";
						return 0;
					}
					while($row_product=mysqli_fetch_array($rs_product)){
						if($row_product['quantity']<=0 || $_GET['cantidad']>$row_product['quantity'] || $_GET['cantidad']<=0 || ($format=='Digital' && $_GET['cantidad']>1)){
							echo "<script>window.location='index.php'</script>";
							return 0;
						}
						$color=explode("&",$row_product['color']);
						$img=explode("&",$row_product['img']);
						echo "<div class='product'>";
							?>
								<div class='content-img' style="cursor: pointer;" onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'"><img src='<?php echo $img[0]; ?>' width='100%' height='100%'></div>
								<div class='content-title' style="cursor: pointer;" onclick="window.location='product.php?id=<?php echo $row_product['id']; ?>'"><?php echo utf8_encode($row_product['title']); ?></div>
							<?php
							echo "<div class='content-precio'>Precio: $".number_format($row_product['price'])."</div>";
							echo "<div class='content-cantidad'>Cantidad: ".$_GET['cantidad']."</div>";
							if($row_product['color']!='&'){
								echo "<div class='content-color'>Color: <li style='cursor: default; background: ";if(isset($_GET['color'])){echo "#".$_GET['color'];}else{echo $color[0];}echo "; border: 1px solid #c2c2c2;' class='product-color'></li></div>";
							}
							echo "<div class='content-precio'>Total de pago: $".number_format($row_product['price']*$_GET['cantidad'])."</div>";
						echo "</div>";
					}
				}
			?>
		</fieldset>
	</section>

	<!-- Modal -->
	<section class="modal" id="modal_phone" style="display: none;" onclick="modal('modal_phone')">
		<div class="content-modal" onclick="modal('modal_phone')">
			<div id='aviso_phone' class="aviso"></div>Número nuevo
			<input type="text" id='phone_new' class="caja" placeholder="Nombre de telefono" onkeyup="phone()">
			<input type="button" class="modal-btn" value="Cambiar" onclick="updateUser('phone')">
		</div>
	</section>
	<section class="modal" id="modal_cp" style="display: none;" onclick="modal('modal_cp')">
		<div class="content-modal" onclick="modal('modal_cp')">
			<div id='aviso_phone' class="aviso"></div>Número nuevo
			<input type="text" id='cp_new' class="caja" placeholder="Código postal" onkeyup="cp()">
			<input type="text" id='colonia_new' class="caja" placeholder="Colonia" disabled style="opacity: 0.8;">
			<input type="text" id='municipio_new' class="caja" placeholder="Municipio" disabled style="opacity: 0.8;">
			<input type="text" id='estado_new' class="caja" placeholder="Estado" disabled style="opacity: 0.8;">
			<input type="button" class="modal-btn" value="Cambiar" onclick="updateUser('cp')">
		</div>
	</section>

</body>
</html>