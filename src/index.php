<?php

	require_once 'includes/assets.php';

	$pastes = $db->query("SELECT * FROM pastes");

?>

<?php require 'includes/header.php'; ?>
		<div class="container-wrap">
			
			<div class="container-block">
				<div class="container-head">
					<div class="container-align-left">
						<div class="container-align-left-element">
							<span id="brand"><?php echo $config['brand']; ?></span>
						</div>
						<div class="container-align-left-element pastes-counter">
							<span>Total pastes: <?php echo count($pastes); ?></span>
						</div>
					</div>
					<div class="container-align-right"></div>
				</div>
				<div class="container-body">
					<textarea class="form-control form-control-dark paste-input" placeholder="Enter a message" autofocus="1" spellcheck="false" autocomplete="off" autocorrect="off" autocapitalize="off" id="messageArea"></textarea>
				</div>
				<div class="container-footer">
					<div class="container-align-left">
						<div class="container-align-left-element">
							<input type="text" class="form-control form-control-dark encryption-key-input" placeholder="Encryption key" id="keyInput">
						</div>
						<!--<div class="container-align-left-element max-views-container">
							<input type="number" class="form-control form-control-dark" min=0 max=20 id="maxViewsInput">
							<span class="description"> <span class="reset-max-views">&times;</span> Max views before removal</span>
						</div>-->
					</div>
					<div class="container-align-right">
						<div class="container-align-right-element">
							<input type="button" class="form-control form-control-dark create-paste-button" value="Save paste" id="saveButton">
						</div>
					</div>
				</div>
			</div>

		</div>

<?php require 'includes/footer.php'; ?>