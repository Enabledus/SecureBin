$(document).ready(function() {

	// Init the tooltip for all tooltips
	$('[data-toggle="tooltip"]').tooltip();

	var $messageArea = $("#messageArea");
	var $keyInput = $("#keyInput");
	var $saveButton = $("#saveButton");

	$saveButton.click(function(e) {

		$.ajax({
			url: "api.php?action=initial-validation",
			method: "POST",
			data: {
				"message": $messageArea.val(),
				"key": $keyInput.val()
			},
			success: function(data) {
				if(data.success == false) {
					alert(data.message);
				} else if(data.success == true) {
					settingsModal($messageArea.val(), $keyInput.val());
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

	$('.themes-snippet .toggle-themes').click(function() {
		$('.themes-snippet .themes-container').toggle();

		if($('.themes-snippet .themes-container').css('display') == "block") {
			$('.themes-snippet .toggle-themes i').removeClass('fa-caret-left');
			$('.themes-snippet .toggle-themes i').addClass('fa-caret-right');
		} else {
			$('.themes-snippet .toggle-themes i').addClass('fa-caret-left');
			$('.themes-snippet .toggle-themes i').removeClass('fa-caret-right');
		}
	});

	$('[data-action="change-theme"]').click(function() {
		if(!$(this).attr('theme-id')) return;
		document.location = "?theme="+$(this).attr('theme-id');
	});

});