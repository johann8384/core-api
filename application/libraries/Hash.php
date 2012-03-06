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
		core_base::startf();

		if ($key === NULL)
		{
			$key = self::$key;
		}

		$hash = core_crypto::encrypt($string, NULL, NULL, $key);

		self::log_message('String: '.$string);
		self::log_message('Key: '.self::$key);
		self::log_message('Hash: '.$hash);

		core_base::endf();

		return $hash;
	}

	public static function decode_hash($hash, $key=NULL)
	{
		core_base::startf();

		if ($key === NULL)
		{
			$key = self::$key;
		}

		$string = core_crypto::decrypt($hash, NULL, NULL, $key);

		self::log_message('Hash: '.$hash);
		self::log_message('Key: '.self::$key);
		self::log_message('String: '.$string);

		core_base::endf();
		return $string;
	}

	public static function encode_object($object)
	{
		core_base::startf();

		$json = json_encode($object);
		$hash = self::encode_hash($json);

		core_base::endf();
		return $hash;
	}

	public static function decode_object($hash)
	{
		core_base::startf();

		$json = self::decode_hash($hash);
		$object = json_decode($json);

		core_base::endf();
		return $object;
	}
}
?>
