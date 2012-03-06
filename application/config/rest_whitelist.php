<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Global IP Whitelisting
|--------------------------------------------------------------------------
|
| Limit connections to your REST server to whitelisted IP addresses.
|
| Usage:
| 1. Set to true *and* select an auth option for extreme security (client's IP
|	 address must be in whitelist and they must also log in)
| 2. Set to true with auth set to false to allow whitelisted IPs access with no login.
| 3. Set to false here but set 'auth_override_class_method' to 'whitelist' to
|	 restrict certain methods to IPs in your whitelist
|
*/
$config['rest_ip_whitelist_enabled'] = true;

/*
|--------------------------------------------------------------------------
| REST IP Whitelist
|--------------------------------------------------------------------------
|
| Limit connections to your REST server to a comma separated
| list of IP addresses
|
| Example: $config['rest_ip_whitelist'] = '123.456.789.0, 987.654.32.1';
|
| 127.0.0.1 and 0.0.0.0 are allowed by default.
|
*/

$config['rest_ip_whitelist'] = '10.3.16.164, 10.11.48.109, 10.8.30.159';

/* End of file config.php */
/* Location: ./system/application/config/rest.php */
