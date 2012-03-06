<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'libraries/Rest_Controller.php';

/**
 * The Rest Controller class takes care of the multi-format input/output stuff.
 * Methods are "function_method" for example, calling the "machine" function from the URL
 * with a method of PUT would match a function "machine_put" here.
 *
 * The index function is special, if no function is specified, this function is used.
 *
 * @author jcreasy
 *
 */
class Puppet extends REST_Controller {
	/**
	 * default function for GET method if no function is specified
	 * URL: /opsapi/puppet/
	 */
	public function index_get()
	{
		$hostname = $this->get('hostname');
		if (empty($hostname))
		{
			// if hostname was a required value the following line would be uncommented. $this->response behaves like "return";
			// 412 is 'pre-condition failed'
			//$this->response(Array('error'=>'Missing Parameter: hostname'), 412);
		}

		// Load a Model
		$this->load->model('Model_Puppet');

		// Use the Model
		$hosts = $this->Model_Puppet->get_hostnames($hostname);

		// Check to see if it returned some data
		if ($hosts === false)
		{
			$this->response(Array('error'=>'Could not retrieve puppet hostnames'), 500);
		}

		// If the format is HTML, use views to render the response
		// The Rest_Controller class will take an array argument and create a table if format is html
		if ($this->get('format') == 'html')
		{
			$this->load->view('header', Array('title'=>'Puppet Hosts'));
			$this->load->view('hosts', Array('hosts'=>$hosts));
			$this->load->view('footer');
			return;
		} else {
			// Let the Rest_Controller class take care of the response format
			$this->response($hosts, 200);
		}
		$this->response(Array('error'=>'Your request did not complete properly.'), 500);
	}
	/**
	 * default function to get a host record from Puppet
	 * URL: /opsapi/puppet/host?hostname=${hostname} or /opsapi/puppet/host/hostname/${hostname}
	 */
	public function host_get()
	{
		$hostname = $this->get('hostname');
		if (empty($hostname))
		{
			// if hostname was a required value the following line would be uncommented. $this->response behaves like "return";
			// 412 is 'pre-condition failed'
			//$this->response(Array('error'=>'Missing Parameter: hostname'), 412);
		}

		// Load a Model
		$this->load->model('Model_Puppet');

		// Use the Model
		$hosts = $this->Model_Puppet->get_hostnames($hostname);

		// Check to see if it returned some data
		if ($hosts === false)
		{
			$this->response(Array('error'=>'Could not retrieve puppet hostnames'), 500);
		}

		// If the format is HTML, use views to render the response
		// The Rest_Controller class will take an array argument and create a table if format is html
		if ($this->get('format') == 'html')
		{
			$this->load->view('header', Array('title'=>'Puppet Hosts'));
			$this->load->view('hosts', Array('hosts'=>$hosts));
			$this->load->view('footer');
			return;
		} else {
			// Let the Rest_Controller class take care of the response format
			$this->response($hosts, 200);
		}
		$this->response(Array('error'=>'Your request did not complete properly.'), 500);
	}
}
