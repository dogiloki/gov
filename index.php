<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="author" content="Julio César Villanueva Ontiveros">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>GOV</title>
	<link rel="stylesheet" type="text/css" href="css/header.css">
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<script type="text/javascript" src="js/app.js"></script>
</head>
<body>

<?php

	include("models/header.php");
	include("models/products/explore.php");
	include("models/products/more_viewed.php");
	if(isset($_SESSION['gov']['id'])){
		include("models/products/viewed.php");
	}

?>

	

</body>
</html>