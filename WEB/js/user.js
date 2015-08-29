var $userDT = null;
$(document).ready(function() {
	$userDT = $('#users').dataTable({
		dom : 'lfrtip',
		"ajax" : {
			"url" : baseURL + "index.php/users/all",
			"dataSrc" : "users"
		},
		order : [[1, "ASC"]],
		"columnDefs" : [{
			"data" : null,
			"targets" : 0,
			"orderable" : false,
			"className" : "text-center",
			"createdCell" : function(td, cellData, rowData, row, col) {
				$(td).html("<i class='edit fa fa-pencil' data-id='" + rowData['id'] + "'></i><i data-id='" + rowData['id'] + "' class='delete fa fa-trash'></i>");
			}
		}, {
			"data" : "firstName",
			"targets" : 1,
			"createdCell" : function(td, cellData, rowData, row, col) {
				var name = rowData.firstName;
				if (rowData.middleName)
					name += " " + rowData.middleName;
				if (rowData.lastName)
					name += " " + rowData.lastName;
				$(td).html(name);
			}
		}, {
			"data" : "email",
			"targets" : 2
		}, {
			"data" : "designation",
			"targets" : 3
		}, {
			"data" : "ratePerHour",
			"targets" : 4,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData == 0) {
					$(td).html("-");
				}
			}
		}, {
			"data" : "phoneNumber",
			"targets" : 5
		}, {
			"data" : "userType",
			"targets" : 6
		}, {
			"data" : "deleted",
			"targets" : 7,
			"createdCell" : function(td, cellData, rowData, row, col) {
				$(td).html(cellData == 0 ? "Active" : "Not Active");
			}
		}]
	});
});

$(document).on("click", "i.delete", function(e) {
	$that = $(this);
	bootbox.dialog({
		message : "Are you sure you want to delete?",
		title : "Delete User",
		buttons : {
			success : {
				label : "No",
				className : "btn",
				callback : function() {

				}
			},
			danger : {
				label : "Yes",
				className : "btn-danger",
				callback : function() {
					$.ajax({
						type : 'POST',
						url : baseURL + "index.php/users/delete/" + $that.data("id"),
						data : JSON.stringify({
							deletedBy : logged_in_user_id
						}),
						dataType : "json",
						success : function(data) {
							if (data) {
								if (data.userId) {
									bootbox.alert("User deleted successfully", function() {
										$userDT.api().ajax.reload();
									});
								}
							} else {
								bootbox.alert("Oops!! Please try again");
							}
						}
					});
				}
			}
		}
	});
});

$('#addUserModal').on('hidden.bs.modal', function() {
	$("input#password").prop("required", false);
	document.getElementById("addUserFrm").reset();
	$("#userId").remove();
	$("div#statusMsg").text("");
	$("#myModalLabel").text("Add User");
	$("#saveUserBtn").text("Save");
});

$(document).on("click", "i.edit", function(e) {
	$that = $(this);
	$("#addUserFrm").append("<input type='hidden' value='" + $(this).data("id") + "' class='form-control' name='userId' id='userId'>");
	$('#addUserModal').modal('show');
	$("#myModalLabel").text("Edit User");
	$("#saveUserBtn").text("Update");
	getUserById($(this).data("id"));
	$("input#password").prop("required", false);
});

function getUserById(userId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/user/" + userId,
		dataType : "json", // data type of response
		success : renderUser
	});
}

function renderUser(data) {
	var user = data.user[0];
	$("#userId").val(user.id);
	$('#firstName').val(user.firstName);
	$('#lastName').val(user.lastName);
	$('#email').val(user.email);
	$('#designation').val(user.designation);
	$('#phoneNumber').val(user.phoneNumber);
	$('#userType').val(user.userType);
	$('#ratePerHour').val(user.ratePerHour);
}


$(document).on("submit", "form#addUserFrm", function() {
	if ($("#userId").length) {
		updateUser();
	} else {
		addUser();
	}
	return false;
});

function updateUser() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/users/" + $("#userId").val(),
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.firstName) {
					document.getElementById("addUserFrm").reset();
					$userDT.api().ajax.reload();
					$('#addUserModal').modal('hide');
					bootbox.alert("User Updated Successfully");
				} else if (data.error) {
					bootbox.alert(data.error);
				} else {
					bootbox.alert("User Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("User Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("User Update Failed!! Please try again");
		}
	});
}

function addUser() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/users",
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.userId) {
					document.getElementById("addUserFrm").reset();
					$userDT.api().ajax.reload();
					$('#addUserModal').modal('hide');
					bootbox.alert("User Added Successfully");
				} else if (data.error) {
					bootbox.alert(data.error);
				} else {
					bootbox.alert("Add User Failed!! Please try again");
				}
			} else {
				bootbox.alert("Add User Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Add User Failed!! Please try again");
		}
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON() {
	var user = {
		"firstName" : $('#firstName').val(),
		"lastName" : $('#lastName').val(),
		"email" : $('#email').val(),
		"password" : $('#password').val(),
		"designation" : $('#designation').val(),
		"phoneNumber" : $('#phoneNumber').val(),
		"userType" : $('#userType').val(),
		"ratePerHour" : $('#ratePerHour').val()
	};

	if ($('input#userId').length) {
		user["userId"] = $('#userId').val();
		user['updatedBy'] = logged_in_user_id;
	} else {
		user["createdBy"] = logged_in_user_id;
	}

	return JSON.stringify(user);
}