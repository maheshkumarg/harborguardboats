<?php
include_once ('lock.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>HarborGuardBoats | Process</title>
		<?php
		$current = "process";
		include_once ('templates/headerIncludes.php');
		?>
		<link rel="stylesheet" type="text/css" href="plugins/timepicker/jquery.datetimepicker.css"/ >
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
						<h2>Process
						<button type="button" style="margin:0 10px" class="btn pull-right btn-primary" data-toggle="modal" data-target="#addProcessModal">
							Add Process
						</button></button></h2><h3>Boat Number: <select name="productId" id="productId"></select></h3>
						<table id="process" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Process Group</th>
									<th>Process</th>
									<th>Labor Hours</th>
									<th>Total Cost</th>
									<th>Employees</th>
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
		include_once ('templates/addProcess.php');
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/common.js" type="text/javascript"></script>
		<script src="js/process.js" type="text/javascript"></script>
		<script type="text/javascript" src="plugins/timepicker/jquery.datetimepicker.js"></script>
	</body>
</html>