<?php
include_once ('lock.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title>HarborGuardBoats | Process Groups</title>
		<?php
		$current = "processgrps";
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
						<h2>Process Groups
						<button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#addProcessGroupModal">
							Add Process Group
						</button></h2><h3>Boat Number: <select name="productId" id="productId"></select></h3>
						<table id="processgrps" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Name</th>
									<th>Description</th>
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
		include_once ('templates/addProcessGroup.php');
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/common.js" type="text/javascript"></script>
		<script src="js/processgrps.js" type="text/javascript"></script>
	</body>
</html>