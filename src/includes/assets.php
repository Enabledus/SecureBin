<?php

	$config = require_once 'config.php';
	require_once 'Encryption.php';

	$metadata = array(
		"description" => $config['meta']['description']
	);

	require_once 'db.php';
	$db = new db;

	require_once 'functions.php';

?>