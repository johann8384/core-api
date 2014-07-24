<?php
class Hash
{
	public static $key = 'ebe64f897e9483';

	public static function create_user_hash($username, $app_key)
	{
		$hash = self::encode_hash($username."|".$app_key);
		return $hash;
	}

	public static function generate_password ($length = 9, $strength = 8) {
		$vowels = 'aeuy';
		$consonants = 'bdghjmnpqrstvz';
		if ($strength & 1) {
			$consonants .= 'BDGHJLMNPQRSTVWXZ';
		}
		if ($strength & 2) {
			$vowels .= "AEUY";
		}
		if ($strength & 4) {
			$consonants .= '23456789';
		}
		if ($strength & 8) {
			$consonants .= '@#$%';
		}

		$password = '';
		$alt = time() % 2;
		for ($i = 0; $i < $length; $i++) {
			if ($alt == 1) {
				$password .= $consonants[(rand() % strlen($consonants))];
				$alt = 0;
			} else {
				$password .= $vowels[(rand() % strlen($vowels))];
				$alt = 1;
			}
		}
		return $password;
	}

	public static function encode_hash($string, $key=NULL)
	{
		if ($key === NULL)
		{
			$key = self::$key;
		}

		$hash = Crypto::encrypt($string, NULL, NULL, $key);

		self::log_message('String: '.$string);
		self::log_message('Key: '.self::$key);
		self::log_message('Hash: '.$hash);
		self::log_message('Hash Length: ' . strlen($hash));
		return $hash;
	}

	public static function decode_hash($hash, $key=NULL)
	{
		if ($key === NULL)
		{
			$key = self::$key;
		}

		$string = Crypto::decrypt($hash, NULL, NULL, $key);

		self::log_message('Hash: '.$hash);
		self::log_message('Key: '.self::$key);
		self::log_message('String: '.$string);
		return $string;
	}

	public static function encode_object($object)
	{
		$json = json_encode($object);
		$hash = self::encode_hash($json);

		return $hash;
	}

	public static function decode_object($hash)
	{
		$json = self::decode_hash($hash);
		$object = json_decode($json);
		return $object;
	}

	private static function log_message($string)
	{
		echo time() . ': ' .  $string . "\n";
	}
}
?>
