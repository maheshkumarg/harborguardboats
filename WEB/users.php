<?php
include_once ('lock.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>HarborGuardBoats | Users</title>
		<?php
		$current = "users";
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
						<h2>Users
						<button type="button" style="margin:0 10px" class="btn pull-right btn-primary" data-toggle="modal" data-target="#addUserModal">
							Add User
						</button></h2>
						<table id="users" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Name</th>
									<th>Email</th>
									<th>Designation</th>
									<th>Rate Per Hour</th>
									<th>Phone Number</th>
									<th>Role</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody></tbody>
							<tfoot></tfoot>
						</table>
					</div>
				</div>
				<div class="footer">
					<?php
					include_once ('templates/footer.php');
					?>
				</div>
			</div>
		</div>

		<?php
		include_once ('templates/addUser.php');
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/user.js" type="text/javascript"></script>
		<script src="js/common.js" type="text/javascript"></script>
	</body>
</html>