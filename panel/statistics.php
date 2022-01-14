<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/statistics.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php
	include("header.php");

	$rs_product=mysqli_query($conexion,"SELECT * FROM products ORDER BY sold DESC LIMIT 5");
	echo "<h3>MÁS VENDIDOS<h3><hr><br><section class='products'>";
	while($row_product=mysqli_fetch_array($rs_product)){
		$img=explode("&",$row_product['img']);
		echo "<div class='product'>
			<div class='content-img'>
				<img src='../".$img[0]."' width='100%'>
			</div>
			<div class='title-product'>".$row_product['title']."</div>
			<div class='price'>$".number_format($row_product['price'])." MXN</div>
			<div class='sold'>Vendidos: ".$row_product['sold']."</div>
		</div>";
	}
	echo "</section>";

	$rs_product=mysqli_query($conexion,"SELECT * FROM products ORDER BY views DESC LIMIT 5");
	echo "<h3>MÁS VISITADOS<h3><hr><br><section class='products'>";
	while($row_product=mysqli_fetch_array($rs_product)){
		$img=explode("&",$row_product['img']);
		echo "<div class='product'>
			<div class='content-img'>
				<img src='../".$img[0]."' width='100%>
			</div>
			<div class='title-product'>".$row_product['title']."</div>
			<div class='price'>$".number_format($row_product['price'])." MXN</div>
			<div class='sold'>Visitas: ".$row_product['views']."</div>
		</div>";
	}
	echo "</section>";

	$rs_product=mysqli_query($conexion,"SELECT * FROM products ORDER BY stars DESC LIMIT 5");
	echo "<h3>MEJOR CALIFICADOS<h3><hr><br><section class='products'>";
	while($row_product=mysqli_fetch_array($rs_product)){
		$img=explode("&",$row_product['img']);
		echo "<div class='product'>
			<div class='content-img'>
				<img src='../".$img[0]."' width='100%>
			</div>
			<div class='title-product'>".$row_product['title']."</div>
			<div class='price'>$".number_format($row_product['price'])." MXN</div>
			<div class='sold'>";
				for($a=1; $a<=5; $a++){if($a<=$row_product['stars']){echo "<img src='../img/estrella.png' width='20px' height='20px'>";}else{echo "<img src='../img/estrella_vacia.png' width='20px' height='20px'>";}}
			echo "</div>
		</div>";
	}
	echo "</section>";


	echo "<h3>USUARIOS CON MÁS COMPRAS<h3><hr><br><section class='products'>";
		$rs_data_buys=mysqli_query($conexion,"SELECT * FROM data_buys ORDER BY sold DESC LIMIT 5");
		while($row_data_buys=mysqli_fetch_array($rs_data_buys)){
			$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_data_buys['id_user']."'");
			while($row_user=mysqli_fetch_array($rs_user)){
				echo "<div class='product'>
					<div class='content-img'>
						<img src='../".$row_user['img']."' width='100%>
					</div>
					<div class='title-product'>".$row_user['name']."</div>
					<div class='sold'>".$row_data_buys['sold']." productos vendidos</div>
				</div>";
			}
		}
	echo "</section>";

	echo "<h3>USUARIOS CON MÁS DINERO GASTADO<h3><hr><br><section class='products'>";
		$rs_data_buys=mysqli_query($conexion,"SELECT * FROM data_buys ORDER BY payment DESC LIMIT 5");
		while($row_data_buys=mysqli_fetch_array($rs_data_buys)){
			$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_data_buys['id_user']."'");
			while($row_user=mysqli_fetch_array($rs_user)){
				echo "<div class='product'>
					<div class='content-img'>
						<img src='../".$row_user['img']."'>
					</div>
					<div class='title-product'>".$row_user['name']."</div>
					<div class='sold'>$".number_format($row_data_buys['payment'])." MXN gastado</div>
				</div>";
			}
		}
	echo "</section>";

?>

</body>
</html>