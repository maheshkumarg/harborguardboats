/**
 * @author Mahesh
 */
var loc = location.href;
function findAllProducts() {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/products",
		dataType : "json", // data type of response
		success : renderProductList
	});
}


$("select#productId").change(function() {
	var apiDT = null,
	    sURL = "materials/" + $('#productId').val();
	if (loc.indexOf("process.php") > 0) {
		apiDT = $processDT;
		sURL = "process/" + $('#productId').val();
	} else if (loc.indexOf("processgrps.php") > 0) {
		sURL = "process/groups/" + $('#productId').val();
		apiDT = $processGrpDT;
	} else {
		apiDT = $materialDT;
	}

	sURL = baseURL + "index.php/" + sURL;
	apiDT.api().ajax.url(sURL);
	apiDT.api().ajax.reload();
});

if (loc.indexOf("users.php") >= 0 || loc.indexOf("profile.php") >= 0) {
	var password = document.getElementById("password"),
	    confirm_password = document.getElementById("confirm_password");

	function validatePassword() {
		if (password.value.length > 0 && password.value != confirm_password.value) {
			confirm_password.setCustomValidity("Passwords Don't Match");
		} else {
			confirm_password.setCustomValidity('');
		}
	}


	password.onchange = validatePassword;
	confirm_password.onkeyup = validatePassword;
}
