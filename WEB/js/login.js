var baseURL = "http://localhost/~Mahesh/Admin/";
// var baseURL = "http://medinaih.com/HarborGuardBoats/";
//var baseURL = "http://nutech-solutions.com/harborguardboats/";
$(document).on("submit", "form#login_form", function() {
	$.ajax({
		type : 'POST',
		url : baseURL + "loginController.php",
		cache : false,
		data : {
			"username" : $("input[name='username']").val(),
			"password" : $("input[name='password']").val()
		},
		success : function(data, textStatus, jqXHR) {
			json = JSON.parse(data);
			if (json) {
				if (json.userId) {
					location.href = baseURL + "materials.php";
				} else if (json.error) {
					$("#error").html("<span style='color:#cc0000'>" + json.error + "</span> ");
				}
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			$("#error").html("<span style='color:#cc0000'>Oops!: Please try again. </span> ");
		}
	});
	return false;
});
