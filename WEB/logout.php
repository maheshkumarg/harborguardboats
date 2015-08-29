<?php
session_start();
if (!empty($_SESSION['username'])) {
	$_SESSION['username'] = '';
	$_SESSION['userId'] = '';
	session_destroy();
}
header("Location:login.php");
?>