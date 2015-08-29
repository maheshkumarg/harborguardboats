<?php
include_once ('lock.php');
?>
<!doctype html>
<html>
	<head>
		<title>Harborguardboats | Boats</title>
		<?php
		$current = "boats";
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
				<div class="wrapper wrapper-content animated fadeinright" >
					<div class="row">
						<h2>Boats
						<button type="button" style="margin:0 10px" class="btn pull-right btn-primary" data-toggle="modal" data-target="#addBoatModal">
							Add Boat
						</button></button></h2>
						<table id="boats" class="table table-striped table-bordered" cellspacing="0" width="100%">
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
		include_once ('templates/addBoat.php');
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/boat.js" type="text/javascript"></script>
	</body>
</html>