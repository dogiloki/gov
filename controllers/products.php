<?php

	include("../conexion.php");
	session_start();

	if(isset($_GET['cantidad'])){
		if($_GET['cantidad']=='update' && isset($_POST['id_cart'])){
			mysqli_query($conexion,"UPDATE cart SET quantity='".$_POST['canti_actual']."' WHERE id='".$_POST['id_cart']."'") or die ("ERROR EN SERVIDOR");
			return 0;
		}
		$rs_cantidad=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id_product']."'");
		while($row_cantidad=mysqli_fetch_array($rs_cantidad)){
			$canti_actual=intval($_POST['canti_actual']);
			if($row_cantidad['quantity']!=$canti_actual){
				if($_GET['cantidad']=='mas'){
					echo $canti_actual+1;
					return 0;
				}
				if($_GET['cantidad']=='menos' && $canti_actual!=1){
					echo $canti_actual-1;
					return 0;
				}else{
					echo $canti_actual;
					return 0;
				}
			}else{
				if($_GET['cantidad']=='menos' && $canti_actual!=1){
					echo $canti_actual-1;
					return 0;
				}else{
					echo $canti_actual;
					return 0;
				}
			}
		}
	}
	if(isset($_GET['cart_color'])){
		if($_GET['cart_color']=='cambiar'){
			mysqli_query($conexion,"UPDATE cart SET color='".$_POST['color']."' WHERE id='".$_POST['id_cart']."'") or die ("ERROR EN SERVIDOR");
			return 0;
		}
	}

	if(!isset($_SESSION['gov']['id'])){
		if(isset($_GET['question'])){
			return header("location:../login.php");
		}else{
			echo "login";
			return 0;
		}
	}

	//Preguntas
	if(isset($_GET['question'])){
		if($_GET['question']=='add'){
			mysqli_query($conexion,"INSERT INTO questions VALUES ('".uniqid()."','".$_SESSION['gov']['id']."','".$_GET['id_product']."','".$_POST['text']."','No hay respuesta',now())") or die ("ERROR EN EL SERVIDOR");
			return header("location:../product.php?id=".$_GET['id_product']);
		}
	}


	//Historial
	if(isset($_GET['record'])){
		if($_GET['record']=='remove'){
			mysqli_query($conexion,"DELETE FROM record WHERE id_user='".$_SESSION['gov']['id']."' AND id_product='".$_POST['id_product']."'") or die ("ERROR EN EL SERVIDOR");
			return 0;
		}
		if($_GET['record']=='remove_all'){
			mysqli_query($conexion,"DELETE FROM record WHERE id_user='".$_SESSION['gov']['id']."'") or die ("ERROR EN EL SERVIDOR");
			echo "HISTORIAL VACIADO";
			return 0;
		}
	}

	//Carrito
	if(isset($_GET['cart'])){
		if($_GET['cart']=='add'){
			$rs_cart=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id_product']."'");
			while($row_cart=mysqli_fetch_array($rs_cart)){
				$color=explode("&",$row_cart['color']);
				$format=$row_cart['format'];
			}
			mysqli_query($conexion,"INSERT INTO cart VALUES ('".uniqid()."','".$_SESSION['gov']['id']."','".$_POST['id_product']."','1','".$color[0]."','".$format."',now())") or die ("ERROR EN EL SERVIDOR");
			if($_POST['location']=='product'){
				echo "Quitar del carrito";
			}else{
				echo "QUITAR CARRITO";
			}
			return 0;
		}
		if($_GET['cart']=='remove'){
			mysqli_query($conexion,"DELETE FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND id_product='".$_POST['id_product']."'") or die ("ERROR EN EL SERVIDOR");
			if($_POST['location']=='product'){
				echo "Añadir al carrito";
			}else{
				echo "CARRITO";
			}
			return 0;
		}
		if($_GET['cart']=='remove_all'){
			mysqli_query($conexion,"DELETE FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$_GET['format']."'") or die ("ERROR EN EL SERVIDOR");
			return header("location:../cart.php");
		}
		if($_GET['cart']=='calcular'){
			$total=0;
			$rs_cart_calcular=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$_GET['format']."'");
			$cantidad=mysqli_num_rows($rs_cart_calcular);
			while($row_cart_calcular=mysqli_fetch_array($rs_cart_calcular)){
				$rs_product_calcular=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_cart_calcular['id_product']."'");
				while($row_product_calcular=mysqli_fetch_array($rs_product_calcular)){
					$total+=$row_product_calcular['price']*$row_cart_calcular['quantity'];
				}
			}
			$datos[0]=$cantidad;
			$datos[1]=number_format($total);
			echo json_encode($datos);
			return 0;
		}
	}


?>