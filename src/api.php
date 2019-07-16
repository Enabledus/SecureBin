<?php

	header("Content-Type: application/json");

	require 'includes/assets.php';

	if($_SERVER['REQUEST_METHOD'] == "POST") {

		if(isset($_GET['action'])) {

			if($_GET['action'] == "initial-validation") {

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

				die(json_encode(array("success" => true)));

			} else if($_GET['action'] == "create") {

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
				/**
				* @param int $results[2]	Threat score
				* @param int $results[1]	Days since last malicious activity
				*/
				$ip = retriveIP($_SERVER);
				$honeypot = $config['security']['spam']['projecthoneypot'];

				$result = query($ip, $honeypot['api']['api_key']);

				if ($result[0] == 127) {
					if ($result[2] >= $honeypot['settings']['threat_score'] && $result[1] <= $honeypot['settings']['last_activity']) {
						die(json_encode(array(
							"success" => false,
							"message" => "Your IP Address has been automatically blocked from creating any new pastes"
						)));
					}
				}
				}

				if(isset($_POST['max-views'])) {
					if(empty($_POST['max-views']))
						die(json_encode(array("success" => false, "message" => "Max views can't be empty")));

					if(!is_numeric($_POST['max-views']))
						die(json_encode(array("success" => false, "message" => "Max views has to be numeric")));

					if((int)$_POST['max-views'] < 1)
						die(json_encode(array("success" => false, "message" => "Max views can't be less than 1")));

					if((int)$_POST['max-views'] > $config['encryption']['max_views']['max_value'])
						die(json_encode(array("success" => false, "message" => "Max views can't be more than ".$config['encryption']['max_views']['max_value'])));

					$maxViews = (int)$_POST['max-views'];
				}

				/*if(isset($_POST['burn-if-incorrect']) && !empty($_POST['burn-if-incorrect'])) {
					if(!is_numeric($_POST['burn-if-incorrect']))
						die(json_encode(array("success" => false, "message" => "Max retries before burn has to be numeric")));

					if((int)$_POST['burn-if-incorrect'] < 1)
						die(json_encode(array("success" => false, "message" => "Max retries before burn can't be less than 1")));

					if((int)$_POST['burn-if-incorrect'] > 20)
						die(json_encode(array("success" => false, "message" => "Max retries before burn can't be more than 20")));
				}*/

				$masterkey = "";
				if(isset($_POST['master-key'])) {
					if(empty(trim($_POST['master-key'])))
						die(json_encode(array("success" => false, "message" => "Master password can't be empty")));

					if(strlen(trim($_POST['master-key'])) < 6)
						die(json_encode(array("success" => false, "message" => "Master password can't be shorter than 6 characters")));

					if(strlen(trim($_POST['master-key'])) > 128)
						die(json_encode(array("success" => false, "message" => "Master password can't be longer than 128 characters")));

					$masterkey = password_hash($_POST['master-key'], PASSWORD_DEFAULT);
				}


				$Encryption = new Encryption($_POST['key']);
				$encrypted = $Encryption->encrypt($_POST['message']);

				$id = rand_str(16);
				while($db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $id)))
					$id = rand_str(16);

				$db->query("INSERT INTO pastes VALUES (:id, :paste, :password, :masterkey, :views, :max_views)", array(
					":id" => $id,
					":paste" => $encrypted[0],
					":password" => password_hash($encrypted[1], PASSWORD_DEFAULT),
					":masterkey" => $masterkey,
					":views" => 0,
					":max_views" => $maxViews
				));

				die(json_encode(array("success" => true, "message" => $id, "key" => $_POST['key'])));


			} else if($_GET['action'] == "delete") {

				if(!isset($_POST['paste-id']))
					die(json_encode(array("success" => false, "message" => "Missing POST: paste-id")));

				if(!$db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $_POST['paste-id'])))
					die(json_encode(array("success" => false, "message" => "That paste doesn't exist")));

				if(!isset($_POST['master-key']))
					die(json_encode(array("success" => false, "message" => "Missing POST: master-key")));

				$paste = $db->query("SELECT * FROM pastes WHERE id=:id", array(":id" => $_POST['paste-id']))[0];
				if(!password_verify($_POST['master-key'], $paste['masterkey']))
					die(json_encode(array("success" => false, "message" => "Wrong master password")));

				$db->query("DELETE FROM pastes WHERE id=:id", array(":id" => $_POST['paste-id']));

				die(json_encode(array("success" => true)));

			}

		}

	}

	die(json_encode(array("success" => false, "message" => "Request Error")));

?>