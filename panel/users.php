<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMINISTRACIÓN</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/users.css">
	<script type="text/javascript" src="js/app.js"></script>
	<script type="text/javascript" src="js/users.js"></script>
</head>
<body>

<?php
	include("header.php");
?>

	<?php
		if(isset($_POST['search'])){
			$search=$_POST['search'];
			$s=explode(" ",$_POST['search']);
			$like="WHERE ";
			for($a=0; $a<sizeof($s); $a++){
				$like.="name LIKE '%".$s[$a]."%' OR surname LIKE '%".$s[$a]."%' OR phone LIKE '%".$s[$a]."%' OR email LIKE '%".$s[$a]."%' OR sexo LIKE '%".$s[$a]."%' OR cp LIKE '%".$s[$a]."%' OR user LIKE '%".$s[$a]."%'";
			}
		}else{
			$search="";
			$like="";
		}
		$num_row=mysqli_num_rows(mysqli_query($conexion,"SELECT * FROM users ".$like));
		if(isset($_GET['page'])){
			$page=$_GET['page'];
			if($page>ceil($num_row/20)){
				$page=ceil($num_row/20);
			}
		}else{
			$page=1;
		}
	?>
	<form action="users.php" method="post">
		<?php
		if(isset($_POST['search'])){
			echo "<input type='search' name='search' placeholder='Buscar...' value='".$_POST['search']."' class='caja'>";
		}else{
			echo "<input type='search' name='search' placeholder='Buscar...' class='caja'>";
		}
		?>
	</form>
	<fieldset class="content-users">
			<?php
				if($page==1){
					$rs=mysqli_query($conexion,"SELECT * FROM users ".$like." ORDER BY date_register DESC LIMIT 0,20");
				}else{
					$rs=mysqli_query($conexion,"SELECT * FROM users ".$like." ORDER BY date_register DESC LIMIT ".(($page-1)*20).",20");
				}
				$contador=0;
				while($row=mysqli_fetch_array($rs)){
					$contador++;
					if($contador==1){
						echo "<section class='users'>";
					}
					?>
					<li class='user' id='<?php echo $row['id']; ?>' onclick="modal2('<?php echo $row['id']; ?>')">
					<?php
						echo "<div class='content-img-users'>
							<img src='../".$row['img']."' width='100%' height='100%' loaging='lazy' id='img-user".$row['id']."'>
						</div>
						<div class='content-name' id='name-user".$row['id']."'>".utf8_encode($row['name'])."</div>
					</li>";
					if($contador==5){
						$contador=0;
						echo "</section>";
					}
					?>
					<section class="modal" id='modal-option<?php echo $row['id']; ?>' style="display: none;" onclick="modal2('<?php echo $row['id']; ?>')">
						<div class="content-option" id="content-option">
							<li class="option" onclick="getUpdate('opinion','<?php echo $row['id']; ?>')">VER OPINIONES</li>
							<li class="option" onclick="getUpdate('question','<?php echo $row['id']; ?>')">VER PREGUNTAS</li>
							<hr>
							<li class="option" onclick="getUpdate('compras','<?php echo $row['id']; ?>')">VER COMPRAS</li>
							<hr>
							<li class="option" onclick="getUpdate('mas','<?php echo $row['id']; ?>')">MÁS OPCIONES</li>
						</div>
					</section>
					<?php
				}
				echo "</section>";
				echo "<section class='content-page'>";
				for($a=1; $a<=ceil($num_row/20); $a++){
					if($num_row>=20){
						if($page==$a){
							?>
							<form action="users.php?page=<?php echo $a; ?>" method="post">
								<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
								<input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px; background: #353535;" class="page" id="page<?php echo $a; ?>">
							</form>
							<?php
						}else{
							?>
							<form action="users.php?page=<?php echo $a; ?>" method="post">
								<input type="text" name="search" value="<?php echo $search; ?>" readonly hidden>
								<input type="submit" value="<?php echo $a; ?>" style="cursor: pointer; font-size: 20px; margin:10px;" class="page" id="page<?php echo $a; ?>">
							</form>
							<?php
						}
					}
				}
				echo "</section>";
			?>
	</fieldset>

	<section class="modal" id='modal-opinion' style="display: none;" onclick="modal('modal-opinion')">
		<div class="content-modal" onclick="modal('modal-opinion')">
			<h2>OPINIONES</h2><br>
			<div class="content-opinions" id="content-opinions"></div>
		</div>
	</section>

	<section class="modal" id='modal-question' style="display: none;" onclick="modal('modal-question')">
		<div class="content-modal" onclick="modal('modal-question')">
			<h2>PREGUNTAS</h2><br>
			<div class="content-questions" id="content-questions"></div>
		</div>
	</section>
	<section class="modal" id='modal-info-answer' style="display: none;" onclick="modal('modal-info-answer')">
		<div class="content-modal" onclick="modal('modal-info-answer')">
			<h2 id="text-question"></h2><br>
			<textarea class="caja" id="text-answer" placeholder="Escribe una respuesta..."></textarea>
			<div class="content-btn" id="content-btn-answer">
			</div><br>
		</div>
	</section>

	<section class="modal" id='modal-compras' style="display: none;" onclick="modal('modal-compras')">
		<div class="content-modal" onclick="modal('modal-compras')">
			<h2>COMPRAS</h2><br>
			<div class="content-compras" id="content-compras"></div>
		</div>
	</section>

	<section class="modal" id='modal-mas' style="display: none;" onclick="modal('modal-mas')">
		<div class="content-modal" onclick="modal('modal-mas')">
			<h2>MÁS OPCIONES</h2><br>
			<div class="content-mas" id="content-mas"></div>
		</div>
	</section>

</body>
</html>