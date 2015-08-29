<?php
include_once ("config.php");
if (isset($_POST['forgotPwd'])) {
	if (!isset($_POST['password']) || !isset($_POST['cpassword']) || !isset($_POST['email'])) {
		?>
<script type="text/javascript">alert("We are sorry, but there appears to be a problem with the form you submitted.");</script>
<?php
die();
}
else
{
$password = $_POST['password'];
$email = $_POST['email'];

$error_message = "";
$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
if (!preg_match($email_exp, $email)) {
$error_message .= 'The Email Address you entered does not appear to be valid.<br />';
}

if (strlen($password) > 1) {
$password = $password;
}

$result = mysqli_query($db, "select userName FROM login WHERE userName='$email'");
if (mysqli_num_rows($result) > 0) {
$password = md5($password);
$result = mysqli_query($db, "UPDATE login SET password='$password' WHERE userName='$email'");
if (mysqli_affected_rows($db) > 0) {
?>
<script type="text/javascript">alert("Password updated successfully");</script>
<?php
}
else {
?>
<script type="text/javascript">alert("Please input a password which is not same as old password");</script>
<?php
}
} else {
?>
<script type="text/javascript">alert("Email address not found");</script>
<?php
}
}
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>HarborGuardBoats | Forgot Password</title>
		<link href="plugins/bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="plugins/bootstrap-3.2.0/css/font-awesome.css" rel="stylesheet">
		<link href="plugins/morris/css/morris-0.4.3.min.css" rel="stylesheet">
		<link href="css/animate.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
		<div class="row">
			<div class="middle-box loginscreen animated fadeInDown">
				<div>
					<h3>Forgot Password</h3>
					<form class="m-t" role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" id="login_form">
						<div class="form-group">
							<input type="email" maxlength="60" class="form-control" name="email" placeholder="Enter Email" required="">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="password" placeholder="Enter New Password" required="">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="cpassword" placeholder="Confirm Password" required="">
						</div>
						<button type="submit" name="forgotPwd" class="btn btn-primary block full-width m-b">
							Submit
						</button>
					</form>
					<div id="loginLnk">
						<a href="login.php"><small>Login Now</small></a>
					</div>
				</div>
			</div>
		</div>
		<div class="footer">
			<?php
			include_once (dirname(__FILE__) . '/templates/footer.php');
			?>
		</div>
		<!-- Mainly scripts -->
		<script src="plugins/jquery/js/jquery-2.1.1.js"></script>
		<script src="plugins/bootstrap-3.2.0/js/bootstrap.min.js"></script>
	</body>
</html>