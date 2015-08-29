<?php
include_once ('lock.php');
?>
<!doctype html>
<html>
	<head>
		<title>Harborguardboats | Materials</title>
		<?php
		$current = "materials";
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
						<h2>Materials
						<button type="button" style="margin:0 10px" class="btn pull-right btn-primary" data-toggle="modal" data-target="#addMaterialModal">
							Add Material
						</button>
						<button type="button" class="btn pull-right btn-primary" data-toggle="modal" data-target="#importMaterialsModal">
							Import Materials
						</button></h2><h3>Boat Number: <select name="productId" id="productId"></select></h3>
						<table id="materials" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th></th>
									<th>Name</th>
									<th>Description</th>
									<th>Barcode</th>
									<th>PartNumber</th>
									<th>Vendor Name</th>
									<th>Vendor Part Number</th>
									<th>Actual Qty</th>
									<th>Actual Unit Price</th>
									<th>Actual Total Price</th>
									<th>Standard Qty</th>
									<th>Standard Unit Price</th>
									<th>Standard Total Price</th>
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
		include_once ('templates/addMaterial.php');
		include_once ('templates/importMaterial.php');
		include_once ('templates/bottomScripts.php');
		?>
		<script src="js/material.js" type="text/javascript"></script>
		<script src="js/common.js" type="text/javascript"></script>
		<script src="plugins/waitingDialog/js/waitingDialog.js" type="text/javascript"></script>
	</body>
</html>