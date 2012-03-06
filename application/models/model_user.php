<?php
class model_user extends CI_Model {

	public $username   = '';
	public $email = '';
	public $type    = '';
	public $password = '';

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function is_valid_username($username)
	{
		//TODO: validate username
		return true;
	}

	public function is_valid_password($password)
	{
		//TODO: validate password
		return true;
	}

	public function generate_password ($length = 9, $strength = 0)
	{
		return Hash::generate_password($length, $strength);
	}

	public function check_credentials($username, $password)
	{

		$user = $this->get_user_from_db($username);

		if (empty($user))
		{
			return false;
		}

		if ($user['password'] = $password)
		{
			return true;
		}

		return false;
	}

	public function get_user_from_db($username)
	{
		$users = array('jcreasy', 'ldweisser', 'test');
		$users['jcreasy'] = Array('password'=>'pw2001', 'email'=>'jonathan@ghostlab.net');
		if (array_key_exists($username, $users))
		{
			return $users[$username];
		} else {
			return Array();
		}
	}
}
