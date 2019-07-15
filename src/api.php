<?php

	header("Content-Type: application/json");

	require 'includes/assets.php';

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		if(isset($_GET['action'])) {

			if($_GET['action'] == "create") {

				if(!isset($_POST['message']))
					die(json_encode(array("success" => false, "message" => "Missing POST: message")));

				if(!isset($_POST['key']))
					die(json_encode(array("success" => false, "message" => "Missing POST: key")));

				if(strlen($_POST['message']) < $config['encryption']['message']['min_length']) {
					die(json_encode(array(
						"success" => false,
						"message" => "Message has to be at least ".$config['encryption']['message']['min_length']." characters long"
					)));
				}

				if(strlen($_POST['message']) > $config['encryption']['message']['max_length']) {
					die(json_encode(array(
						"success" => false,
						"message" => "Message can't be longer than ".$config['encryption']['message']['max_length']." characters long"
					)));
				}

				if (in_array(strtolower($_POST['key']), array_map('strtolower', $config['security']['keys']['blacklisted_keys']))) {
					die(json_encode(array(
						"success" => false,
						"message" => "Encryption key is too weak"
					)));
				}

				if(strlen($_POST['key']) < $config['encryption']['key']['min_length']) {
					die(json_encode(array(
						"success" => false,
						"message" => "Encryption key has to be at least ".$config['encryption']['key']['min_length']." characters long"
					)));
				}

				if(strlen($_POST['key']) > $config['encryption']['key']['max_length']) {
					die(json_encode(array(
						"success" => false,
						"message" => "Encryption key can't be longer than ".$config['encryption']['key']['max_length']." characters long"
					)));
				}
				
				if ($config['security']['spam']['projecthoneypot']['api']['enabled']) {
				$ip = "";
				if (!empty($_SERVER['REMOTE_ADDR'])) { // Normal IP Header
					$ip = $_SERVER['REMOTE_ADDR'];
				} else if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) { // Cloudflare IP Header
					$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
				} else if (!empty($_SERVER['X-Real-IP'])) { // Blazingfast IP Header
					$ip = $_SERVER['X-Real-IP'];
				}
				$lookup = $config['security']['spam']['projecthoneypot']['api']['api_key'] . '.' . implode('.', array_reverse(explode ('.', $ip))) . '.dnsbl.httpbl.org';
				$result = explode( '.', gethostbyname($lookup));

				if ($result[0] == 127) {
					if ($result[2] >= $config['security']['spam']['projecthoneypot']['settings']['threat_score']) {
						die(json_encode(array(
							"success" => false,
							"message" => "Your IP Address has been blocked from creating any pastes."
						)));
					}
				}
				}
				
				if(isset($_POST['maxViews']) && !empty($_POST['maxViews'])) {
					if(!is_numeric($_POST['maxViews']))
						die(json_encode(array("success" => false, "message" => "Max views has to be numeric")));

					if((int)$_POST['maxViews'] < 0)
						die(json_encode(array("success" => false, "message" => "Max views has to be greater than or equal to 0, or blank")));

					if((int)$_POST['maxViews'] > $config['encryption']['max_views']['max_value'])
						die(json_encode(array("success" => false, "message" => "Max views can't be more than ".$config['encryption']['max_views']['max_value'])));

					$maxViews = (int)$_POST['maxViews'];
				}


				$Encryption = new Encryption($_POST['key']);
				$encrypted = $Encryption->encrypt($_POST['message']);

				$id = rand_str(16);
				while($db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $id)))
					$id = rand_str(16);

				$db->query("INSERT INTO pastes VALUES (:id, :paste, :password, :views, :max_views)", array(
					":id" => $id,
					":paste" => $encrypted[0],
					":password" => password_hash($encrypted[1], PASSWORD_DEFAULT),
					":views" => 0,
					":max_views" => $maxViews
				));

				die(json_encode(array("success" => true, "message" => $id, "key" => $_POST['key'])));


			}

		}

	}

	die(json_encode(array("success" => false, "message" => "Request Error")));

?>
