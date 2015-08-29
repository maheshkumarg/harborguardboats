/**
 * @author Mahesh
 */
$(document).ready(function() {
	findAllProducts();
});

$(document).on("submit", "form#addProcessGrpFrm", function() {
	$("div#error").html("");
	if ($("#processGrpId").length) {
		updateProcessGroup();
	} else {
		addProcessGroup();
	}
	return false;
});

function renderProductList(data) {
	$.each(data.products, function(index, product) {
		$('#productId').add("#prodctId").append('<option value="' + product.id + '">' + product.name + '</option>');
	});

	var i = 1;
	$processGrpDT = $('#processgrps').dataTable({
		dom : 'T<"clear">lfrtip',
		tableTools : {
			sSwfPath : "plugins/DataTables-1.10.6/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
			aButtons : [{
				sExtends : "xls",
				mColumns : [0, 1, 2],
				aButtons : ['xls'],
				sFileName : "*.xls",
				sButtonText : "Export (Excel)",
				sButtonClass : "exportBtn"
			}]
		},
		"ajax" : {
			"url" : baseURL + "index.php/process/groups/" + $('#productId').val(),
			"dataSrc" : "processgroups"
		},
		"order" : [[0, "ASC"]],
		"columnDefs" : [{
			"data" : null,
			"targets" : 0,
			"orderable" : false,
			"className" : "text-center",
			"createdCell" : function(td, cellData, rowData, row, col) {
				$(td).html("<i class='edit fa fa-pencil' data-id='" + rowData['id'] + "'></i><i data-id='" + rowData['id'] + "' class='delete fa fa-trash'></i>");
			}
		}, {
			"data" : "name",
			"targets" : 1
		}, {
			"data" : "description",
			"targets" : 2
		}]
	});
}


$(document).on("click", "i.delete", function(e) {
	$that = $(this);
	bootbox.dialog({
		message : "Are you sure you want to delete?",
		title : "Delete Process Group",
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
						url : baseURL + "index.php/process/group/delete/" + $that.data("id"),
						dataType : "json",
						data : JSON.stringify({
							deletedBy : logged_in_user_id
						}),
						success : function(data) {
							if (data) {
								if (data.processGrpId) {
									bootbox.alert("Process Group deleted successfully", function() {
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

$(document).on("click", "i.edit", function(e) {
	$that = $(this);
	$("#addProcessGrpFrm").append("<input type='hidden' value='" + $(this).data("id") + "' class='form-control' name='processGrpId' id='processGrpId'>");
	$('#addProcessGroupModal').modal('show');
	$("#myModalLabel").text("Edit process group");
	$("#saveProcessGrpBtn").text("Update");
	getProcessGrpById($(this).data("id"));
});

function getProcessGrpById(processGrpId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/process/group/edit/" + processGrpId,
		dataType : "json", // data type of response
		success : renderProcessGroup
	});
}

function renderProcessGroup(data) {
	var processGrp = data.processgroup[0];
	$("#processGrpId").val(processGrp.id);
	$('#prodctId').val(processGrp.productId);
	$('#name').val(processGrp.name);
	$('#description').val(processGrp.description);
}


$('#addProcessGroupModal').on('hidden.bs.modal', function() {
	document.getElementById("addProcessGrpFrm").reset();
	$("#processGrpId").remove();
	$("div#statusMsg,div#error").text("");
	$("#myModalLabel").text("Add Process Group");
	$("#saveProcessGrpBtn").text("Save");
});

function addProcessGroup() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/process/group",
		dataType : "json",
		data : formToJSON("add"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.groupId) {
					document.getElementById("addProcessGrpFrm").reset();
					$('#addProcessGroupModal').modal('hide');
					$("select#productId").trigger("change");
					bootbox.alert("Process Group Added Successfully");
				} else if (data.error) {
					$("div#error").html(data.error);
				}
			} else {
				bootbox.alert("Add Process Group Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Add Process Group Failed!! Please try again");
		}
	});
}

function updateProcessGroup() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/process/group/update/" + $("#processGrpId").val(),
		dataType : "json",
		data : formToJSON("update"),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.processgroup) {
					document.getElementById("addProcessGrpFrm").reset();
					$('#addProcessGroupModal').modal('hide');
					$("select#productId").trigger("change");
					bootbox.alert("Process Group Updated Successfully");
				} else {
					bootbox.alert("Process Group Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("Process Group Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Process Group Update Failed!! Please try again");
		}
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON(whichpage) {
	var processGrp = {
		"name" : $('#name').val(),
		"productId" : $("#prodctId").val(),
		"description" : $('#description').val()
	};
	if (whichpage == "add") {
		processGrp["createdBy"] = logged_in_user_id;
	} else {
		processGrp["updatedBy"] = logged_in_user_id;
	}

	return JSON.stringify(processGrp);
}
