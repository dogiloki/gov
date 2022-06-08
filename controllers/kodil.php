<?php

	session_start();
	if(!isset($_SESSION['gov']['id'])){
		return header("location:index.php");
	}
	$conexion_kodil=mysqli_connect("localhost","root","") or die ("Error en servidor");
	mysqli_select_db($conexion_kodil,"kodil") or die ("Error en db");
	$datos=false;
	//$titular=$_POST['titular'];
	$num_tarjeta=$_POST['num_tarjeta'];
	$dia=str_replace(' ','',$_POST['dia']);
	$mes=str_replace(' ','',$_POST['mes']);
	if(strlen($dia)==1){
		$dia="0".$dia;
	}
	if(strlen($mes)==1){
		$mes="0".$mes;
	}
	$cvv=str_replace(' ','',$_POST['cvv']);
	$cantidad=$_POST['cantidad'];
	if($num_tarjeta=='' || $dia=='' || $mes=='' || $cvv=='' || $cantidad=='' || $num_tarjeta=='-'){
		echo "DATOS INCORRECTO EN MÉTODO DE PAGO";
		return 0;
	}
	$rs=mysqli_query($conexion_kodil,"SELECT * FROM debito WHERE bloqueada='1' AND cancelada='0' AND cvv!='-' AND vencimiento!='-'") or die ("ERROR EN SERVIDOR");
	$encontrado=false;
	while($row=mysqli_fetch_array($rs)){
		if($encontrado==false){
			if(str_replace(' ','',$num_tarjeta)==str_replace(' ','',$row['digitos']) && $row['vencimiento']==($dia."/".$mes) && $row['cvv']==$cvv){
				$encontrado=true;
				$rs_user=mysqli_query($conexion_kodil,"SELECT * FROM users WHERE account='".$row['account']."'") or die ("ERROR EN SERVIDOR");
				while($row_user=mysqli_fetch_array($rs_user)){
					if($row_user['saldo']>$cantidad){
						proceso($row['account'],$cantidad,$conexion_kodil);
					}else{
						echo "SALDO INSUFICIENTE";
						return 0;
					}
				}
			}
		}
	}
	if($encontrado==false){
		echo "DATOS INCORRECTO EN MÉTODO DE PAGO";
		return 0;
	}

	function proceso($account,$cantidad,$conexion_kodil){
		mysqli_query($conexion_kodil,"CALL enviar('','".$account."','Genesis Of Videogames (gov)',".$cantidad.")") or die ('ERROR EN SERVIDOR');
		mysqli_query($conexion_kodil,"INSERT INTO movimientos VALUES ('".uniqid()."','".$account."','Genesis Of Videogames (gov)','debito','".utf8_decode('Pago en tienda virtual GOV')."','".$cantidad."',now())") or die ("ERROR EN SERVIDOR");
		echo "COMPRA CORRECTA";
		return 0;
	}

?>