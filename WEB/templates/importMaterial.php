<div class="modal fade" data-backdrop="static" id="importMaterialsModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form action="http://localhost/~Mahesh/Admin/importMaterials.php" method="post" name="uploadMaterialsFrm" id="uploadMaterialsFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Import Materials</h4>
				</div>
				<div class="modal-body">
					<input type="file" name="file" id="file" class="input-large">
				</div>
				<div class="modal-footer">
					<div id="error" class="pull-left"></div>
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>
					<button type="submit" id="Import" name="Import" class="btn btn-primary">
						Upload
					</button>
				</div>
			</div>
		</form>
	</div>
</div>