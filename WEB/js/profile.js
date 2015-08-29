/**
 * @author Mahesh
 */

$(document).ready(function() {
	getProfile();
});

function getProfile() {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/user/" + logged_in_user_id,
		dataType : "json", // data type of response
		success : renderProfile
	});
}


$(document).on("submit", "form#chngePasswdFrm", function() {
	changePassword();
	return false;
});

function changePassword() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/users/password/" + logged_in_user_id,
		dataType : "json",
		data : formToJSON("changePasswd"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.userId) {
					document.getElementById("chngePasswdFrm").reset();
					$('#chngePasswdModal').modal('hide');
					bootbox.alert("Password Updated Successfully");
				} else {
					bootbox.alert("Password Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("Password Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Password Update Failed!! Please try again");
		}
	});
}


$(document).on("submit", "form#myProfileFrm", function() {
	updateUser();
	return false;
});

function updateUser() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/users/" + logged_in_user_id,
		dataType : "json",
		data : formToJSON("profile"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.firstName) {
					bootbox.alert("Profile Updated Successfully");
				} else {
					bootbox.alert("Profile Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("Profile Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Profile Update Failed!! Please try again");
		}
	});
}

function formToJSON(pageName) {
	if (pageName === "changePasswd") {
		return JSON.stringify({
			"password" : $('#password').val()
		});
	} else {
		return JSON.stringify({
			"updatedBy" : logged_in_user_id,
			"firstName" : $('#firstName').val(),
			"lastName" : $('#lastName').val(),
			"email" : $('#email').val(),
			"designation" : $('#desig').val(),
			"phoneNumber" : $('#phoneNumber').val(),
			"userType" : $("#userType").val()
		});
	}
}

function renderProfile(data) {
	var user = data.user[0];
	$('#firstName').val(user.firstName);
	$('#lastName').val(user.lastName);
	$('#email').val(user.email);
	$('#desig').val(user.designation);
	$('#phoneNumber').val(user.phoneNumber);
	$("#userType").val(user.userType);
}
