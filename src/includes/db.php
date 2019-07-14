<?php

	class db {

		private function connect() {
			$config = require 'config.php';
			$pdo = new PDO("mysql:host=".$config['database']['host'].";dbname=".$config['database']['database'],
				$config['database']['username'],
				$config['database']['password']);
			return $pdo;
		}

		public function query($query, $params = array()) {
			$stat = self::connect()->prepare($query);
			$stat->execute($params);

			if(explode(' ', $query)[0] == 'SELECT') {
				$data = $stat->fetchAll();
				return $data;
			}
		}

	}

?>