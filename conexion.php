<?php

	$conexion=mysqli_connect("localhost","root","") or die ("Error en servidor");
	mysqli_select_db($conexion,"gov") or die ("Error en db");

	function getInfoUser($id,$conexion){
		$rs_info=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$id."'") or die ("ERROR EN SERVIDOR");
		while($row_info=mysqli_fetch_array($rs_info)){
			$_SESSION['gov']=array(
				'id'=>$row_info['id'],
				'user'=>$row_info['user'],
				'name'=>$row_info['name'],
				'surname'=>$row_info['surname'],
				'email'=>$row_info['email'],
				'phone'=>$row_info['phone'],
				'sexo'=>$row_info['sexo'],
				'birth'=>$row_info['birth'],
				'img'=>$row_info['img'],
				'cp'=>$row_info['cp'],
				'date_register'=>$row_info['date_register'],
				'active'=>$row_info['active'],
				'admin'=>$row_info['admin'],
				'locked'=>$row_info['locked'],
				'fullname'=>$row_info['name']." ".$row_info['surname']
			);
		}
	}
	function getCodePostal($tipo,$code,$conexion){
		$rs_code_potal=mysqli_query($conexion,"SELECT * FROM postal_code WHERE cp='".$code."'");
		while($row_code_postal=mysqli_fetch_array($rs_code_potal)){
			if($tipo=='colonia'){
				$cp=utf8_encode($row_code_postal['colonia']);
			}
			if($tipo=='municipio'){
				$cp=utf8_encode($row_code_postal['municipio']);
			}
			if($tipo=='estado'){
				$cp=utf8_encode($row_code_postal['estado']);
			}
			return $cp;
		}
	}

	function total_pago_cart($conexion,$format){
		$total=0;
		$rs_cart=mysqli_query($conexion,"SELECT * FROM cart WHERE id_user='".$_SESSION['gov']['id']."' AND format='".$format."'");
		while($row_cart=mysqli_fetch_array($rs_cart)){
			$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_cart['id_product']."'");
			while($row_product=mysqli_fetch_array($rs_product)){
				$total+=$row_product['price']*$row_cart['quantity'];
			}
		}
		return $total;
	}

	function download($conexion,$id_product){
		$rs=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_SESSION['gov']['id']."' AND id_product='".$id_product."'");
		while($row=mysqli_fetch_array($rs)){
			$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row['id_product']."'");
			while($row_product=mysqli_fetch_array($rs_product)){
				if($row_product['format']=='Digital'){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}

?>