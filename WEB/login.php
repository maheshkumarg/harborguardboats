<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>HarborGuardBoats | Login</title>
		<link href="plugins/bootstrap-3.2.0/css/bootstrap.min.css" rel="stylesheet">
		<link href="plugins/bootstrap-3.2.0/css/font-awesome.css" rel="stylesheet">
		<link href="plugins/morris/css/morris-0.4.3.min.css" rel="stylesheet">
		<link href="css/animate.css" rel="stylesheet">
		<link href="css/style.css" rel="stylesheet">
	</head>
	<body class="gray-bg">
		<div class="text-center">
			<h3 class="logo-name">HarborGuardBoats</h3>
		</div>
		<div class="row">
			<div class="col-xs-6" id="brandImageContainer">
				<img src="images/hgb1.jpg" />
			</div>
			<div class="col-xs-6 middle-box loginscreen animated fadeInDown">
				<div>
					<h3>Welcome to Admin Portal</h3>
					<form class="m-t" role="form" method="post" action="" id="login_form">
						<div class="form-group">
							<input type="email" class="form-control" name="username" placeholder="Enter Email" required="">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" name="password" placeholder="Password" required="">
						</div>
						<button type="submit" class="btn btn-primary block full-width m-b">
							Login
						</button>
						<a href="forgotPassword.php"><small>Forgot password?</small></a>
					</form>
					<div id="error"></div>
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
		<script src="js/login.js"></script>
	</body>
</html>