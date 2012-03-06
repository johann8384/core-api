<?php
class Keys
{
	public static function lookup_key($app_key, $uri_params)
	{
		core_base::startf();
		$ret = false;

		if ($app_key == 'ebe64f897e9483' &&  Net_IPv4::ipInNetwork($_SERVER['REMOTE_ADDR'], '10.0.0.0/8'))
		{
			self::log_message('this key has full access when used on the LAN');
			$ret = true;
		} else {
			self::log_message("Validating URI Params: ".json_encode($uri_params));

			$access_list = self::key_database($app_key);

			self::log_message("Access List is: ".json_encode($access_list));


			if ($access_list === false || !is_array($access_list))
			{
				$ret = false;
			} else {
				foreach ($uri_params as $level=>$value)
				{
					if ($ret === false && !is_null($access_list) && $value != "")
					{
						$ret = self::validate_level($access_list, $value);
						if ($ret === 'wildcard') {
							$ret = true;
						} else {
							if (array_key_exists($value, $access_list))
							{
								$access_list = $access_list[$value];
							} else {
								$access_list = null;
							}
							$ret = false;
						}
					}
				}
			}
		}
		core_base::endf($ret, $ret !== false);
		return $ret;
	}

	private static function validate_level($access_list, $uri_param)
	{
		core_base::startf();
		self::log_message("validating $uri_param in ".json_encode($access_list), PEAR_LOG_DEBUG);
		$ret = false;
		if (array_key_exists($uri_param, $access_list) || array_key_exists('*', $access_list))
		{
			self::log_message("array key exists $uri_param or *", PEAR_LOG_DEBUG);
			if (array_key_exists('*', $access_list) || $access_list[$uri_param] === '*')
			{
				self::log_message("access_list[$uri_param] is * or array_key_exists *", PEAR_LOG_DEBUG);
				$ret = 'wildcard';
			} else {
				self::log_message("access_list[$uri_param] is NOT * and !array_key_exists *", PEAR_LOG_DEBUG);
				$ret = 'found';
			}
		} else {
			self::log_message("array key DOES NOT exist $uri_param or *", PEAR_LOG_DEBUG);
		}
		core_base::endf($ret, $ret !== false);
		return $ret;
	}

	private static function key_database($app_key)
	{
		core_base::startf();
		self::log_message('app key is '.$app_key);
		$ret = false;
		$keylist = Array();
		$keylist['fc22b6a9cfe3ab4']['*']['*'] = '*'; // Full Access to /*
		$keylist['34b32b6a2263cf3']['debug']['*'] = '*'; // Full Access to /debug/*

		if (array_key_exists($app_key, $keylist))
		{
			$ret = $keylist[$app_key];
		} else {
			$ret = false;
		}
		core_base::endf($ret, $ret !== false);
		return $ret;
	}
}
?>