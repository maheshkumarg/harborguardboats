<?php
include_once ('lock.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>HarborGuardBoats | Profile</title>
		<?php
		include_once ('templates/headerIncludes.php');
		?>
	</head>
	<body>
		<div id="wrapper">
			<?php
			include_once ('templates/leftnav.php');
			?>
			<div id="page-wrapper" class="gray-bg">
				<div class="row border-bottom">
					<?php
					include_once ('templates/topnav.php');
					?>
				</div>
				<div class="wrapper wrapper-content animated fadeInRight" >
					<div class="row">
						<h2>My Profile</h2>
						<form method="get" class="form-horizontal" role="form" id="myProfileFrm">
							<div class="form-group">
								<label class="col-sm-3 control-label">First Name</label>
								<div class="col-sm-5">
									<input type="text" maxlength="50" class="form-control" name='firstName' id='firstName'>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Last Name</label>
								<div class="col-sm-5">
									<input type="text" maxlength="50" class="form-control" name='lastName' id='lastName'>
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Email</label>
								<div class="col-sm-5">
									<input type="hidden" maxlength="60" class="form-control" name='userType' id='userType' />
									<input type="email" maxlength="60" class="form-control" name='email' id='email' required="">
									<span class="text-muted m-b-none">username used to login to the web portal.</span>
								</div>
								<div class="col-sm-4">
									<a href='#chngePasswdModal' data-toggle="modal" data-target="#chngePasswdModal" id='changePassword'>Edit password</a>
								</div>
							</div>
							<div class="hr-line-dashed"></div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Designation</label>
								<div class="col-sm-5">
									<input type="text" maxlength="100" class="form-control" name='desig' id='desig'>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">Phone Number</label>
								<div class="col-sm-5">
									<input type="text" maxlength="20" class="form-control" name='phoneNumber' id='phoneNumber'>
								</div>
							</div>
							<!-- <div class="hr-line-dashed"></div>
							<div class="form-group">
							<label class="col-sm-3 control-label">Profile Pic</label>
							<div class="col-sm-5">
							<input type="file" class="form-control" name="profile-pic" id="profile-pic">
							</div>
							</div> -->
							<div class="form-group">
								<div class="col-sm-4 col-sm-offset-4">
									<button class="btn btn-primary" type="submit">
										Save
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="footer">
					<?php
					include_once ('templates/footer.php');
					?>
				</div>
			</div>
		</div>

		<div class="modal fade" id="chngePasswdModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<form method="put" role="form" class="form-horizontal" id="chngePasswdFrm">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title" id="myModalLabel">Change Password</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label class="col-sm-4 control-label">New Password</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="password" name="password" required="">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label">Confirm Password</label>
								<div class="col-sm-8">
									<input type="password" class="form-control" id="confirm_password" name='confirm_password' required="">
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary" id="updPasswdBtn">
								Update
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<?php
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/profile.js" type="text/javascript"></script>
		<script src="js/common.js" type="text/javascript"></script>
	</body>
</html>