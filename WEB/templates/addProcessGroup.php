<div class="modal fade" data-backdrop="static" id="addProcessGroupModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="get" class="form-horizontal" id="addProcessGrpFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Add Process Group</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">Boat Number<span class="required">*</span></label>
						<div class="col-sm-8">
							<select class="form-control" name="prodctId" id="prodctId" required="">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Name<span class="required">*</span></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="name" name="name" required="" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Description</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="description" name="description">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="error" style='color:#cc0000' class="pull-left"></div>
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>
					<button type="submit" class="btn btn-primary" id="saveProcessGrpBtn" name="saveProcessGrpBtn">
						Save
					</button>
				</div>
			</div>
		</form>
	</div>
</div>