/**
 * @author Mahesh
 */

var $materialDT = null;

$(document).ready(function() {
	findAllProducts();
});

var allowedExtensions = {
	'.xls' : 1,
	'.csv' : 1,
	'.xlsx' : 1
};

function checkExtension(filename) {
	var match = /\..+$/;
	var ext = filename.match(match);
	if (allowedExtensions[ext]) {
		return true;
	} else {
		$("div#error").html("<span style='color:#cc0000'>File must be of type .csv or .xls or .xlsx!</span>");
		return false;
	}
};

$("input#file").change(function(e) {
	$("div#error").html("");
});

$("form#uploadMaterialsFrm").on('submit', (function(e) {
	e.preventDefault();
	if (checkExtension($("input#file").val())) {
		$.ajax({
			url : baseURL + "upload.php",
			type : "POST",
			data : new FormData(this),
			beforeSend : function() {
				waitingDialog.show("Uploading Materials. Please wait");
			},
			contentType : false,
			cache : false,
			processData : false,
			success : function(data) {
				document.getElementById("uploadMaterialsFrm").reset();
				$('#importMaterialsModal').modal('hide');
				bootbox.alert(data);
				$(".bootbox-body").css("max-height", "300px");
				$(".bootbox-body").css("overflow", "scroll");
				$("select#productId").trigger("change");
			},
			error : function() {
				$("div#error").html("<span style='color:#cc0000'>Upload Materials Failed!! Please try again</span>");
			},
			complete : function() {
				waitingDialog.hide();
			}
		});
	}
}));

$('#addMaterialModal').on('hidden.bs.modal', function() {
	document.getElementById("addMaterialFrm").reset();
	$("#materialId").remove();
	$("div#statusMsg").text("");
	$("#myModalLabel").text("Add Material");
	$("#saveMaterialBtn").text("Save");
});

$(document).on("click", "i.edit", function(e) {
	$that = $(this);
	$("#addMaterialFrm").append("<input type='hidden' value='" + $(this).data("id") + "' class='form-control' name='materialId' id='materialId'>");
	$('#addMaterialModal').modal('show');
	$("#myModalLabel").text("Edit Material");
	$("#saveMaterialBtn").text("Update");
	getMaterialById($(this).data("id"));
});

