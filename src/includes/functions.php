<?php

	function rand_str($length = 16) {
		$chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"); $output = "";
		for($i = 0; $i < $length; $i++) {
			$output .= $chars[ rand(0, count($chars)-1) ];
		} return $output;
	}

	function retriveIP($SERVER) {
		if (!empty($SERVER['REMOTE_ADDR'])) { // Normal IP Header
			return $SERVER['REMOTE_ADDR'];
		} else if (!empty($SERVER['HTTP_CF_CONNECTING_IP'])) { // Cloudflare IP Header
			return $SERVER['HTTP_CF_CONNECTING_IP'];
		} else if (!empty($SERVER['X-Real-IP'])) { // Blazingfast IP Header
			return $SERVER['X-Real-IP'];
		}
		throw new Exception('Unknown error when retriving client IP');
	}

	function query($ip, $api) {
		if (filter_var($ip, FILTER_VALIDATE_IP,FILTER_FLAG_IPV6))
			return;
		$lookup = $api . '.' . implode('.', array_reverse(explode ('.', $ip))) . '.dnsbl.httpbl.org';
		$result = explode( '.', gethostbyname($lookup));
		return $result;
	}

	function is_crawler() {

		$agent = $_SERVER['HTTP_USER_AGENT'];

		$crawlersfile = $_SERVER['DOCUMENT_ROOT']."/.crawlers";

		if(!file_exists($crawlersfile)) return false;

		$crawlers = [];

		$crawlershandle = fopen($crawlersfile, "r");
		if($crawlershandle) {
			$c = fread($crawlershandle, filesize($crawlersfile));
			$c = explode("\n", $c);
			foreach($c as $x) {
				$crawlers[] = trim($x);
			}
		}

		if(in_array($agent, $crawlers)) {
			return true;
		} return false;

	}

	function get_themes() {
		require_once 'assets.php'; $db = new db;

		$directory = "/assets/css/themes/";
		$base_directory = $_SERVER['DOCUMENT_ROOT'].$directory;

		$themes = $db->query("SELECT * FROM themes");
		$filtered = [];

		foreach($themes as $theme) {
			if(!file_exists($base_directory.$theme['filename'])) continue;
			if(!ctype_xdigit($theme['hex']) || strlen($theme['hex']) != 6) continue;

			$theme['forecolor'] = colorContrast("#".$theme['hex']);

			$filtered[] = $theme;
		} return $filtered;
	}

	// https://stackoverflow.com/questions/1331591/given-a-background-color-black-or-white-text
	function colorContrast($hex){
		list($red, $green, $blue) = sscanf($hex, "#%02x%02x%02x");
		$luma = ($red + $green + $blue)/3;

		if ($luma < 128) {
			$textcolour = "white";
		} else {
			$textcolour = "black";
		}
		return $textcolour;
	}



?>
