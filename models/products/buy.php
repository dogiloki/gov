<style type="text/css">
	.btn-format{
	margin-left: 20px;
	margin-top: 20px;
	font-size: 18px;
	cursor: pointer;
	background: none;
	border: none;
	color: #f5f5f5;
	}
	.btn-format:hover{
		color: #c2c2c2;
	}
</style>
<?php
$format="";
if(isset($_GET['format'])){
	$format=$_GET['format'];
}else{
	$format='Físico';
}
if($format=='Digital'){
	?>
	<input type="button" value="IR A COMPRAS FÍSICAS" class="btn-format" onclick="window.location='shopping.php?format=Físico'">
	<?php
}else{
	?>
	<input type="button" value="IR A COMPRAS DIGITALES" class="btn-format" onclick="window.location='shopping.php?format=Digital'">
	<?php
}
?>
<section class="content-products-view">
	<h3>COMPRAS EN INDIVIDUAL<h3><hr><br>
		<?php
		$rs_buy=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."' ORDER BY date DESC");
		$contador_products=0;
		$veri_product=false;
		while($row_buy=mysqli_fetch_array($rs_buy)){
			$veri_product=true;
			$contador_products++;
			if($contador_products==1){
				echo "<article class='products'>";
			}
			?>
			<div class='product' onmouseover="modal('content-options-individual<?php echo $row_buy['id']; ?>')" onmouseout="modal('content-options-individual<?php echo $row_buy['id']; ?>')" id='product<?php echo $row_buy['id']; ?>' title='<?php echo $format; ?>'>
			<?php
			echo "<div class='content-title'>".utf8_encode($row_buy['title'])."</div>
				<div class='content-buy'>Cantidad: ".number_format($row_buy['quantity'])."</div>
				<div class='content-buy'>Precio:<br> $".number_format($row_buy['payment'])." MXN</div>
				<div class='content-buy'>Total:<br> $".number_format($row_buy['total'])." MXN</div>
				<div class='content-date'>".$row_buy['date']."</div>";
				if($format=='Digital'){
					?>
					<div class="content-options" id='content-options-individual<?php echo $row_buy['id']; ?>' style="display: none;">				
						<li style="background: #2e82ff;" onclick="window.location='ticker.php?id=<?php echo $row_buy['id']; ?>'" title='Ver ticker'>TICKER</li>
						<li style="background: #75a800;" onclick="window.location='download.php?id=<?php echo $row_buy['id_product']; ?>'" title='Ver ticker'>DESCARGAR</li>
						<li style="background: #e55d15;" onclick="getOpinion('<?php echo $row_buy['id']; ?>')" title='Ver ticker'>OPINION</li>
					</div>
					<?php
				}else{
					?>
					<div class="content-options" id='content-options-individual<?php echo $row_buy['id']; ?>' style="display: none;">				
						<li style="background: #2e82ff;" onclick="window.location='ticker.php?id=<?php echo $row_buy['id']; ?>'" title='Ver ticker'>TICKER</li>
						<li style="background: #e55d15;" onclick="getOpinion('<?php echo $row_buy['id']; ?>')" title='Ver ticker'>OPINION</li>
					</div>
					<?php
				}
			echo "</div>";
			if($contador_products==5){
				$contador_products=0;
				echo "</article>";
			}
		}
		if($veri_product==false){
			echo "<strong style='color: #666;'>sin productos</strong>";
		}
		?>
</section>

