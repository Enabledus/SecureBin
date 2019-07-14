<?php

	return array(
		"brand" => "SecureBin",
		"default_title" => "SecureBin - Secure Paste",
		"theme" => "default.css",

		"database" => array(
			"database" => "",
			"host" => "127.0.0.1",
			"username" => "",
			"password" => ""
		),


		"security" => array(
			"spam" => array(
				"projecthoneypot" => array(
					"api" => array(
						"enabled" => false,
						"api_key" => "", // Projecthoneypot API Key https://www.projecthoneypot.org/httpbl_configure.php
					),
					"settings" => array(
					"threat_score" => 14, // Score required to prohibit the user from creating any pastes [1-3 Low: 4-8 Medium: 9+ High]
					)
				)
			),
			"crawler" => array(
				"count" => false // False = dont increase views if the request comes from a known crawler
			),
			"keys" => array(
				"blacklisted_keys" => ["abc123", "123456", "asdfgh", "asdasd", "password", "321cba"] // Blacklisted encryption keys (Incasesensitive)
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
