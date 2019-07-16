<?php

	session_start();

	$config = require_once 'config.php';
	require_once 'Encryption.php';

	$metadata = array(
		"description" => $config['meta']['description']
	);

	require_once 'db.php';
	$db = new db;

	require_once 'functions.php';

	if(isset($_GET['theme']) && $db->query("SELECT * FROM themes WHERE identifier=:identifier", array(":identifier" => $_GET['theme']))) {
		$_theme = $db->query("SELECT * FROM themes WHERE identifier=:identifier", array(":identifier" => $_GET['theme']))[0];
		$_SESSION['theme'] = $_theme['filename'];
	}

	if(!isset($_SESSION['theme'])) {
		$_SESSION['theme'] = $config['theme'];
	}

?>