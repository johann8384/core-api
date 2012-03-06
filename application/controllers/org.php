<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Organization Controller
 *
 * @author Jonathan Creasy <jc@givinginc.com>
 * @version 1.0
 * @package givinginc
 * @subpackage api
 */

/**
 *
 * Org Controller Class
 * @author Jonathan Creasy <jc@givinginc.com>
 * /org
 *
 */
class Org extends Base_Controller {
	/**
	 * Handles GET requests for /org
	 */
	public function index_get()
	{
		extract($this->check_required_params(Array('tid'), Array('X-API-KEY')));
		
	}
}
