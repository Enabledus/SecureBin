<?php

	function rand_str($length = 16) {
		$chars = str_split("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"); $output = "";
		for($i = 0; $i < $length; $i++) {
			$output .= $chars[ rand(0, count($chars)-1) ];
		} return $output;
	}

	function is_crawler($agent) {

		$crawlersfile = $_SERVER['DOCUMENT_ROOT']."/.crawlers";

		if(!file_exists($crawlersfile)) return false;

		$crawlers = [];

		$crawlershandle = fopen($crawlersfile, "r");
		if($crawlershandle) {
			$c = fread($crawlershandle, filesize($crawlersfile));
			$c = explode("\n", $c);
			foreach($c as $x) {
				$crawlers[] = $x;
			}
		}

		if(in_array($agent, $crawlers)) {
			return true;
		} return false;

	}

?>
