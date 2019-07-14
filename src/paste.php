<?php

	require_once 'includes/assets.php';

	if(!isset($_GET['id']) || !$db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $_GET['id']))) {
		header("Location: /"); exit;
	}

	$paste = $db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $_GET['id']))[0];
	$displayPaste = false;

	if($paste['max_views'] != 0 && $paste['views'] >= $paste['max_views']) {
		$db->query("DELETE FROM pastes WHERE id=:id", array(":id" => $paste['id']));
		header("Location: /");
		exit;
	}

	if(isset($_GET['key'])) {
		if(!password_verify($_GET['key'], $paste['password'])) {
			$error = "Invalid key";
		} else {
			$Encryption = new Encryption($_GET['key']);
			$pasteContent = htmlspecialchars($Encryption->decrypt($paste['paste']));
			if(!is_crawler($_SERVER['HTTP_USER_AGENT']) || (is_crawler($_SERVER['HTTP_USER_AGENT']) && $config['security']['crawler']['count'] == true))
				$db->query("UPDATE pastes SET views = views+1 WHERE id=:id", array(":id" => $paste['id']));

			$displayPaste = true;
		}
	}

	if(!isset($pasteContent) || empty($pasteContent)) {
		$metadata['description'] = "Enter the decryption key below to access the paste";
	} else {
		$metadata['description'] = substr(htmlspecialchars($pasteContent), 0, 50);
	}

?>

<?php require 'includes/header.php'; ?>
		<div class="container-wrap">
			
			<div class="container-block">
				<div class="container-head">
					<div class="container-align-left">
						<div class="container-align-left-element">
							<input type="button" value="Go back" class="btn btn-default width-m" onclick="document.location = '/';">
						</div>
					</div>
					<div class="container-align-right">
						<div class="container-align-right-element">
							<?php if($displayPaste): ?><span class="h5"><?php echo $paste['views']+1; ?> views</span><?php endif; ?>
						</div>
					</div>
				</div>
				<div class="container-body">
					<?php if($displayPaste): ?>
					<textarea class="form-control paste-input" readonly="1" spellcheck="false"
					autocomplete="off" autocorrect="off" autocapitalize="off"><?php echo $pasteContent; ?></textarea>
					<?php endif; ?>

					<?php if(!$displayPaste): ?>
					<form action="" method="GET" class="unlock-paste">
						<p>Enter the decryption key below to gain access to the paste data</p>
						<div class="form-group field-container">
							<div class="unlock-fields">
								<input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
								<input type="text" placeholder="Enter decryption key" class="form-control" name="key" value="<?php if(isset($_GET['key'])) echo htmlspecialchars($_GET['key']); ?>" autofocus="1">
								<input type="submit" value="Unlock" class="btn btn-default">
							</div>
						</div>
						<?php if(isset($error) && !empty(trim($error))): ?>
						<div class="form-group">
							<div class="alert alert-danger decrypt-error">
								<?php echo $error; ?>
							</div>
						</div>
						<?php endif; ?>
					</form>
					<?php endif; ?>

				</div>
				<div class="container-footer"></div>
			</div>

		</div>