<?php

include('../conexion.php');
session_start();

if(!isset($_SESSION['gov']['id'])){
	echo "login";
	return 0;
}

if(isset($_GET['v'])){
	if($_GET['v']=='very_compra'){
		if($_POST['id_product']!='cart'){
			$rs_very=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id_product']."'");
			while($row_very=mysqli_fetch_array($rs_very)){
				if($_POST['cantidad']<=0 || $_POST['cantidad']>$row_very['quantity']){
					echo "CANTIDAD DE PRODUCTOS NO DISPONIBLES:<br>".$row_very['title'];
					return 0;
				}
				if($row_very['quantity']<=0){
					echo "PRODUCTO NO DISPONIBLE:<br>".$row_very['title'];
					return 0;
				}
			}
		}else{
			$rs_very_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$_POST['format']."'");
			while($row_very_cart=mysqli_fetch_array($rs_very_cart)){
				$rs_very=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_very_cart['id_product']."'");
				while($row_very=mysqli_fetch_array($rs_very)){
					if($row_very_cart['quantity']<=0 || $row_very_cart['quantity']>$row_very['quantity']){
						echo "CANTIDAD DE PRODUCTOS NO DISPONIBLES:<br>".$row_very['title'];
						return 0;
					}
					if($row_very['quantity']<=0){
						echo "PRODUCTO NO DISPONIBLE:<br>".$row_very['title'];
						return 0;
					}
				}
			}
		}
		echo "CORRECTO";
		return 0;
	}
	if($_GET['v']=='comprar'){
		if($_POST['id_product']=='cart'){
			$ids="";
			$rs_comprar_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$_POST['format']."'");
			while($row_comprar_cart=mysqli_fetch_array($rs_comprar_cart)){
				$id=uniqid();
				if($_POST['format']=='Digital'){
					mysqli_query($conexion,"UPDATE products SET quantity=quantity-1, sold=sold+1 WHERE id='".$row_comprar_cart['id_product']."'") or die ("ERROR AL ALMACENAR LA COMPRA");
				}else{
					mysqli_query($conexion,"UPDATE products SET quantity=quantity-".$row_comprar_cart['quantity'].", sold=sold+".$row_comprar_cart['quantity']." WHERE id='".$row_comprar_cart['id_product']."'") or die ("ERROR AL ALMACENAR LA COMPRA");
				}
				$rs_comprar=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_comprar_cart['id_product']."'");
				while($row_comprar=mysqli_fetch_array($rs_comprar)){
					$pago=$row_comprar['price'];
					$title=$row_comprar['title'];
					if($row_comprar['quantity']<=0){
						mysqli_query($conexion,"DELETE FROM record WHERE id_product='".$row_comprar_cart['id_product']."'") or die ("ERROR AL MODIFICAR HISTORIAL Y CARRITO 0 PRODUCTOS");
						mysqli_query($conexion,"DELETE FROM cart WHERE id_product='".$row_comprar_cart['id_product']."' AND format='".$_POST['format']."'") or die ("ERROR AL MODIFICAR HISTORIAL Y CARRITO 0 PRODUCTOS");
					}
					mysqli_query($conexion,"INSERT INTO buys VALUES ('".$id."','".$_SESSION['gov']['id']."','".$row_comprar_cart['id_product']."','".$title."','".$row_comprar_cart['quantity']."','".$row_comprar_cart['color']."','".$pago."',0,'".$pago*$row_comprar_cart['quantity']."','".$_POST['name_recibe']."','".$_POST['direccion']."','".$_POST['phone']."','".$_POST['referencias']."','".$_POST['cp']."','".$_POST['format']."',now())") or die ("ERROR AL ALMACENAR LA COMPRA");
					if(mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM data_buys WHERE id_user='".$_SESSION['gov']['id']."'"))==0){
						mysqli_query($conexion,"INSERT INTO data_buys VALUES ('".$_SESSION['gov']['id']."',".$row_comprar_cart['quantity'].",".$pago*$row_comprar_cart['quantity'].")");
					}else{
						mysqli_query($conexion,"UPDATE data_buys SET sold=data_buys.sold+".$row_comprar_cart['quantity'].", payment=data_buys.payment+".$pago*$row_comprar_cart['quantity']." WHERE id_user='".$_SESSION['gov']['id']."'");
					}
				}
				$ids.=$id."&";
			}
			mysqli_query($conexion,"DELETE FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$_POST['format']."'") or die ("ERROR AL ELIMINAR CARRITO, PERO COMPRADO");
			$id_buy_cart=uniqid();
			mysqli_query($conexion,"INSERT INTO buys_cart VALUES ('".$id_buy_cart."','".$_SESSION['gov']['id']."','".$ids."','".$_POST['format']."')") or die ("ERROR AL TICKER DE CARRITO, PERO COMPRADO");
			echo $id_buy_cart;
			return 0;
		}else{
			$id=uniqid();
			if($_POST['format']=='Digital'){
				mysqli_query($conexion,"UPDATE products SET quantity=quantity-1, sold=sold+1 WHERE id='".$_POST['id_product']."'") or die ("ERROR AL ALMACENAR LA COMPRA");
			}else{
				mysqli_query($conexion,"UPDATE products SET quantity=quantity-".$_POST['cantidad'].", sold=sold+".$_POST['cantidad']." WHERE id='".$_POST['id_product']."'") or die ("ERROR AL ALMACENAR LA COMPRA");
			}
			$rs_comprar=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id_product']."'");
			while($row_comprar=mysqli_fetch_array($rs_comprar)){
				$pago=$row_comprar['price'];
				$title=$row_comprar['title'];
				if($row_comprar['quantity']<=0){
					mysqli_query($conexion,"DELETE FROM record WHERE id_product='".$row_comprar['id']."'") or die ("ERROR AL MODIFICAR HISTORIAL Y CARRITO 0 PRODUCTOS");
					mysqli_query($conexion,"DELETE FROM cart WHERE id_product='".$row_comprar['id']."' AND format='".$_POST['format']."'") or die ("ERROR AL MODIFICAR HISTORIAL Y CARRITO 0 PRODUCTOS");
				}
			}
			mysqli_query($conexion,"INSERT INTO buys VALUES ('".$id."','".$_SESSION['gov']['id']."','".$_POST['id_product']."','".$title."','".$_POST['cantidad']."','".$_POST['color']."','".$pago."',0,'".$_POST['total']."','".$_POST['name_recibe']."','".$_POST['direccion']."','".$_POST['phone']."','".$_POST['referencias']."','".$_POST['cp']."','".$_POST['format']."',now())") or die ("ERROR AL ALMACENAR LA COMPRA");
			if(mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM data_buys WHERE id_user='".$_SESSION['gov']['id']."'"))==0){
				mysqli_query($conexion,"INSERT INTO data_buys VALUES ('".$_SESSION['gov']['id']."',".$_POST['cantidad'].",".$_POST['total'].")");
			}else{
				mysqli_query($conexion,"UPDATE data_buys SET sold=data_buys.sold+".$_POST['cantidad'].", payment=data_buys.payment+".$_POST['total']." WHERE id_user='".$_SESSION['gov']['id']."'");
			}
			echo $id;
			return 0;
		}
	}
	if($_GET['v']=='get_opinion'){
		$rs=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_SESSION['gov']['id']."' AND id='".$_POST['id_buy']."'");
		while($row=mysqli_fetch_array($rs)){
			$rs_opinion=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."' AND id_user='".$_SESSION['gov']['id']."'");
			while($row_opinion=mysqli_fetch_array($rs_opinion)){
				$datos[0]=$row_opinion['opinion'];
				$datos[1]=$row_opinion['stars'];
				echo json_encode($datos);
				return 0;
			}
		}
		$datos[0]='';
		$datos[1]=1;
		echo json_encode($datos);
		return 0;
	}
	if($_GET['v']=='add_opinion'){
		if($_POST['opinion']==""){
			$opinion="Sin opinión";
		}else{
			$opinion=$_POST['opinion'];
		}
		$rs=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_SESSION['gov']['id']."' AND id='".$_POST['id_buy']."'");
		while($row=mysqli_fetch_array($rs)){
			$rs_opinion=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."' AND id_user='".$_SESSION['gov']['id']."'");
			while($row_opinion=mysqli_fetch_array($rs_opinion)){
				mysqli_query($conexion,"UPDATE opinions SET opinion='".$opinion."', stars='".$_POST['stars']."' WHERE id_product='".$row['id_product']."' AND id_user='".$_SESSION['gov']['id']."'") or die ("index");
				$rs_opinion2=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."'");
				$stars=0;
				while($row_opinion2=mysqli_fetch_array($rs_opinion2)){
					$stars+=$row_opinion2['stars'];
				}
				mysqli_query($conexion,"UPDATE products SET stars='".($stars/mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."'")))."' WHERE id='".$row['id_product']."'");
				echo $row_opinion['opinion'];
				return 0;
			}
			mysqli_query($conexion,"INSERT INTO opinions VALUES ('".uniqid()."','".$_SESSION['gov']['id']."','".$row['id_product']."','".$_POST['stars']."','".$opinion."',now())") or die ("index");
			$rs_opinion2=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."'");
			$stars=0;
			while($row_opinion2=mysqli_fetch_array($rs_opinion2)){
				$stars+=$row_opinion2['stars'];
			}
			mysqli_query($conexion,"UPDATE products SET stars='".($stars/mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$row['id_product']."'")))."' WHERE id='".$row['id_product']."'");
			echo $opinion;
			return 0;
		}
		echo "index";
		return 0;
	}
	if($_GET['v']=='delete_opinion'){
		mysqli_query($conexion,"DELETE FROM opinions WHERE id_product='".$row['id_product']."' AND id_user='".$_SESSION['gov']['id']."'") or die ("index");
	}
}

?>