$(document).on("click", "i.delete", function(e) {
	$that = $(this);
	bootbox.dialog({
		message : "Are you sure you want to delete?",
		title : "Delete Material",
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
						url : baseURL + "index.php/materials/delete/" + $that.data("id"),
						dataType : "json",
						data : JSON.stringify({
							deletedBy : logged_in_user_id
						}),
						success : function(data) {
							if (data) {
								if (data.materialId) {
									bootbox.alert("Material deleted successfully", function() {
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

$('#addMaterialModal').on('show.bs.modal', function(e) {
	setTimeout(function() {
		$("#name").focus();
	}, 500);
});

function getMaterialById(materialId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/material/" + materialId,
		dataType : "json", // data type of response
		success : renderMaterial
	});
}

function renderMaterial(data) {
	var material = data[0];
	$("#materialId").val(material.id);
	$('#prodId').val(material.productId);
	$('#name').val(material.name);
	$('#description').val(material.description);
	$('#partNumber').val(material.partNumber);
	$('#barcode').val(material.barcode);
	$('#actualQty').val(material.actualQty);
	$('#actualUnitPrice').val(material.actualUnitPrice);
	$('#stdQty').val(material.stdQty);
	$('#stdUnitPrice').val(material.stdUnitPrice);
	$('#vendorName').val(material.vendorName);
	$('#vendorPartNum').val(material.vendorPartNumber);
}

function renderProductList(data) {
	$.each(data.products, function(index, product) {
		$('#productId').add("#prodId").append('<option value="' + product.id + '">' + product.name + '</option>');
	});

	$('#productId').find("option:eq(0)").prop("selected", true);

	$materialDT = $('#materials').dataTable({
		dom : 'T<"clear">lfrtip',
		tableTools : {
			sSwfPath : "plugins/DataTables-1.10.6/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
			aButtons : [{
				sExtends : "xls",
				mColumns : [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
				aButtons : ['xls'],
				sFileName : "*.xls",
				sButtonText : "Export (Excel)",
				sButtonClass : "exportBtn"
			}]
		},
		"ajax" : {
			"url" : baseURL + "index.php/materials/" + $('#productId').val(),
			"dataSrc" : "materials"
		},
		"order" : [[2, "ASC"]],
		"columnDefs" : [{
			"data" : null,
			"width" : "4%",
			"targets" : 0,
			"orderable" : false,
			"className" : "text-center",
			"createdCell" : function(td, cellData, rowData, row, col) {
				$(td).html("<i class='edit fa fa-pencil' data-id='" + rowData['id'] + "'></i><i data-id='" + rowData['id'] + "' class='delete fa fa-trash'></i>");
			}
		}, {
			"data" : "name",
			"targets" : 1,
			"width" : "20%"
		}, {
			"data" : "description",
			"width" : "10%",
			"targets" : 2
		}, {
			"data" : "barcode",
			"width" : "12%",
			"targets" : 3
		}, {
			"data" : "partNumber",
			"width" : "12%",
			"targets" : 4
		}, {
			"data" : "vendorName",
			"width" : "12%",
			"targets" : 5
		}, {
			"data" : "vendorPartNumber",
			"width" : "12%",
			"targets" : 6
		}, {
			"data" : "actualQty",
			"width" : "3%",
			"targets" : 7,
			"visible" : false
		}, {
			"data" : "actualUnitPrice",
			"width" : "3%",
			"targets" : 8,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData > 0) {
					$(td).html("$" + cellData);
				} else {
					$(td).html("");
				}
			},
			"visible" : false
		}, {
			"data" : "actualTotalPrice",
			"width" : "3%",
			"targets" : 9,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData > 0) {
					$(td).html("$" + cellData);
				} else {
					$(td).html("");
				}
			}
		}, {
			"data" : "stdQty",
			"width" : "3%",
			"targets" : 10,
			"visible" : false
		}, {
			"data" : "stdUnitPrice",
			"width" : "3%",
			"targets" : 11,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData > 0) {
					$(td).html("$" + cellData);
				} else {
					$(td).html("");
				}
			},
			"visible" : false
		}, {
			"data" : "stdTotalPrice",
			"width" : "3%",
			"targets" : 12,
			"createdCell" : function(td, cellData, rowData, row, col) {
				if (cellData > 0) {
					$(td).html("$" + cellData);
				} else {
					$(td).html("");
				}
			}
		}]
	});
}


$(document).on("submit", "form#addMaterialFrm", function() {
	if ($("#materialId").length) {
		updateMaterial();
	} else {
		addMaterial();
	}
	return false;
});

function updateMaterial() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/materials/" + $("#materialId").val(),
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.materialId) {
					document.getElementById("addMaterialFrm").reset();
					$("select#productId").trigger("change");
					$('#addMaterialModal').modal('hide');
					bootbox.alert("Material Updated Successfully");
				} else {
					bootbox.alert("Material Update Failed!! Please try again");
				}
			} else {
				bootbox.alert("Material Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Material Update Failed!! Please try again");
		}
	});
}

function addMaterial() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/materials",
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.materialId) {
					document.getElementById("addMaterialFrm").reset();
					$("select#productId").trigger("change");
					$('#addMaterialModal').modal('hide');
					bootbox.alert("Material Added Successfully");
				} else {
					bootbox.alert("Add Material Failed!! Please try again");
				}
			} else {
				bootbox.alert("Add Material Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Add Material Failed!! Please try again");
		}
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON() {
	var material = {
		"productId" : $('#prodId').val(),
		"name" : $('#name').val(),
		"description" : $('#description').val(),
		"partNumber" : $('#partNumber').val(),
		"barcode" : $('#barcode').val(),
		"actualQty" : $('#actualQty').val(),
		"actualUnitPrice" : $('#actualUnitPrice').val(),
		"stdQty" : $('#stdQty').val(),
		"stdUnitPrice" : $('#stdUnitPrice').val(),
		"vendorName" : $('#vendorName').val(),
		"vendorPartNum" : $('#vendorPartNum').val()
	};

	if ($('input#materialId').length) {
		material["materialId"] = $('#materialId').val();
		material['updatedBy'] = logged_in_user_id;
	} else {
		material["createdBy"] = logged_in_user_id;
	}

	return JSON.stringify(material);
}