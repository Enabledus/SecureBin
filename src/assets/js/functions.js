function settingsModal(message, key) {
	$('.settings-modal').remove();
	$('.modal-backdrop.show').remove();

	$('body').prepend(`<div class="modal settings-modal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Settings</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="form-group settings-module" post-key="max-views">
						<h6><label><input type="checkbox"> Auto-remove after amount of views</label></h6>
						<div class="align-left reveal-oncheck">
							<input type="number" min="1" max="20" class="form-control" placeholder="Remove paste after a certain amount of views">
						</div>
					</div>

					<div class="form-group settings-module" post-key="master-key">
						<h6><label><input type="checkbox"> Enable master-key for manual deleting</label></h6>
						<div class="reveal-oncheck">
							<input type="text" class="form-control" placeholder="Master Password">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary save-settings">Save</button>
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>`);

	$('.settings-modal').modal();

	$('.settings-modal input[type="checkbox"]').change(function() {
		var checked = $(this).is(':checked');
		if(checked == true) {
			$(this).closest('.form-group').find('.reveal-oncheck').show();
		} else {
			$(this).closest('.form-group').find('.reveal-oncheck').hide();
		}
	});

	$('.save-settings').click(function() {
		var settings = $('.settings-modal .settings-module');
		var postData = {
			"message": message,
			"key": key
		}
		settings.each(function() {
			var postkey = $(this).attr('post-key');
			var checked = $(this).find('input[type="checkbox"]').is(':checked');
			var value = $(this).find('.reveal-oncheck input').val();

			if(!checked) return true; // return true, equivalent to continue in this context (.each)

			postData[postkey] = value;
		});

		$.ajax({
			url: "api.php?action=create",
			method: "POST",
			data: postData,
			success: function(data) {
				if(data.success == false) {
					alert(data.message);
				} else if(data.success == true) {
					var id = data.message;
					var key = data.key;
					document.location = "paste.php?id="+id;
				} else {
					alert("An internal error has occurred");
				}
			},
			error: function() {
				alert("An internal error has occurred");
			}
		});
	});
}