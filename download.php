<?php

	include("conexion.php");
	session_start();

	if(isset($_SESSION['gov']['id'])){
		if(isset($_GET['id'])){
			$rs=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_SESSION['gov']['id']."' AND id_product='".$_GET['id']."'");
			while($row=mysqli_fetch_array($rs)){
				$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row['id_product']."'");
				while($row_product=mysqli_fetch_array($rs_product)){
					//$title=$row_product['file'];
					//$extension=pathinfo($row_product['file'],PATHINFO_EXTENSION);
					$ruta=str_replace(" ","-",$row_product['file']);
					$file=str_replace("upload/products/".$row_product['id']."/","",$ruta);
					header('Content-Type: application/force-download');
				   	header('Content-Disposition: attachment; filename='.$file);
				   	header('Content-Transfer-Encoding: binary');
				   	header('Content-Length: '.filesize($ruta));
					return readfile($ruta);
				}
			}
		}else{
			return header("location:index.php");	
		}
		return header("location:index.php");
	}else{
		return header("location:login.php");
	}

?>