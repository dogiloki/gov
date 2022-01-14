<?php

	if(isset($_GET['v'])){
		echo "<center><h1>ERROR EN EL SERVIDOR</h1></center>";
	}else{
		header("location:index.php");
	}

?>