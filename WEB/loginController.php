<?php
include ("config.php");
session_start();
if (isset($_POST['username']) && isset($_POST['password'])) {
	// username and password sent from Form
	$username = mysqli_real_escape_string($db, $_POST['username']);
	//Here converting passsword into MD5 encryption.
	$password = md5(mysqli_real_escape_string($db, $_POST['password']));

	$result = mysqli_query($db, "SELECT l.userId as userId,firstName,lastName,userType FROM login l, users u WHERE l.userId = u.id AND l.userName='$username' and l.password='$password'");
	// If result matched $username and $password, table row  must be 1 row
	if (mysqli_num_rows($result) == 1) {
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		if ($row['userType'] == "Admin") {
			$_SESSION['userId'] = $row['userId'];
			$_SESSION['firstName'] = $row['firstName'];
			$_SESSION['lastName'] = $row['lastName'];
			$_SESSION['userType'] = $row['userType'];
			$_SESSION['username'] = $username;
			//Storing user session value.
			echo json_encode(array('userId' => $row['userId']));
			//echo $row['userId'];
		} else {
			echo json_encode(array('error' => "Error: Employees are not allowed to login"));
		}
	} else {
		echo json_encode(array('error' => "Error: Invalid username and password."));
	}
}
?>