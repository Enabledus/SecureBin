<?php

	return array(
		"brand" => "SecureBin",
		"default_title" => "SecureBin - Secure Paste",
		"theme" => "extra_dark.css",
		"database" => array(
			"database" => "",
			"host" => "",
			"username" => "",
			"password" => ""
		),


		"security" => array(
			"spam" => array(
				"projecthoneypot" => array(
					"api" => [
						"enabled" => false,
						"api_key" => "", // Projecthoneypot API Key https://www.projecthoneypot.org/httpbl_configure.php
					],
					"settings" => [
						"threat_score" => 14, // Score required to prohibit the user from creating any pastes [1-13 Low: 14-24 Medium: 49+ High]
						"last_activity" => 45, // Maximum days since last malicious activity to consider it as malicious
					]
				)
			),
			"crawler" => array(
				"count" => false // False = dont increase view count if the request comes from a known crawler
			),
			"keys" => array(
				"blacklisted_keys" => ["abc123", "123456", "asdfgh", "asdasd", "password", "321cba", "654321"] // Blacklisted encryption keys (Incasesensitive)
			)
		),

		"encryption" => array(
			"key" => array(
				"min_length" => 6,
				"max_length" => 128
			),
			"message" => array(
				"min_length" => 1,
				"max_length" => 204800
			),
			"max_views" => array(
				"max_value" => 20 // The most amount of times your paste can be viewed before self destructed, unless it's infinite
			)
		),

		"meta" => array(
			"description" => "SecureBin is an encrypted pastebin where the server has got zero knowledge of the paste data."
		)
	);

?>
