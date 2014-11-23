<?php
	session_start();
	if(!isset($_SESSION['user'])) header('Location: ../login.php');
	if(($_SESSION['type'] != 'admin') && (basename($_SERVER['SCRIPT_NAME']) != 'V.php' || $tabla == 'users')) header('Location: V.php');
	if($_SESSION['type'] != 'admin' && $tabla == 'users') header('Location: ../logout.php');
?>