<section class="content-products-view" style="margin-bottom: 100px;">
	<h3>COMPRAS EN CARRITO<h3><hr><br>
		<?php
		$rs_buy_cart=mysqli_query($conexion,"SELECT * FROM buys_cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."'");
		while($row_buy_cart=mysqli_fetch_array($rs_buy_cart)){
			$id_buy=explode("&",$row_buy_cart['id_buys']);
			echo "<fieldset id='caja_product_buy' style='display: block;'>";
				?>
					<legend class="ticker" onclick="window.location='ticker.php?id=<?php echo $row_buy_cart['id']; ?>'" title='Ver ticker del carrito'>TICKER</legend>
				<?php
			$contador_products_cart=0;
			for($b=0; $b<sizeof($id_buy)-1; $b++){
				$rs_buy=mysqli_query($conexion,"SELECT * FROM buys WHERE id='".$id_buy[$b]."' AND format='".$format."' ORDER BY date DESC");
				$veri_product=false;
				$contador_products_cart++;
				while($row_buy=mysqli_fetch_array($rs_buy)){
					$veri_product=true;
					if($contador_products_cart==1){
						echo "<article class='products'>";
					}
					if($b==0){
						echo "<div class='content-date'>".$row_buy['date']."</div>";
					}
					?>
						<div class='product' onmouseover="modal('content-options-cart<?php echo $row_buy['id']; ?>')" onmouseout="modal('content-options-cart<?php echo $row_buy['id']; ?>')" id='product<?php echo $row_buy['id']; ?>'>
					<?php
					echo "<div class='content-title'>".utf8_encode($row_buy['title'])."</div>
						<div class='content-buy'>Cantidad: ".number_format($row_buy['quantity'])."</div>
						<div class='content-buy'>Precio:<br> $".number_format($row_buy['payment'])." MXN</div>
						<div class='content-buy'>Total:<br> $".number_format($row_buy['total'])." MXN</div>
						<div class='content-date'>".$row_buy['date']."</div>";
						if($format=='Digital'){
							?>
							<div class="content-options" id='content-options-individual<?php echo $row_buy['id']; ?>' style="display: none;">				
								<li style="background: #2e82ff;" onclick="window.location='ticker.php?id=<?php echo $row_buy['id']; ?>'" title='Ver ticker'>TICKER</li>
								<li style="background: #75a800;" onclick="window.location='download.php?id=<?php echo $row_buy['id_product']; ?>'" title='Ver ticker'>DESCARGAR</li>
							</div>
							<?php
						}else{
							?>
							<div class="content-options" id='content-options-individual<?php echo $row_buy['id']; ?>' style="display: none;">				
								<li style="background: #2e82ff;" onclick="window.location='ticker.php?id=<?php echo $row_buy['id']; ?>'" title='Ver ticker'>TICKER</li>
							</div>
							<?php
						}
					echo "</div>";
					if($contador_products_cart==5){
						$contador_products_cart=0;
						echo "</article>";
					}
				}
			}
			echo "</fieldset>";
		}
		if($veri_product==false){
			echo "<strong style='color: #666;'>sin productos</strong>";
		}
		?>
</section>

<style type="text/css">
	.modal-opinion{
		width: 100%;
		height: 100vh;
		position: fixed;
		left: 0px; top: 0px;
		background: rgb(0,0,0,0.7);
		display: inline-flex;
		justify-content: center;
		color: #f5f5f5;
	}
	.modal-opinion .content-modal{
		margin-top: 50px;
		padding: 20px;
		border-radius: 5px;
		border: 1px solid #c2c2c2;
		background: #353535;
		max-width: 400px;
		min-width: 400px;
		height: 200px;
	}
	.modal-opinion .title{
		font-size: 20px;
		cursor: default;
		display: block;
	}
	.modal-opinion .content-opinion{
		display: block;
		margin-top: 10px;
	}
	.btn-add-opinion{
		padding: 3px 10px 3px 10px;
		font-size: 17px;
		cursor: pointer;
		display: block;
		background: none;
		border: 1px solid #c2c2c2;
		color: #e55d15;
	}
	.caja-add-opinion{
		max-height: 90px;
		min-height: 90px;
		max-width: 385px;
		min-width: 385px;
		margin: 5px 0px 5px 0px;
		color: #353535;
		background: #f5f5f5;
		width: 100%;
		padding: 5px;
	}
	.content-stars .img{
		cursor: pointer;
		transition: 0.3s all ease;
	}
</style>

<section class="modal-opinion" id="modal-add-opinion" onclick="modal('modal-add-opinion')" style="display: none;">
	<div class="content-modal" onclick="modal('modal-add-opinion')">
		<strong class="title">OUYA</strong>
		<div class="content-opinion">
			<input type="text" id="id-buy-opinion" readonly hidden>
			<input type="text" id="num_stars" readonly hidden>
			<div class="content-stars" id='content-stars'></div>
			<textarea placeholder="Escribe tú opinion" class="caja-add-opinion" id='opinion'></textarea>
			<input type="button" class="btn-add-opinion" value="Guardar opinion" onclick="addOpinion()">
		</div>
	</div>
</section>