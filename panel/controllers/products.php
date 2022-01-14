<?php

include("../../conexion.php");
session_start();
if(!isset($_SESSION['gov']['id']) && $_SESSION['gov']['admin']==0){
	echo "<script>window.location='../index.php';</script>";
	return 0;
}

if(isset($_GET['v'])){
	if($_GET['v']=='add'){
		$id=uniqid();
		mysqli_query($conexion,"INSERT INTO products VALUES ('".$id."','".utf8_decode($_POST['title'])."','".$_POST['category']."','".$_POST['seccion']."',".$_POST['price'].",".$_POST['shipping'].",".$_POST['quantity'].",0,'".utf8_decode($_POST['description'])."','".$_POST['format']."','','".$_POST['color']."','".$_POST['status']."','img/logo.png',now(),0,0)") or die ("error");
		echo $id;
		return 0;
	}
	if($_GET['v']=='file'){
		$archivo=$_FILES['file'];
		$id=$_POST['id'];
   		$extension=pathinfo($archivo['name'],PATHINFO_EXTENSION);
   		$time=time();
   		if(!file_exists("../../upload/products/".$id)){
			mkdir("../../upload/products/".$id);
		}
		$ruta="upload/products/".$id."/".$archivo['name'];
		$rs_file=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$id."'");
		while($row_file=mysqli_fetch_array($rs_file)){
			if($row_file['file']!=''){
				unlink("../../".$row_file['file']);
			}
		}
   		if(move_uploaded_file($archivo['tmp_name'],"../../upload/products/".$id."/".$archivo['name'])){
      		mysqli_query($conexion,"UPDATE products SET file='".$ruta."' WHERE id='".$id."'") or die ("error");
      		echo 1;
   		}else{
      		echo 0;
   		}
   		return 0;
	}
	if($_GET['v']=='add-img'){
		$id=$_POST['id'];
		$reporte=null;
		$imgs="";
		if(!file_exists("../../upload/products/".$id)){
			mkdir("../../upload/products/".$id);
		}
		$rs_img=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$id."'");
		while($row_img=mysqli_fetch_array($rs_img)){
			if($row_img['img']=="img/logo.png"){
				$imgs="";
			}else{
				$imgs=$row_img['img'];
			}
		}
		for($x=0; $x<count($_FILES['img']['name']); $x++){
			$file=$_FILES["img"];
			$tipo=$file["type"][$x];
	      	if($tipo!='image/jpeg' && $tipo !='image/jpg' && $tipo !='image/png' && $tipo !='image/gif'){
	          	$reporte="valido";
	      	}else{
		      	$nombre=$file["name"][$x];
		      	$ruta_provisional=$file["tmp_name"][$x];
		      	$size=$file["size"][$x];
		      	$dimensiones=getimagesize($ruta_provisional);
		      	$width=$dimensiones[0];
		      	$height=$dimensiones[1];
		      	$carpeta="../../upload/products/".$id."/".$nombre;
	          	if(move_uploaded_file($ruta_provisional,$carpeta)){
	          		$reporte=1;
	          	}else{
	          		$reporte=0;
	          	}
	          	$imgs.="upload/products/".$id."/".$nombre."&";
	      	}
		}
		if($imgs==""){
			$imgs="img/logo.png";
		}
		mysqli_query($conexion,"UPDATE products SET img='".$imgs."' WHERE id='".$id."'") or die ("error");
		echo $reporte;
		return 0;//header("location:../products/add6.php?id=".$id);
	}
	if($_GET['v']=='delete-product'){
		$rs_delete_product=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
		while($row_delete_product=mysqli_fetch_array($rs_delete_product)){
			if($row_delete_product['img']!='img/logo.png'){
				if($row_delete_product['img']!=''){
					$img_delete=explode("&",$row_delete_product['img']);
					for($a=0; $a<sizeof($img_delete)-1; $a++){
						if(file_exists("../../".$img_delete[$a])){
							unlink("../../".$img_delete[$a]);
						}
					}
					if($row_delete_product['file']!=''){
						if(file_exists("../../".$row_delete_product['file'])){
							unlink("../../".$row_delete_product['file']);
						}							
					}
					if(file_exists("../../upload/products/".$_POST['id'])){
						rmdir("../../upload/products/".$_POST['id']) or die ("error");
					}
				}
			}
		}
		mysqli_query($conexion,"CALL delete_product('".$_POST['id']."')") or die ("error");
		echo $_POST['id'];
		return 0;
	}
	if($_GET['v']=='delete-img'){
		if($_GET['op']=="" || $_GET['op']=="all"){
			$rs_delete_img=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
			while($row_delete_img=mysqli_fetch_array($rs_delete_img)){
				$img_delete=explode("&",$row_delete_img['img']);
				for($a=0; $a<sizeof($img_delete)-1; $a++){
					unlink("../../".$img_delete[$a]);
				}
			}
			mysqli_query($conexion,"UPDATE products SET img='img/logo.png' WHERE id='".$_POST['id']."'") or die ("error");
		}else{
			$imgs="";
			$rs_delete_img=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
			while($row_delete_img=mysqli_fetch_array($rs_delete_img)){
				$img_delete=explode("&",$row_delete_img['img']);
				for($a=0; $a<sizeof($img_delete)-1; $a++){
					if($img_delete[$a]==$_GET['op']){
						unlink("../../".$img_delete[$a]);
					}else{
						$imgs.=$img_delete[$a]."&";
					}
				}
				if((sizeof($img_delete)-1)==1){
					$imgs="img/logo.png";
				}
			}
			mysqli_query($conexion,"UPDATE products SET img='".$imgs."' WHERE id='".$_POST['id']."'") or die ("error");
		}
		echo $_POST['id'];
		return 0;
	}
	if($_GET['v']=='clonar'){
		$rs_clonar=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_GET['id']."'");
		while($row_clonar=mysqli_fetch_array($rs_clonar)){
			$id=uniqid();
			mysqli_query($conexion,"INSERT INTO products VALUES ('".$id."','".$row_clonar['title']."','".$row_clonar['category']."','".$row_clonar['seccion']."',".$row_clonar['price'].",".$row_clonar['shipping'].",".$row_clonar['quantity'].",0,'".$row_clonar['description']."','".$row_clonar['format']."','','".$row_clonar['color']."','".$row_clonar['status']."','img/logo.png',now(),0,0)") or die ("error");
		}
		return header("location:../products/view.php");
	}
	if($_GET['v']=='portada'){
		$imgs="";
		$rs_delete_img=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
		while($row_delete_img=mysqli_fetch_array($rs_delete_img)){
			$img_delete=explode("&",$row_delete_img['img']);
			$imgs.=$_POST['img']."&";
			for($a=0; $a<sizeof($img_delete)-1; $a++){
				if($img_delete[$a]!=$_POST['img']){
					$imgs.=$img_delete[$a]."&";
				}
			}
		}
		mysqli_query($conexion,"UPDATE products SET img='".$imgs."' WHERE id='".$_POST['id']."'") or die ("error");
	}
	if($_GET['v']=='get-update'){
		$rs_update=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
		while($row_update=mysqli_fetch_array($rs_update)){
			if($_GET['op']=='view'){
				$img=explode("&",$row_update['img']);
				$estrellas="";
				for($a=1; $a<=5; $a++){
					if($a<=$row_update['stars']){
						$estrellas.="<img src='../../img/estrella.png' width='20px' height='20px'>";
					}else{
						$estrellas.="<img src='../../img/estrella_vacia.png' width='20px' height='20px'>";
					}
				}
				$datos['img_view']=$img[0];
				$datos['title_view']=utf8_encode($row_update['title']);
				$datos['sold_view']=$row_update['sold'];
				$datos['views_view']=$row_update['views'];
				$datos['stars_view']=$estrellas;
			}
			if($_GET['op']=='opinion'){
				$rs_opinion=mysqli_query($conexion,"SELECT * FROM opinions WHERE id_product='".$_POST['id']."' ORDER BY date DESC");
				$datos['opinion']="NO HAY OPINIONES";
				while($row_opinion=mysqli_fetch_array($rs_opinion)){
					if($datos['opinion']=="NO HAY OPINIONES"){
						$datos['opinion']="";
					}
					$stars="";
					for($s=1; $s<=5; $s++){
						if($s<=$row_opinion['stars']){
							$stars.="<img src='../../img/estrella.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
						}else{
							$stars.="<img src='../../img/estrella_vacia.png' width='20px' height='20px' style='margin: 0px 5px 0px 5px;'>";
						}
					}
					$rs_opinion_user=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$row_opinion['id_user']."'");
					while($row_opinion_user=mysqli_fetch_array($rs_opinion_user)){
						$datos['opinion'].="<div class='opinion' id='opinion".$row_opinion['id']."'>
							<div style='display: flex; align-items: center;'>
								<div class='content-img-user'>
									<img class='img-user' src='../../".$row_opinion_user['img']."'>
								</div>
								<strong class='name-user'>".$row_opinion_user['name']."</strong>".$stars."
							</div>
							<div class='text-opinion'>".$row_opinion['opinion']."</div>
							<a class='btn-delete' onclick=\"delete_opinion('".$row_opinion['id']."')\">ELIMINAR</a>
						</div>";
					}
				}
				echo json_encode($datos,true);
				return 0;
			}
			if($_GET['op']=='question'){
				$rs_question=mysqli_query($conexion,"SELECT * FROM questions WHERE id_product='".$_POST['id']."' ORDER BY date DESC");
				$datos['question']="NO HAY PREGUNTAS";
				while($row_question=mysqli_fetch_array($rs_question)){
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
						$datos['question'].="<div class='opinion' id='question".$row_question['id']."'>
							<div style='display: flex; align-items: center;'>
								<div class='content-img-user'>
									<img class='img-user' src='../../".$row_question_user['img']."'>
								</div>
								<strong class='name-user'>".$row_question_user['name']."</strong>
							</div>
							<div class='text-opinion'>".$row_question['question']."</div>
							<div>
								<a class='btn-delete' onclick=\"delete_question('".$row_question['id']."')\">ELIMINAR</a>
								<a class='btn-respond' id='btn-respond' onclick=\"info_answer('".$row_question['id']."')\">".$answer."</a>
							</div>
						</div>";
					}
				}
				echo json_encode($datos,true);
				return 0;
			}
			if($_GET['op']=='info'){
				$datos['title']=utf8_encode($row_update['title']);
				$datos['price']=$row_update['price'];
				$datos['shipping']=$row_update['shipping'];
				$datos['quantity']=$row_update['quantity'];
				$datos['description']=utf8_encode($row_update['description']);
				$datos['status']=$row_update['status'];
			}
			if($_GET['op']=='color'){
				$datos['colores']=$row_update['color'];
			}
			if($_GET['op']=='img'){
				$img=explode("&",$row_update['img']);
				$imgs="";
				for($a=0; $a<sizeof($img)-1; $a++){
					$imgs.="<img src='../../".$img[$a]."' class='img-product' onclick=\"delete_img_view('".$_POST['id']."','".$img[$a]."')\" title=\"ELIMINAR\" id='img_view".$img[$a]."'>";
				}
				$datos['imgs']=$imgs;
			}
			if($_GET['op']=='img-sele'){
				$img=explode("&",$row_update['img']);
				$imgs="";
				for($a=0; $a<sizeof($img)-1; $a++){
					$imgs.="<img src='../../".$img[$a]."' class='img-product-sele' onclick=\"cambiar_portada('".$_POST['id']."','".$img[$a]."')\" title=\"CAMBIAR PORTADA\" id='img_view".$img[$a]."'>";
				}
				$datos['imgs']=$imgs;
			}
			if($_GET['op']=='file'){
				$datos['title_file']=str_replace("upload/products/".$row_update['id']."/","",$row_update['file']);
			}
			echo json_encode($datos,true);
			return 0;
		}
	}
	if($_GET['v']=='update-info'){
		mysqli_query($conexion,"UPDATE products SET title='".utf8_decode($_POST['title'])."', description='".utf8_decode($_POST['description'])."', quantity=".$_POST['quantity'].", price=".$_POST['price'].", shipping=".$_POST['shipping'].", status='".$_POST['status']."' WHERE id='".$_POST['id']."'") or die ("error");
		echo $_POST['id'];
		return 0;
	}
	if($_GET['v']=='add-color'){
		$rs_color=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
		while($row_color=mysqli_fetch_array($rs_color)){
			mysqli_query($conexion,"UPDATE products SET color='".$row_color['color'].$_POST['color']."' WHERE id='".$_POST['id']."'") or die ("error");
		}
		return 0;
	}
	if($_GET['v']=='delete-color'){
		$colores="";
		$rs_color=mysqli_query($conexion,"SELECT * FROM products WHERE id='".$_POST['id']."'");
		while($row_color=mysqli_fetch_array($rs_color)){
			$color=explode("&",$row_color['color']);
			for($a=0; $a<sizeof($color)-1; $a++){
				if($_POST['color']!=$color[$a]){
					$colores.=$color[$a]."&";
				}
			}
			if(sizeof($color)>=3){
				mysqli_query($conexion,"UPDATE products SET color='".$colores."' WHERE id='".$_POST['id']."'") or die ("error");
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
}else{
	echo "<script>window.location='../index.php';</script>";
	return 0;
}

?>