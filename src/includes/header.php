<?php require_once 'assets.php'; require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">

<?php if((isset($metadata) && is_array($metadata)) && count($metadata)): ?>
<?php foreach($metadata as $name => $content): ?>
		<meta name="<?php echo $name; ?>" content="<?php echo $content; ?>">
<?php endforeach; ?>
<?php endif; ?>

		<title><?php if(isset($title) && !empty($title)) echo $title; else echo $config['default_title']; ?></title>

		<link href="https://fonts.googleapis.com/css?family=Inconsolata&display=swap" rel="stylesheet">

		<link rel="stylesheet" href="assets/css/bootstrap.css">
		<link rel="stylesheet" href="assets/css/style.css">
		<link rel="stylesheet" href="assets/css/themes/<?php echo $_SESSION['theme']; ?>">

		<script src="https://kit.fontawesome.com/ded29a47a8.js"></script>

		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/js/popper.min.js"></script>
		<script src="assets/js/bootstrap.js"></script>
		<script src="assets/js/functions.js"></script>
		<script src="assets/js/script.js"></script>
	</head>
	<body>

		<div class="themes-snippet">
			<div class="themes-container">
				<?php foreach(get_themes() as $theme): ?>
				<div class="theme" style="background: #<?php echo $theme['hex']; ?>;" data-action="change-theme" theme-id="<?php echo $theme['identifier']; ?>">
					<span class="theme-title" style="color: <?php echo $theme['forecolor']; ?>;"><?php echo $theme['name']; ?></span>
				</div>
				<?php endforeach; ?>
			</div>
			<span class="toggle-themes"><i class="fas fa-caret-left"></i></span>
		</div>