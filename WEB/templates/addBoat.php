<div class="modal fade" data-backdrop="static" id="addBoatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" role="form" class="form-horizontal" id="addBoatFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Add Boat</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="name" id="name" required="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Description</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="description" id="description">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="error" style='color:#cc0000' class="pull-left"></div>
					<button type="submit" class="btn btn-primary" id="saveBoatBtn" name="saveBoatBtn">
						Save
					</button>
				</div>
			</div>
		</form>
	</div>
</div>