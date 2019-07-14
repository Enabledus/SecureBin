$(document).ready(function() {

	// Init the tooltip for all tooltips
	$('[data-toggle="tooltip"]').tooltip();

	var $messageArea = $("#messageArea");
	var $keyInput = $("#keyInput");
	var $saveButton = $("#saveButton");

	$saveButton.click(function(e) {
		var $maxViewsInput = $("#maxViewsInput");
		$.ajax({
			url: "api.php?action=create",
			method: "POST",
			data: {
				"message": $messageArea.val(),
				"key": $keyInput.val(),
				"maxViews": $maxViewsInput.val()
			},
			success: function(data) {
				if(data.success == false) {
					alert(data.message);
				} else if(data.success == true) {
					var id = data.message;
					var key = data.key;
					document.location = "paste.php?id="+id;
				} else {
					alert("An internal error occurred");
				}
			},
			error: function() {
				alert("An internal error occurred");
			}
		})
	});

	$(".reset-max-views").click(function() {
		$("#maxViewsInput").val('');
	});

});