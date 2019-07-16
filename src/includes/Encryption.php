<?php

	define('AES_256_CBC', 'aes-256-cbc');

	class Encryption {

		var $key;
		var $iv;

		function __construct($key) {
			$this->key = $key;
			$this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
		}

		public function encrypt($data) {
			$encrypted = openssl_encrypt($data, AES_256_CBC, $this->key, 0, $this->iv);
			$encrypted = $encrypted.":".base64_encode($this->iv);
			return [$encrypted, $this->key];
		}

		public function decrypt($data) {
			$data = explode(':', $data);
			$decrypted = openssl_decrypt($data[0], AES_256_CBC, $this->key, 0, base64_decode($data[1]));
			return $decrypted;
		}

	}

?>