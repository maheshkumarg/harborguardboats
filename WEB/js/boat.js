/**
 * @author Mahesh
 */

var $boatDT = null;

$('#addBoatModal').on('hidden.bs.modal', function() {
	document.getElementById("addBoatFrm").reset();
	$("#boatId").remove();
	$("div#statusMsg,div#error").text("");
	$("#myModalLabel").text("Add Boat");
	$("#saveBoatBtn").text("Save");
});

$(document).on("click", "i.edit", function(e) {
	$that = $(this);
	$("#addBoatFrm").append("<input type='hidden' value='" + $(this).data("id") + "' class='form-control' name='boatId' id='boatId'>");
	$('#addBoatModal').modal('show');
	$("#myModalLabel").text("Edit Boat");
	$("#saveBoatBtn").text("Update");
	getBoatById($(this).data("id"));
});

$('#addBoatModal').on('show.bs.modal', function(e) {
	setTimeout(function() {
		$("#name").focus();
	}, 500);
});

function getBoatById(boatId) {
	$.ajax({
		type : 'GET',
		url : baseURL + "index.php/products/" + boatId,
		dataType : "json", // data type of response
		success : renderBoat
	});
}

function renderBoat(data) {
	var boat = data[0];
	$("#boatId").val(boat.id);
	$('#name').val(boat.name);
	$('#description').val(boat.description);
}


$(document).ready(function() {
	$boatDT = $('#boats').dataTable({
		dom : 'lfrtip',
		"ajax" : {
			"url" : baseURL + "index.php/products",
			"dataSrc" : "products"
		},
		"order" : [[1, "ASC"]],
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
});

$(document).on("click", "i.delete", function(e) {
	$that = $(this);
	bootbox.dialog({
		message : "Are you sure you want to delete?",
		title : "Delete Boat",
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
						url : baseURL + "index.php/products/delete/" + $that.data("id"),
						data : JSON.stringify({
							deletedBy : logged_in_user_id
						}),
						dataType : "json",
						success : function(data) {
							if (data) {
								if (data.productId) {
									bootbox.alert("Boat deleted successfully", function() {
										$boatDT.api().ajax.reload();
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

$(document).on("submit", "form#addBoatFrm", function() {
	if ($("#boatId").length) {
		updateBoat();
	} else {
		addBoat();
	}
	return false;
});

function updateBoat() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/products/" + $("#boatId").val(),
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.id) {
					document.getElementById("addBoatFrm").reset();
					$boatDT.api().ajax.reload();
					$('#addBoatModal').modal('hide');
					bootbox.alert("Boat Updated Successfully");
				} else if (data.error) {
					$("div#error").html(data.error);
				}
			} else {
				bootbox.alert("Boat Update Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Boat Update Failed!! Please try again");
		}
	});
}

function addBoat() {
	$.ajax({
		type : 'POST',
		contentType : 'application/json',
		url : baseURL + "index.php/products",
		dataType : "json",
		data : formToJSON(),
		success : function(data, textStatus, jqXHR) {
			if (data) {
				if (data.id) {
					document.getElementById("addBoatFrm").reset();
					$boatDT.api().ajax.reload();
					$('#addBoatModal').modal('hide');
					bootbox.alert("Boat Added Successfully");
				} else if (data.error) {
					$("div#error").html(data.error);
				}
			} else {
				bootbox.alert("Add Boat Failed!! Please try again");
			}
		},
		error : function(jqXHR, textStatus, errorThrown) {
			bootbox.alert("Add Boat Failed!! Please try again");
		}
	});
}

// Helper function to serialize all the form fields into a JSON string
function formToJSON() {
	var boat = {
		"name" : $('#name').val(),
		"description" : $('#description').val()
	};

	if ($('input#boatId').length) {
		boat["id"] = $('#boatId').val();
		boat['updatedBy'] = logged_in_user_id;
	} else {
		boat["createdBy"] = logged_in_user_id;
	}

	return JSON.stringify(boat);
}