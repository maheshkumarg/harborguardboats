<div class="modal fade" data-backdrop="static" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="post" role="form" class="form-horizontal" id="addMaterialFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Add Material</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">Boat Number<span class="required">*</span></label>
						<div class="col-sm-8">
							<select class="form-control" name="prodId" id="prodId" required>
								<option value="">Select</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Name<span class="required">*</span></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="name" id="name" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Description</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="description" id="description" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Part Number<span class="required">*</span></label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="partNumber" id="partNumber" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Barcode</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="barcode" id="barcode" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Vendor Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="vendorName" id="vendorName" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Vendor Part Number</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="vendorPartNum" id="vendorPartNum" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Actual Quantity</label>
						<div class="col-sm-8">
							<input type="number"  class="form-control" name="actualQty" id="actualQty" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Actual Unit Price</label>
						<div class="col-sm-8">
							<input type="number"  class="form-control" name="actualUnitPrice" id="actualUnitPrice" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-lg-4 control-label">Standard Quantity</label>
						<div class="col-lg-8">
							<input type="number" class="form-control" name="stdQty" id="stdQty" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Standard Unit Price</label>
						<div class="col-sm-8">
							<input type="number"  class="form-control" name="stdUnitPrice" id="stdUnitPrice" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="saveMaterialBtn" name="saveMaterialBtn">
						Save
					</button>
				</div>
			</div>
		</form>
	</div>
</div>