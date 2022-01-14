<?php
	session_start();
	$_SESSION['gov']=null;
	return header("location:login.php");
?>