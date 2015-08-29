<div class="modal fade" data-backdrop="static" id="addProcessModal" data-keyboard="false" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div id='overlay'></div>
		<p id='loading'>
			<button disabled class='btn-success'>
				Please wait
			</button>
		</p>
		<form method="get" class="form-horizontal" id="addProcessFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Add Process</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">Boat Number<span class="required">*</span></label>
						<div class="col-sm-8">
							<select class="form-control" name="prodId" id="prodId" required="">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Process Name <span class="required">*</span></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="processname" name="processname" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Process Group<span class="required">*</span></label>
						<div class="col-sm-8">
							<select class="form-control" name="processGrpId" id="processGrpId" required="">
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group hide-for-edit">
						<label class="col-sm-4 control-label">Materials</label>
						<div class="col-sm-8">
							<select class="form-control" name="materialIds" id="materialIds" multiple></select>
						</div>
					</div>
					<div class="form-group hide-for-edit">
						<label class="col-sm-4 control-label">Start Time</label>
						<div class="col-sm-8">
							<div class="input-group">
								<input type="text" class="form-control" id="starttime" name="starttime" />
								<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							</div>
						</div>
					</div>
					<div class="form-group hide-for-edit">
						<label class="col-sm-4 control-label">End Time</label>
						<div class="col-sm-8">
							<div class="input-group">
								<input type="text" class="form-control" id="endtime" name="endtime" />
								<span class="input-group-addon"> <i class="fa fa-calendar"></i> </span>
							</div>
						</div>
					</div>
					<div class="hr-line-dashed hide-for-edit"></div>
					<div class="form-group hide-for-edit">
						<label class="col-sm-4 control-label">Employees</label>
						<div class="col-sm-8">
							<select class="form-control" name="employees" id="employees" multiple></select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div id="error" style='color:#cc0000' class="pull-left"></div>
					<button type="button" class="btn btn-default" data-dismiss="modal">
						Cancel
					</button>
					<button type="submit" class="btn btn-primary" id="saveProcessBtn" name="saveProcessBtn">
						Save
					</button>
				</div>
			</div>
		</form>
	</div>
</div>