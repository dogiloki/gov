<?php

	if(isset($_GET['v'])){
		include('../conexion.php');
		session_start();
		if($_GET['v']=='cp'){
			$rs_cp=mysqli_query($conexion,"SELECT * FROM postal_code WHERE cp='".$_POST['cp']."'");
			while($row_cp=mysqli_fetch_array($rs_cp)){
				echo utf8_encode($row_cp['colonia']."&".$row_cp['municipio']."&".$row_cp['estado']);
				return 0;
			}
			echo "sin_ubicacion";
			return 0;
		}
		if($_GET['v']=='email'){
			if($_POST['email']){
				$rs_email=mysqli_query($conexion,"SELECT * FROM users WHERE email='".$_POST['email']."'");
				while($row_email=mysqli_fetch_array($rs_email)){
					if($row_email['email']==$_POST['email']){
						if(isset($_SESSION['gov']['id'])){
							if($row_email['email']!=$_SESSION['gov']['email']){
								echo "Este email ya esta registrado";
								return 0;
							}
						}else{
							echo "Este email ya esta registrado";
							return 0;
						}
					}
				}
			}
		}
		if($_GET['v']=='user'){
			if($_POST['user']!=''){
				$rs_user=mysqli_query($conexion,"SELECT * FROM users WHERE user='".$_POST['user']."'");
				while($row_user=mysqli_fetch_array($rs_user)){
					if($row_user['user']==$_POST['user']){
						if(isset($_SESSION['gov']['id'])){
							if($row_user['user']!=$_SESSION['gov']['user']){
								echo "El usuario ya existe";
								return 0;
							}
						}else{
							echo "El usuario ya existe";
							return 0;
						}
					}
				}
			}
		}
		if($_GET['v']=='password'){
			if($_POST['password']!=''){
				$clave=$_POST['password'];
				if(strlen($clave)<8){
     				echo "La contraseña debe tener al menos 8 caracteres";
      				return 0;
   				}
   				if(strlen($clave)>16){
   				   echo "La contraseña no puede tener más de 16 caracteres";
   				   return 0;
   				}
   				if(!preg_match('`[a-z]`',$clave)){
      				echo"La contraseña debe tener al menos una letra minúscula";
     				return 0;
   				}
   				if(!preg_match('`[A-Z]`',$clave)){
      				echo "La contraseña debe tener al menos una letra mayúscula";
      				return 0;
   				}
   				if(!preg_match('`[0-9]`',$clave)){
      				echo "La contraseña debe tener al menos un caracter numérico";
      				return 0;
   				}
   				echo "valido";
   				return 0;
			}
		}
		if($_GET['v']=='register'){
			if($_POST['password']!=$_POST['password_veri']){
				echo "password";
				return 0;
			}
			if($_POST['sexo']=='Mujer'){
				$img="img/mujer.png";
			}else{
				$img="img/hombre.png";
			}
			$rs_user_very=mysqli_query($conexion,"SELECT * FROM users WHERE user='".$_POST['user']."'");
			while($row_user_very=mysqli_fetch_array($rs_user_very)){
				if($_POST['user']==$row_user_very['user']){
					echo "register";
					return 0;
				}
			}
			$password=password_hash($_POST['password'],PASSWORD_DEFAULT,array("cost"=>10));
			$id=uniqid();
			$code=$cvv=mt_rand(0,9);
			$code.=$cvv=mt_rand(0,9);
			$code.=$cvv=mt_rand(0,9);
			$code.=$cvv=mt_rand(0,9);
			$code.=$cvv=mt_rand(0,9);
			mysqli_query($conexion,"INSERT INTO users VALUES ('".$id."','".$_POST['name']."','','".$_POST['phone']."','".$_POST['email']."','".$_POST['birth']."','".$_POST['sexo']."','".$_POST['cp']."',now(),'".$_POST['user']."','".$password."','".$img."',1,0,0)") or die ("error");
			echo "$&$&".$id;
			return 0;
			/*$mail=new PHPMailer(true);
			mysqli_query($conexion,"INSERT INTO verify VALUES ('".$id."','".$code."')") or die ("error");
			$sql_event="CREATE EVENT z".$id."
    				ON SCHEDULE AT 
    					CURRENT_TIMESTAMP + INTERVAL 3 MINUTE
					DO
    				BEGIN
      					DELETE FROM verify WHERE id_user='".$id."';
      					DROP EVENT IF EXISTS z".$id.";
					END";
			mysqli_query($conexion,$sql_event) or die ("error");
			try{
			    //Configurar servidor
			    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
			    $mail->isSMTP();
				$mail->Host='smtp.gmail.com';
			    $mail->SMTPAuth=true;
		 	    $mail->Username='genesis.of.videogames@gmail.com';
			    $mail->Password='8Y6NCKgBqBPgmrc';
			    $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
			    $mail->Port=587;
						
			   	$mail->setFrom('genesis.of.videogames@gmail.com', 'Genesis of videogames');
			    $mail->addAddress($_POST['email']);     // Add a recipient
				
			    /*
			    $mail->addAttachment('file.zip');
			    $mail->addAttachment('image.jpg', 'new.jpg');---------------
				
			    $mail->isHTML(true);
			    $time=time();
			    $ip="";
			    $fp=fopen("ip.txt", "r");
				while(!feof($fp)){
    				$ip=fgets($fp);
				}
				fclose($fp);
			    $mail->Subject="REGISTRO EN GOV - ".date("d-m-Y (H:i:s)",$time);
			   	/*$mail->Body="<center><h1>SINO RECONOCES ESTE CORREO O NO LO SOLICITASTE IGNORALO</h1><br><br><a style='padding: 10px; color: #f5f5f5; background: #F86400; cursor: pointer; font-size: 25px; text-decoration: none; border: 1px solid #c2c2c2c;' title='activar' href='http://".$ip."/gov/controllers/user.php?v=active&id=".$id."&op=1'>ACTIVAR CUENTA</a></center>";-----------
			   	$mail->Body="<center><h1>SINO RECONOCES ESTE CORREO O NO LO SOLICITASTE IGNORALO</h1><br><br><strong style='font-size: 30px;'>CÓDIGO: ".$code."</strong><br><br>caducá en 5 minutos</a></center>";
					
			    $mail->send();
			}catch(Exception $e) {
				echo "email";//{$mail->ErrorInfo}
			    return 0;
			}*/
			echo "error";
			return 0;
		}
		if($_GET['v']=='active'){
			if(isset($_POST['id_user']) && isset($_GET['op']) && $_GET['op']=='1' && isset($_POST['code'])){
				$rs_active=mysqli_query($conexion,"SELECT * FROM verify WHERE id_user='".$_POST['id_user']."'");
				while($row_active=mysqli_fetch_array($rs_active)){
					if($row_active['code']==$_POST['code']){
						mysqli_query($conexion,"UPDATE users SET active=".$_GET['op']." WHERE id='".$_POST['id_user']."'") or die ("<h1>EEROR AL ACTIVAR CUENTA<br>CONTACTE CON LA PÁGINA</h1>");
						echo "active";
					}else{
						echo "CÓDIGO NO VÁLIDO";
					}
				}
				//mysqli_query($conexion,"CALL active('".$_GET['id']."',".$_GET['op'].")") or die ("<h1>EEROR AL ACTIVAR CUENTA<br>CONTACTE CON LA PÁGINA</h1>");
			}
			return 0;
		}
		if($_GET['v']=='login_datos'){
			$save=explode("&",$_COOKIE['save_user']);
			$save_user="";
			for($a=0; $a<sizeof($save)-1; $a++){
				if($save[$a]==$_POST['id']){
					$rs_login_datos=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_POST['id']."'");
					while($row_login_datos=mysqli_fetch_array($rs_login_datos)){
						$datos['img']=$row_login_datos['img'];
						$datos['name']=$row_login_datos['name'];
					}
					echo json_encode($datos);
					return 0;
				}
			}
			$datos['user']='user';
			echo json_encode($datos);
			return 0;
		}
		if($_GET['v']=='login'){
			$user_login="";
			if(isset($_POST['login_save'])){
				if($_POST['login_save']=='true'){
					$id_login="";
					$save=explode("&",$_COOKIE['save_user']);
					$save_user="";
					for($a=0; $a<sizeof($save)-1; $a++){
						if($save[$a]==$_POST['id']){
							$id_login=$save[$a];
							$a=sizeof($save);
						}
					}
					$rs_login=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$id_login."'");
					$rs_login2=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$id_login."'");
					while($row_login2=mysqli_fetch_array($rs_login2)){
						$user_login=$row_login2['user'];
					}
				}else{
					$rs_login=mysqli_query($conexion,"SELECT * FROM users WHERE user LIKE '%".$_POST['user']."%'");
				}
			}else{
				$user_login=$_POST['user'];
				$rs_login=mysqli_query($conexion,"SELECT * FROM users WHERE user LIKE '%".$_POST['user']."%'");
				if($user_login=='' || $_POST['password']==''){
					echo "vacio";
					return 0;
				}
			}
			while($row_login=mysqli_fetch_array($rs_login)){
				if($user_login!=$row_login['user']){
					echo "user";
					return 0;
				}else{
					if(!password_verify($_POST['password'],$row_login['password'])){
						echo "password";
						return 0;
					}else{
						if($row_login['active']=='0'){
							echo "active";
							return 0;
						}
						if($row_login['locked']=='1'){
							echo "locked";
							return 0;
						}else{
							getInfo($row_login['id'],$conexion);
							if($row_login['admin']==0){
								if($_POST['save']=='true'){
									if(isset($_COOKIE['save_user'])){
										$save=explode("&",$_COOKIE['save_user']);
										$save_user="";
										for($a=0; $a<sizeof($save)-1; $a++){
											if($save[$a]!=$row_login['id']){
												$save_user.=$save[$a]."&";
											}
										}
										$save_user.=$row_login['id']."&";
										setcookie('save_user',$save_user,time()+365*24*60*60,'/');
									}else{
										setcookie('save_user',$row_login['id']."&",time()+365*24*60*60,'/');
									}
								}
								echo "logiado";
							}else{
								if($_POST['save']=='true'){
									if(isset($_COOKIE['save_user'])){
										$save=explode("&",$_COOKIE['save_user']);
										$save_user="";
										for($a=0; $a<sizeof($save)-1; $a++){
											if($save[$a]!=$row_login['id']){
												$save_user.=$save[$a]."&";
											}
										}
										$save_user.=$row_login['id']."&";
										setcookie('save_user',$save_user,time()+365*24*60*60,'/');
									}else{
										setcookie('save_user',$row_login['id']."&",time()+365*24*60*60,'/');
									}
								}
								echo "logiado_admin";
							}
							return 0;
						}
					}
				}
			}
			echo "user";
			return 0;
		}
		if($_GET['v']=='delete_save'){
			$save=explode("&",$_COOKIE['save_user']);
			$save_user="";
			for($a=0; $a<sizeof($save)-1; $a++){
				if($save[$a]!=$_POST['id']){
					$save_user.=$save[$a]."&";
				}
			}
			setcookie('save_user',$save_user,time()+365*24*60*60,'/');
		}

		//Actualizar usuarios
		if($_GET['v']=='updateUser' && isset($_SESSION['gov']['id'])){
			if($_GET['campo']=='user' || $_GET['campo']=='email' || $_GET['campo']=='password' || $_GET['campo']=='locked' || $_GET['campo']=='active' || $_GET['campo']=='admin'){
				echo "ERROR EN SERVIDOR";
				return 0;
			}
			if($_GET['campo']=='img'){
				$rs_update=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_SESSION['gov']['id']."'") or die ("ERROR EN SERVIDOR");
				while($row_update=mysqli_fetch_array($rs_update)){
					if(!file_exists("../upload/users/".$row_update['id'])){
						mkdir("../upload/users/".$row_update['id']);
					}
					if(file_exists("../".$row_update['img']) && $row_update['img']!='img/mujer.png' && $row_update['img']!='img/hombre.png'){
						unlink("../".$row_update['img']) or die ("ERROR EN SERVIDOR");
					}
					if(move_uploaded_file($_FILES['img']['tmp_name'],"../upload/users/".$row_update['id']."/".$_FILES['img']['name'])){
						echo 1;
					}else{
						echo 0;
					}
					mysqli_query($conexion,"UPDATE users SET ".$_GET['campo']."='upload/users/".$row_update['id']."/".$_FILES['img']['name']."' WHERE id='".$_SESSION['gov']['id']."'") or die ("ERROR EN SERVIDOR");
					getInfo($_SESSION['gov']['id'],$conexion);
					return 0;
				}
			}
			mysqli_query($conexion,"UPDATE users SET ".$_GET['campo']."='".$_POST['dato']."' WHERE id='".$_SESSION['gov']['id']."'") or die ("ERROR EN SERVIDOR");
			getInfo($_SESSION['gov']['id'],$conexion);
			return 0;
		}
		//Actualizar usuarios en seguridad
		if($_GET['v']=='updateUserSecurity'){
			if(isset($_POST['password_actual'])){
				$rs_update_security=mysqli_query($conexion,"SELECT * FROM users WHERE id='".$_SESSION['gov']['id']."'");
				while($row_update_security=mysqli_fetch_array($rs_update_security)){
					if(password_verify($_POST['password_actual'],$row_update_security['password'])){
						if($_POST['password']!=''){
							$password=password_hash($_POST['password'],PASSWORD_DEFAULT,array("cost"=>10));
							mysqli_query($conexion,"UPDATE users SET user='".$_POST['user']."', email='".$_POST['email']."', password='".$password."' WHERE id='".$_SESSION['gov']['id']."'") or die ("error");
						}else{
							mysqli_query($conexion,"UPDATE users SET user='".$_POST['user']."', email='".$_POST['email']."' WHERE id='".$_SESSION['gov']['id']."'") or die ("error");
						}
						
						getInfo($_SESSION['gov']['id'],$conexion);
					}else{
						echo "password";
						return 0;
					}
				}
			}else{
				echo "error";
			}
			return 0;
		}
	}

	function getInfo($id,$conexion){
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

?>