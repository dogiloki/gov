<?php
session_cache_limiter("must-revalidate");
include("conexion.php");
session_start();
if(!isset($_SESSION['gov']['id'])){
	?>
	<header>
		<img src="img/logo.png" width="75px" height="50px" class="logo" onclick="window.location='index.php'">
		<div class="title" onclick="window.location='index.php'">
			<span style="color: #2e82ff;">GENESIS</span>
			<span style="color: #e55d15;">OF</span>
			<span style="color: #75a800;">VIDEOGAMES</span>
		</div>
		<form action="search.php?page=1" method="post" class="content-search">
			<div class="content-category">
				<input type="text" name='category' id="category" class="caja-category" value="Todos" readonly onclick="modal('content-category-option')" required>
				<div class="content-category-option" id="content-category-option" onclick="modal('content-category-option')" style="display: none;">
					<option onclick="$('#category').val('Todos')">Todos
					<option onclick="$('#category').val('Juegos')">Juegos
					<option onclick="$('#category').val('Consolas')">Consolas
					<option onclick="$('#category').val('Accesorios')">Accesorios
					<option onclick="$('#category').val('Tarjetas')">Tarjetas
					<option onclick="$('#category').val('Especiales')">Especioales
				</div>
			</div>
			<input type="search" class="caja-search" placeholder="BUSCAR" name="search" required>
			<div class="btn-search">
				<input type="submit" value="" style="width: 40px; height:35px; border: none; background: none; border-radius: 0px 5px 5px 0px; cursor: pointer;">
				<img src="img/search.png" width="100%" height="100%" style="position: absolute; left: 0px; top: 0px; border-radius: 0px 5px 5px 0px; z-index: -1;">
			</div>
		</form>
		<nav class="nav-header">
			<li class="op-header" onclick="window.location='register.php'"><img src="img/registrarse.png" width="20px" height="20px" style="margin-right: 5px;">REGISTRARSE</li>
			<li class="op-header" onclick="window.location='login.php'"><img src="img/ingresar.png" width="20px" height="20px" style="margin-right: 5px;">INGRESAR</li>
		</nav>
	</header>
	<?php
}else{
	getInfoUser($_SESSION['gov']['id'],$conexion);
	?>
	<header>
		<img src="img/logo.png" width="75px" height="50px" class="logo" onclick="window.location='index.php'">
		<div class="title" onclick="window.location='index.php'">
			<span style="color: #2e82ff;">GENESIS</span>
			<span style="color: #e55d15;">OF</span>
			<span style="color: #75a800;">VIDEOGAMES</span>
		</div>
		<form action="search.php?page=1" method="post" class="content-search">
			<div class="content-category">
				<input type="text" name='category' id="category" class="caja-category" value="Todos" readonly onclick="modal('content-category-option')">
				<div class="content-category-option" id="content-category-option" onclick="modal('content-category-option')" style="display: none;">
					<option onclick="$('#category').val('Todos')">Todos
					<option onclick="$('#category').val('Juegos')">Juegos
					<option onclick="$('#category').val('Consolas')">Consolas
					<option onclick="$('#category').val('Accesorios')">Accesorios
					<option onclick="$('#category').val('Tarjetas')">Tarjetas
					<option onclick="$('#category').val('Especiales')">Especiales
				</div>
			</div>
			<input type="search" class="caja-search" placeholder="BUSCAR" name="search" required>
			<div class="btn-search">
				<input type="submit" value="" style="width: 40px; height: 35px; border: none; background: none; border-radius: 0px 5px 5px 0px; cursor: pointer;">
				<img src="img/search.png" width="100%" height="100%" style="position: absolute; left: 0px; top: 0px; border-radius: 0px 5px 5px 0px; z-index: -1;">
			</div>
		</form>
		<nav class="nav-header">
			<li class="op-header" onclick="window.location='shopping.php'" title="Compras"><img src="img/compras.png" width="40px" height="40px" style="margin-right: 5px;"></li>
			<li class="op-header" onclick="window.location='record.php'" title="Historial"><img src="img/historial.png" width="40px" height="40px" style="margin-right: 5px;"></li>
			<li class="op-header" onclick="window.location='cart.php'" title="Carrito"><img src="img/carrito.png" width="40px" height="40px" style="margin-right: 5px;"></li>
			<li class="op-header" title="Menu" onclick="modal('content-menu')">
				<div style="width: 40px; overflow: hidden; margin-right: 5px;">
					<img src="img/hombre.png" style="max-width: 40px;">
				</div>
			</li>
		</nav>
		<section class="content-menu" id="content-menu" style="display: none;" onclick="modal('content-menu')">
			<div class="menu" onclick="modal('content-menu')">
				<center><div class="content-img">
					<img src="<?php echo $_SESSION['gov']['img']; ?>" class="img">
				</div></center>
				<div class="content-name">
					<?php echo $_SESSION['gov']['fullname']; ?>
				</div>
				<div class="content-email">
					<?php echo $_SESSION['gov']['email']; ?>
				</div>
				<hr><br>
				<li onclick="window.location='account.php'"><img src="img/hombre.png" width="20px" height="20px" style="margin-right: 5px; border-radius: 100%;">CUENTA</li>
				<li onclick="window.location='security.php'"><img src="img/seguridad.png" width="20px" height="20px" style="margin-right: 5px;">SEGURIDAD</li>
				<?php
				if($_SESSION['gov']['admin']=='1'){
					?>
					<li onclick="window.location='panel/index.php'"><img src="img/administracion.png" width="20px" height="20px" style="margin-right: 5px;">PÁGINA DE ADMINISTRACIÓN</li>
					<?php
				}
				?>
				<li onclick="window.location='close.php'"><img src="img/salir.png" width="20px" height="20px" style="margin-right: 5px;">CERRAR SESIÓN</li>
			</div>
		</section>
	</header>
	<?php
}
?>