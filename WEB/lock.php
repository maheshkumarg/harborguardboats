<?php
include ('config.php');
session_start();
$user_check = $_SESSION['username'];

$result = mysqli_query($db, "SELECT userName FROM login WHERE userName='$user_check'");
if (mysqli_num_rows($result) < 1) {
	header("Location: login.php");
}
?>