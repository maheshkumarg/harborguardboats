/**
 * @author Mahesh
 */

var aMaterialIdsForEdit = null,
    iProcessGrpsForEdit = null;

$(document).ready(function() {
	findAllProducts();
	$('#starttime,#endtime').datetimepicker({
		format : 'Y-m-d H:i:s'
	});

	$("select#prodId").change(function(e) {
		$('select#materialIds').empty();
		if ($(this).val().length > 0)
			getMaterials($(this).val());
		$('select#processGrpId').find("option:gt(0)").remove();
		if ($(this).val().length > 0)
			getAllProcessGroups($(this).val());
	});

	$("select#prodctId").change(function(e) {
		$("div#statusMsg,div#error").text("");
	});
});

$(document).on("submit", "form#addProcessGrpFrm", function() {
	$("div#error").html("");
	addProcessGroup();
	return false;
});

$(document).on("submit", "form#addProcessFrm", function() {
	if ($("#processId").length) {
		updateProcess();
	} else {
		if (validateProcessForm()) {
			addProcess();
		}
	}
	return false;
});

function validateProcessForm() {
	var starttime = $("input#starttime").val(),
	    endtime = $("input#endtime").val(),
	    isValid = true;

	if (starttime.length > 0) {
		if (endtime.length < 1) {
			alert("Please select a valid EndTime");
			return false;
		}
	}

	var today = new Date();
	today.setHours(0);
	today.setMinutes(0);
	today.setSeconds(0);

	if (new Date(starttime) < today) {
		alert("StartTime cannot be a past date");
		return false;
	}

	if (endtime.length > 0) {
		if (starttime.length < 1) {
			alert("Please select a valid StartTime");
			return false;
		}
	}

	today.setHours(23);
	today.setMinutes(59);
	today.setSeconds(59);

	if (new Date(endtime) > today) {
		alert("EndTime cannot be a past date");
		return false;
	}

	if (new Date(starttime) >= new Date(endtime)) {
		alert("StartTime cannot be greater or equal to EndTime");
		return false;
	}

	return isValid;
}

function updateProcess() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/process/update/" + $("#processId").val(),
		dataType : "json",
		data : formToJSON("add"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.processId) {
					document.getElementById("addProcessFrm").reset();
					$('#addProcessModal').modal('hide');
					$("select#productId").trigger("change");
					bootbox.alert("Process Updated Successfully");
				} else {
					bootbox.alert("Process Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("Process Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Process Update Failed!! Please try again");
		}
	});
}

function addProcess() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/process",
		dataType : "json",
		data : formToJSON("add"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.processId) {
					document.getElementById("addProcessFrm").reset();
					$("select#productId").trigger("change");
					$('#addProcessModal').modal('hide');
					bootbox.alert("Process Added Successfully");
				} else if (data.error) {
					$("div#error").html(data.error);
				}
			} else {
				bootbox.alert("Add Process Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Add Process Failed!! Please try again");
		}
	});
}


$(document).on("click", "i.edit", function(e) {
	$that = $(this);
	$("#addProcessFrm").append("<input type='hidden' value='" + $(this).data("id") + "' class='form-control' name='processId' id='processId'>");
	$('#addProcessModal').modal('show');
	$(".hide-for-edit").hide();
	$("#myModalLabel").text("Edit process");
	$("#saveProcessBtn").text("Update");
	getProcessById($(this).data("id"));
});

function getProcessById(processId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/process/edit/" + processId,
		dataType : "json", // data type of response
		success : function(json) {
			renderProcess(json, processId);
		}
	});
}

function renderProcess(data, processId) {
	var process = data.process[0];
	$("#processId").val(processId);
	$('#prodId').val(process.productId).trigger("change");
	$('#processname').val(process.name);

	// var d = new Date(process.startTime);
	// if (d != "Invalid Date") {
	// $('#starttime').datetimepicker({
	// format : 'Y-m-d H:i:s',
	// value : process.startTime
	// });
	// }
	//
	// d = new Date(process.endTime);
	// if (d != "Invalid Date") {
	// $('#endtime').datetimepicker({
	// format : 'Y-m-d H:i:s',
	// value : process.endTime
	// });
	// }
	//
	// var obj = {};
	if (process.processGroupId) {
		iProcessGrpsForEdit = process.processGroupId;
	}

	$("div#overlay,p#loading").hide();
	// if (process.userId) {
	// obj['userIdsForEdit'] = process.userId.split(",");
	// };
	//
	// if (process.materialIds) {
	// aMaterialIdsForEdit = process.materialIds.split(",");
	// };

	//initForm(obj);
}

function initForm(data) {
	$.when(getUsers(function() {
		if (data) {
			$('select#employees').val(data['userIdsForEdit']);
		}
	})).always(function() {
		$("div#overlay,p#loading").hide();
	});
}


$('#addProcessModal').on('show.bs.modal', function() {
	$("div#overlay,p#loading").show();
	initForm();
});

$('#addProcessModal').on('hidden.bs.modal', function() {
	document.getElementById("addProcessFrm").reset();
	$("select#prodId").trigger("change");
	$("#processId").remove();
	$("div#statusMsg,div#error").text("");
	$(".hide-for-edit").show();
	$("#myModalLabel").text("Add Process");
	$("#saveProcessBtn").text("Save");
});

