<?php

	include("../../conexion.php");
	session_start();
	if(!isset($_SESSION['gov']['id']) && $_SESSION['gov']['admin']==0){
		echo "<script>window.location='../index.php';</script>";
		return 0;
	}

	if($_GET['v']=='get-update'){
		$rs_update=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
		while($row_update=mysqli_fetch_array($rs_update)){
			if($_GET['op']=='user'){
				$datos['img_user']=$row_update['img'];
				$datos['name_user']=$row_update['name'];
				echo json_encode($datos);
				return 0;
			}
			if($_GET['op']=='opinion'){
				$rs_opinion=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_user='".$_POST['id']."' ORDER BY date DESC");
				$datos['opinion']="NO HAY OPINIONES";
				while($row_opinion=mysqli_fetch_array($rs_opinion)){
					$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_opinion['id_product']."'");
					$title_product='';
					$id_product='';
					while($row_product=mysqli_fetch_array($rs_product)){
						$title_product=$row_product['title'];
						$id_product=$row_product['id'];
					}
					if($datos['opinion']=="NO HAY OPINIONES"){
						$datos['opinion']="";
					}
					$stars="";
					for($s=1; $s<=5; $s++){
						if($s<=$row_opinion['stars']){
							$stars.="<img src='../img/estrella.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
						}else{
							$stars.="<img src='../img/estrella_vacia.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
						}
					}
					$rs_opinion_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_opinion['id_user']."'");
					while($row_opinion_user=mysqli_fetch_array($rs_opinion_user)){
						if($title_product=='' || $id_product==''){
							$datos['opinion'].="<div class='opinion' id='opinion".$row_opinion['id']."'>
								<div style='display: flex; align-items: center;'>
									<div class='content-img-user'>
										<img class='img-user' src='../".$row_opinion_user['img']."'>
									</div>
									<strong class='name-user'>".$row_opinion_user['name']."</strong>".$stars."
								</div>
								<div class='text-opinion'>".$row_opinion['opinion']."</div>
								<div>
									<a class='btn-delete' onclick=\"delete_opinion('".$row_opinion['id']."')\">ELIMINAR</a>
									<a class='btn-respond2'>PRODUCTO ELIMINADO</a>
								</div>
							</div>";
						}else{
							$datos['opinion'].="<div class='opinion' id='opinion".$row_opinion['id']."'>
								<div style='display: flex; align-items: center;'>
									<div class='content-img-user'>
										<img class='img-user' src='../".$row_opinion_user['img']."'>
									</div>
									<strong class='name-user'>".$row_opinion_user['name']."</strong>".$stars."
								</div>
								<div class='text-opinion'>".$row_opinion['opinion']."</div>
								<div>
									<a class='btn-delete' onclick=\"delete_opinion('".$row_opinion['id']."')\">ELIMINAR</a>
									<a class='btn-respond' href='../product.php?id=".$id_product."' target='_blank'>".$title_product."</a>
								</div>
							</div>";
						}
					}
				}
				echo json_encode($datos);
				return 0;
			}
			if($_GET['op']=='question'){
				$rs_question=mysqli_query($conexion,"SELECT * FROM questions WHERE id_user='".$_POST['id']."' ORDER BY date DESC");
				$datos['question']="NO HAY PREGUNTAS";
				while($row_question=mysqli_fetch_array($rs_question)){
					$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_question['id_product']."'");
					$title_product='';
					$id_product='';
					while($row_product=mysqli_fetch_array($rs_product)){
						$title_product=$row_product['title'];
						$id_product=$row_product['id'];
					}
					if($datos['question']=="NO HAY PREGUNTAS"){
						$datos['question']="";
					}
					if($row_question['answer']=="" || $row_question['answer']=="No hay respuesta"){
						$answer="RESPONDER";
					}else{
						$answer="ACTUALIZAR RESPONDER";
					}
					$rs_question_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_question['id_user']."'") or die ("error");
					while($row_question_user=mysqli_fetch_array($rs_question_user)){
						if($title_product=='' || $id_product==''){
							$datos['question'].="<div class='opinion' id='question".$row_question['id']."'>
								<div style='display: flex; align-items: center;'>
									<div class='content-img-user'>
										<img class='img-user' src='../".$row_question_user['img']."'>
									</div>
									<strong class='name-user'>".$row_question_user['name']."</strong>
								</div>
								<div class='text-opinion'>".$row_question['question']."</div>
								<div>
									<a class='btn-delete' onclick=\"delete_question('".$row_question['id']."')\">ELIMINAR</a>
									<a class='btn-respond' id='btn-respond".$row_question['id']."' onclick=\"info_answer('".$row_question['id']."')\">".$answer."</a>
								</div>
							</div>";
						}else{
							$datos['question'].="<div class='opinion' id='question".$row_question['id']."'>
								<div style='display: flex; align-items: center;'>
									<div class='content-img-user'>
										<img class='img-user' src='../".$row_question_user['img']."'>
									</div>
									<strong class='name-user'>".$row_question_user['name']."</strong>
								</div>
								<div class='text-opinion'>".$row_question['question']."</div>
								<div>
									<a class='btn-delete' onclick=\"delete_question('".$row_question['id']."')\">ELIMINAR</a>
									<a class='btn-respond' id='btn-respond".$row_question['id']."' onclick=\"info_answer('".$row_question['id']."')\">".$answer."</a>
									<a class='btn-respond' href='../product.php?id=".$id_product."' target='_blank'>".$title_product."</a>
								</div>
							</div>";
						}
					}
				}
				echo json_encode($datos);
				return 0;
			}
			if($_GET['op']=='compras'){
				$rs_buys=mysqli_query($conexion,"SELECT * FROM buys WHERE id_user='".$_POST['id']."' ORDER BY date DESC");
				$datos['compras']="NO HAY COMPRAS";
				while($row_buy=mysqli_fetch_array($rs_buys)){
					if($datos['compras']=="NO HAY COMPRAS"){
						$rs_data_buys=mysqli_query($conexion,"SELECT * FROM data_buys WHERE id_user='".$row_buy['id_user']."'");
						while($row_data_buys=mysqli_fetch_array($rs_data_buys)){
							$datos['compras']="<div style='color: #88c4ff; font-size: 16px;'>".$row_data_buys['sold']." PRODUCTOS COMPRADOS - $".number_format($row_data_buys['payment'])." MXN GASTADOS</div>";
						}
					}
					$rs_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$row_buy['id_product']."'");
					while($row_product=mysqli_fetch_array($rs_product)){
						$datos['compras'].="<div class='compra' id='compra".$row_product['id']."'>
								<div style='display: flex; align-items: center; color: #75a800; margin: 5px;'>
									<strong class='title-compra'>".$row_buy['title']."</strong>
								</div>
								<div class='content-text'>FORMATO: ".$row_buy['format']."</div>
								<div class='content-text'>CANTIDAD: ".$row_buy['quantity']."</div>
								<div class='content-text'>PRECIO: $".number_format($row_buy['payment'])."</div>
								<div class='content-text'>TOTAL: $".number_format($row_buy['total'])."</div>
								<div class='content-text'>".$row_buy['date']."</div>
								<div>
									<a class='btn-respond' id='btn-respond' href='ticker.php?id=".$row_buy['id']."' target='_blank'>TICKER</a>
									<a class='btn-respond' href='../product.php?id=".$row_product['id']."' target='_blank'>VER PRODUCTO</a>
								</div>
							</div>";
					}
				}
				echo json_encode($datos);
				return 0;
			}
			if($_GET['op']=='mas'){
				if($row_update['locked']=='1'){
					$datos['mas']="<div>
						<strong style='width: 100%; font-size: 17px;'>BLOQUEO DE CUENTA</strong><br>
						<input class='btn-mas' id='btn-locked0' type='button' value='DESACTIVADO' onclick=\"update_mas('locked','0')\">
						<input class='btn-mas' id='btn-locked1' style='background: #2e82ff' type='button' value='ACTIVADO' onclick=\"update_mas('locked','1')\">
					</div>";
				}else{
					$datos['mas']="<div>
						<strong style='width: 100%; font-size: 17px;'>BLOQUEO DE CUENTA</strong><br>
						<input class='btn-mas' id='btn-locked0' style='background: #ff6d5c' type='button' value='DESACTIVADO' onclick=\"update_mas('locked','0')\">
						<input class='btn-mas' id='btn-locked1' type='button' value='ACTIVADO' onclick=\"update_mas('locked','1')\">
					</div>";
				}
				if($row_update['admin']=='1'){
					$datos['mas'].="<br><hr><br><div>
						<strong style='width: 100%; font-size: 17px;'>ADMINISTRADOR</strong><br>
						<input class='btn-mas' id='btn-admin0' type='button' value='DESACTIVADO' onclick=\"update_mas('admin','0')\">
						<input class='btn-mas' id='btn-admin1' style='background: #2e82ff' type='button' value='ACTIVADO' onclick=\"update_mas('admin','1')\">
					</div>";
				}else{
					$datos['mas'].="<br><hr><br><div>
						<strong style='width: 100%; font-size: 17px;'>ADMINISTRADOR</strong><br>
						<input class='btn-mas' id='btn-admin0' style='background: #ff6d5c' type='button' value='DESACTIVADO' onclick=\"update_mas('admin','0')\">
						<input class='btn-mas' id='btn-admin1' type='button' value='ACTIVADO' onclick=\"update_mas('admin','1')\">
					</div>";
				}
				echo json_encode($datos);
				return 0;
			}
		}
	}

	if($_GET['v']=='delete-opinion'){
		mysqli_query($conexion,"DELETE FROM opinions WHERE id='".$_POST['id']."'") or die ("error");
		return 0;
	}
	if($_GET['v']=='info-answer'){
		$rs_info_question=mysqli_query($conexion,"SELECT * FROM questions WHERE id='".$_POST['id']."'") or die ("error");
		while($row_info_question=mysqli_fetch_array($rs_info_question)){
			if($row_info_question['answer']=="No hay respuesta" || $row_info_question['answer']==""){
				$answer="RESPONDER";
			}else{
				$answer="ACTUALIZAR RESPUESTA";
			}
			$datos['question']=$row_info_question['question'];
			$datos['answer']=$row_info_question['answer'];
			$datos['btn_answer']="<input type='button' class='btn' id='btn' value='".$answer."' onclick=\"add_answer('".$row_info_question['id']."')\">";
		}
		echo json_encode($datos,true);
		return 0;
	}
	if($_GET['v']=='add-answer'){
		if($_POST['answer']==""){
			mysqli_query($conexion,"UPDATE questions SET answer='No hay respuesta' WHERE id='".$_POST['id']."'") or die ("error");
		}else{
			mysqli_query($conexion,"UPDATE questions SET answer='".$_POST['answer']."' WHERE id='".$_POST['id']."'") or die ("error");
		}
		return 0;
	}
	if($_GET['v']=='delete-question'){
		mysqli_query($conexion,"DELETE FROM questions WHERE id='".$_POST['id']."'") or die ("error");
		return 0;
	}

	if($_GET['v']=='locked'){
		mysqli_query($conexion,"UPDATE users SET locked='".$_POST['op']."' WHERE id='".$_POST['id']."'") or die ("error");
		return 0;
	}

	if($_GET['v']=='admin'){
		mysqli_query($conexion,"UPDATE users SET admin='".$_POST['op']."' WHERE id='".$_POST['id']."'") or die ("error");
		return 0;
	}

?>