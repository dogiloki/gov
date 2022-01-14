<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/ticker.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php
	include("header.php");
	if(!isset($_SESSION['gov']['id']) || !isset($_GET['id'])){
		return header("location:index.php");
	}

	$rs_cart=mysqli_query($conexion,"SELECT * FROM buys_cart WHERE id='".$_GET['id']."' AND id_user='".$_SESSION['gov']['id']."'");
	while($row_cart=mysqli_fetch_array($rs_cart)){
		$id_buy=explode("&",$row_cart['id_buys']);
		$num_buy=sizeof($id_buy)-1;
		$contador=0;
		$total_pago=0;
		for($b=0; $b<sizeof($id_buy)-1; $b++){
			$rs_buy=mysqli_query($conexion,"SELECT * FROM buys WHERE id='".$id_buy[$b]."'");
			while($row_buy=mysqli_fetch_array($rs_buy)){
				$contador++;
				$total_pago+=$row_buy['payment']*$row_buy['quantity'];
				if($contador==1){
					?>
					<section class='content' onclick="window.location='../controllers/pdf.php?id=<?php echo $_GET['id']; ?>'" title="Descargar">
					<?php
						echo "<div class='content-ticker'>
								<div class='content-logo'>
									<img src='img/logo.png' width='75px' height='50px' class='logo'>
									<div class='title'>
										<span style='color: #2e82ff;'>GENESIS</span>
										<span style='color: #e55d15;'>OF</span>
										<span style='color: #75a800;'>VIDEOGAMES</span>
									</div>
								</div>
						<center>geofvi.xyz</center><br><hr>";
						if($row_buy['format']=='Digital'){
							echo "<div class='content-datos'>
								<strong>DATOS DE ENTREGA</strong><br>
								<div class='datos' style='margin-top: 5px;'>
									Formato: ".$row_buy['format']."
								</div>
								<div class='datos' style='margin-top: 5px;'>
									Fecha: ".$row_buy['date']."
								</div>
							</div>";
						}else{
							echo "<div class='content-datos'>
								<strong>DATOS DE ENTREGA</strong><br>
								<div class='datos' style='margin-top: 5px;'>
									Formato: ".$row_buy['format']."
								</div>
								<div class='datos' style='margin-top: 5px;'>
									Fecha: ".$row_buy['date']." - Telefono: ".$row_buy['phone']."
								</div>
								<div class='datos'>
									Dirección: ".$row_buy['direccion']." - cp: ".$row_buy['cp']."
								</div>
								<div class='datos'>
									Colonia: ".getCodePostal('colonia',$row_buy['cp'],$conexion)."
								</div>
								<div class='datos'>
									Municipio: ".getCodePostal('municipio',$row_buy['cp'],$conexion)."
								</div>
								<div class='datos'>
									Estado: ".getCodePostal('estado',$row_buy['cp'],$conexion)."
								</div>
								<div class='datos'>
									Referencias: ".$row_buy['referencias']."
								</div>
							</div>";
						}
						echo "<div class='content-datos'><hr><br>
						<strong>PRODUCTOS COMPRADOS</strong><br>
						<table width='100%' style='margin-top: 10px;'>
							<tr>
								<th align='left'>Producto</th>
								<th align='left' style='width: 100px;'>Cantidad</th>
								<th align='left' style='width: 100px;'>Precio</th>
								<th align='left' style='width: 100px;'>Precio envío</th>
								<th align='left' style='width: 100px;'>Sub Total</th>
							</tr>";
				}
				echo "<tr>
						<td>".$row_buy['title']."</td>
						<td>".$row_buy['quantity']."</td>
						<td>$".number_format($row_buy['payment'])."</td>
						<td>$".number_format($row_buy['shipping'])."</td>
						<td>$".number_format($row_buy['total'])."</td>
					</tr>";
				if($contador==$num_buy){
					echo "</table>
						<strong class='datos'>
							Total de pago: $".number_format($total_pago)."
						</strong>
					</div></div></section>";
				}
			}
		}
	}
if(!isset($num_buy)){
	$id_buy=$_GET['id'];
	$rs_buy=mysqli_query($conexion,"SELECT * FROM buys WHERE id='".$id_buy."'");
	while($row_buy=mysqli_fetch_array($rs_buy)){
		?>
		<section class='content' onclick="window.location='../controllers/pdf.php?id=<?php echo $_GET['id']; ?>'" title="Descargar">
		<?php
		echo "<div class='content-ticker'>
					<div class='content-logo'>
						<img src='img/logo.png' width='75px' height='50px' class='logo'>
							<div class='title'>
								<span style='color: #2e82ff;'>GENESIS</span>
								<span style='color: #e55d15;'>OF</span>
								<span style='color: #75a800;'>VIDEOGAMES</span>
							</div>
					</div>
					<center>geofvi.xyz</center><br><hr>";
					if($row_buy['format']=='Digital'){
						echo "<div class='content-datos'>
							<strong>DATOS DE ENTREGA</strong><br>
							<div class='datos' style='margin-top: 5px;'>
								Formato: ".$row_buy['format']."
							</div>
							<div class='datos' style='margin-top: 5px;'>
								Fecha: ".$row_buy['date']."
							</div>
						</div>";
					}else{
						echo "<div class='content-datos'>
							<strong>DATOS DE ENTREGA</strong><br>
							<div class='datos' style='margin-top: 5px;'>
								Formato: ".$row_buy['format']."
							</div>
							<div class='datos' style='margin-top: 5px;'>
								Fecha: ".$row_buy['date']." - Telefono: ".$row_buy['phone']."
							</div>
							<div class='datos'>
								Dirección: ".$row_buy['direccion']." - cp: ".$row_buy['cp']."
							</div>
							<div class='datos'>
								Colonia: ".getCodePostal('colonia',$row_buy['cp'],$conexion)."
							</div>
							<div class='datos'>
								Municipio: ".getCodePostal('municipio',$row_buy['cp'],$conexion)."
							</div>
							<div class='datos'>
								Estado: ".getCodePostal('estado',$row_buy['cp'],$conexion)."
							</div>
							<div class='datos'>
								Referencias: ".$row_buy['referencias']."
							</div>
						</div>";
					}
					echo "<div class='content-datos'><hr><br>
						<strong>PRODUCTOS COMPRADOS</strong><br>
						<table width='100%' style='margin-top: 10px;'>
							<tr>
								<th align='left'>Producto</th>
								<th align='left' style='width: 100px;'>Cantidad</th>
								<th align='left' style='width: 100px;'>Precio</th>
								<th align='left' style='width: 100px;'>Precio envío</th>
								<th align='left' style='width: 100px;'>Total</th>
							</tr>
							<tr>
								<td>".$row_buy['title']."</td>
								<td>".$row_buy['quantity']."</td>
								<td>$".number_format($row_buy['payment'])."</td>
								<td>$".number_format($row_buy['shipping'])."</td>
								<td>$".number_format($row_buy['total'])."</td>
							</tr>
						</table>
					</div>
				</div>
		</section>";
	}
}

?>

	

</body>
</html>