function getMaterials(prodId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/materials/" + prodId,
		dataType : "json", // data type of response
		success : renderMaterialsList
	});
}

function getUsers(callbackFn) {
	if ($("select#employees").find("option").length > 0) {
		if (callbackFn) {
			callbackFn();
		}
		return false;
	}

	return $.ajax({
		type : 'GET',
		url : baseURL + "index.php/users/Employee",
		dataType : "json" // data type of response
	}).always(function(response, status) {
		if (status === "success") {
			renderUsersList(response);
		}
		if (callbackFn) {
			callbackFn();
		}
	});
}

function getAllProcessGroups(prodId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/process/groups/" + prodId,
		dataType : "json", // data type of response
		success : renderProcessGrpList
	});
}

function renderMaterialsList(data) {
	$.each(data.materials, function(index, material) {
		$('select#materialIds').append('<option value="' + material.id + '">' + material.name + '</option>');
	});
	$('select#materialIds').val(aMaterialIdsForEdit);
	aMaterialIdsForEdit = null;
}

function renderUsersList(data) {
	$.each(data.users, function(index, user) {
		var userName = user.firstName;
		if (user.lastName != null) {
			userName += " " + user.lastName;
		}
		$('select#employees').append('<option value="' + user.id + '">' + userName + '</option>');
	});
}

function renderProcessGrpList(data) {
	$.each(data.processgroups, function(index, processGrp) {
		$('select#processGrpId').append('<option value="' + processGrp.id + '">' + processGrp.name + '</option>');
	});

	$('select#processGrpId').val(iProcessGrpsForEdit);
	iProcessGrpsForEdit = null;
}


$(document).on("click", "i.delete", function(e) {
	$that = $(this);
	bootbox.dialog({
		message : "Are you sure you want to delete?",
		title : "Delete Process",
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
						url : baseURL + "index.php/process/delete/",
						dataType : "json",
						data : JSON.stringify({
							processIds : $that.data("id"),
							deletedBy : logged_in_user_id
						}),
						success : function(data) {
							if (data) {
								if (data.processIds) {
									bootbox.alert("Process deleted successfully", function() {
										$("select#productId").trigger("change");
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

function renderProductList(data) {
	$.each(data.products, function(index, product) {
		$('#productId').add("#prodId").add("#prodctId").append('<option value="' + product.id + '">' + product.name + '</option>');
	});

	var i = 1;
	$processDT = $('#process').dataTable({
		dom : 'T<"clear">lfrtip',
		tableTools : {
			sSwfPath : "plugins/DataTables-1.10.6/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
			aButtons : [{
				sExtends : "xls",
				mColumns : [0, 1, 2, 3, 4, 5],
				aButtons : ['xls'],
				sFileName : "*.xls",
				sButtonText : "Export (Excel)",
				sButtonClass : "exportBtn"
			}]
		},
		"ajax" : {
			"url" : baseURL + "index.php/process/" + $('#productId').val(),
			"dataSrc" : "process"
		},
		"order" : [[0, "ASC"]],
		"columnDefs" : [{
			"data" : null,
			"targets" : 0,
			"orderable" : false,
			"className" : "text-center",
			"createdCell" : function(td, cellData, rowData, row, col) {
				$(td).html("<i class='edit fa fa-pencil' data-id='" + rowData['processIds'] + "'></i><i data-id='" + rowData['processIds'] + "' class='delete fa fa-trash'></i>");
			}
		}, {
			"data" : "processGrpName",
			"targets" : 1
		}, {
			"data" : "processName",
			"targets" : 2
		}, {
			"data" : "laborHours",
			"targets" : 3
		}, {
			"data" : "totalCost",
			"targets" : 4,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData > 0) {
					$(td).html(cellData + "$");
				} else {
					$(td).html("");
				}
			}
		}, {
			"data" : "employees",
			"targets" : 5,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData) {
					if (cellData.length > 0) {
						var sHTML = '';
						for (var i = 0; i < cellData.length; i++) {
							if (cellData[i].name) {
								sHTML += "<span class='name'>" + cellData[i].name + "</span>";
							}
						}
						$(td).html(sHTML);
					}
				}
			}
		}]
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON(whichpage) {
	if (whichpage == "add") {
		var process = {
			"productId" : $('#prodId').val(),
			"name" : $('#processname').val(),
			"processGroupId" : $('#processGrpId').val() != "" ? $('#processGrpId').val() : null,
			"materialIds" : $('#materialIds').val() != null ? $('#materialIds').val().join(",") : null,
			"startTime" : $('#starttime').val(),
			"endTime" : $('#endtime').val(),
			"userId" : $('#employees').val() != null ? $('#employees').val().join(",") : null
		};

		if ($('input#processId').length) {
			process["processId"] = $('#processId').val();
			process['updatedBy'] = logged_in_user_id;
			delete process['materialIds'];
			delete process['startTime'];
			delete process['endTime'];
			delete process['userId'];
		} else {
			process["createdBy"] = logged_in_user_id;
		}

		return JSON.stringify(process);
	}
	var processGrp = {
		"name" : $('#name').val(),
		"productId" : $("#prodctId").val(),
		"description" : $('#description').val(),
		"createdBy" : logged_in_user_id
	};

	return JSON.stringify(processGrp);
}
