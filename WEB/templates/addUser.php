<div class="modal fade" data-backdrop="static" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form method="get" class="form-horizontal" id="addUserFrm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">Add User</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="col-sm-4 control-label">First Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="firstName" name="firstName" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Last Name</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="lastName" name="lastName" required="" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Email</label>
						<div class="col-sm-8">
							<input type="email" class="form-control" name="email" id="email" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="password" name="password" required="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Confirm Password</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="confirm_password" name='confirm_password' />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Rate Per Hour</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" name="ratePerHour" id="ratePerHour" />
						</div>
					</div>
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Designation</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" name="designation" id="designation" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">Phone Number</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" name="phoneNumber" id="phoneNumber" />
						</div>
					</div>
					<!-- <div class="hr-line-dashed"></div>
					<div class="form-group">
					<label class="col-sm-4 control-label">Profile Pic</label>
					<div class="col-sm-8">
					<input type="file" class="form-control" name="profile-pic" id="profile-pic">
					</div>
					</div> -->
					<div class="hr-line-dashed"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label">User Type</label>
						<div class="col-sm-8">
							<select class="form-control" name="userType" id="userType"  required="">
								<option value="">Select</option>
								<option value="Admin">Admin</option>
								<option value="Employee">Employee</option>
							</select>
						</div>
					</div>

				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary" id="saveUserBtn">
						Save
					</button>
				</div>
			</div>
		</form>
	</div>
</div>