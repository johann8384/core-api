<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * This class allows to generate a form. It requires form_helper and validation library.
 *
 */
class Form_generation {
	var $clears = 0;
	var $_form_attributes = array();
	var $error_msg = array();
	var $_inputs = array();
	var $_labels = array();
	var $_validation = array();
	var $autovalidation = false;
	var $js_validation_function = "validate";
	var $_allowed_form_attributes = array();
	var $_allowed_input_attributes = array();
	var $_allowed_input_text_attributes = array();
	var $_allowed_input_password_attributes = array();
	var $_allowed_input_select_attributes = array();
	var $_allowed_input_linked_select_attributes = array();
	var $_allowed_input_image_attributes = array();
	var $_allowed_input_radio_attributes = array();
	var $_allowed_input_checkbox_attributes = array();
	var $_allowed_input_submit_attributes = array();
	
	/**
	 * Constructor
	 *
	 * @access	public
	 */
	function Form_generation($props = array()) {
		if (count($props) > 0)
		{
			$this->initialize($props);
		}
		log_message('debug', "Form Generation Class Initialized");
		$CI =& get_instance();
		$CI->load->helper('form');
		if (!isset($CI->validation)) $CI->load->library('validation');
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Initialize preferences
	 *
	 * @access	public
	 * @param	array
	 * @return	void
	 */	
	function initialize($config = array()) {
		foreach ($config as $key => $value) {
			$method = 'set_'.$key;
			if (method_exists($this, $method))
			{
				$this->$method($config[$key]);
			}
			else
			{
				$this->$key = $value;
			}
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Restart the class
	 *
	 * @access	private
	 * @param	array
	 * @return	void
	 */	
	function clear($att = array()) {
		$this->_form_attributes = array();
		$this->_inputs = array();
		$this->_labels = array();
		$this->_validation = array();
		$this->js_validation_function = "validate" . $this->clears++;;
	}

	// --------------------------------------------------------------------
	
	/**
	 * Set form valid attributes
	 *
	 * @access	private
	 * @param	array
	 * @return	void
	 */	
	function set_form_att($att = array()) {
		$this->_allowed_form_attributes = $att;
	}

	function set_form($data = array()) {
		foreach ($data as $key => $value) {
			//xHTML compatibility
			$key = strtolower($key);
			
			//is allowed?
			if (!in_array($key,$this->_allowed_form_attributes)) {
				$this->set_error("Unsopported attribute: " . $key);
				continue;
			}
			$this->_form_attributes[$key] = $value;
		}
	}
	
	/**
	 * Set allowed attributes for all inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_input_att($att = array()) {
		$this->_allowed_input_attributes = $att;
	}

	/**
	 * Set allowed attributes for text inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_text_att($att = array()) {
		$this->_allowed_input_text_attributes = $att;
	}

	/**
	 * Set allowed attributes for password inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_password_att($att = array()) {
		$this->_allowed_input_password_attributes = $att;
	}

	/**
	 * Set allowed attributes for dropdown inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_select_att($att = array()) {
		$this->_allowed_input_select_attributes = $att;
	}

	/**
	 * Set allowed attributes for linked dropdown inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_linked_select_att($att = array()) {
		$this->_allowed_input_linked_select_attributes = $att;
	}

	/**
	 * Set allowed attributes for image inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_image_att($att = array()) {
		$this->_allowed_input_image_attributes = $att;
	}

	/**
	 * Set allowed attributes for radio inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_radio_att($att = array()) {
		$this->_allowed_input_radio_attributes = $att;
	}

	/**
	 * Set allowed attributes for checkbox inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_checkbox_att($att = array()) {
		$this->_allowed_input_checkbox_attributes = $att;
	}

	/**
	 * Set allowed attributes for textarea inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_textarea_att($att = array()) {
		$this->_allowed_input_textarea_attributes = $att;
	}

	/**
	 * Set allowed attributes for file inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_file_att($att = array()) {
		$this->_allowed_input_file_attributes = $att;
	}

	/**
	 * Set allowed attributes for file inputs
	 *
	 * @access public
	 * @param array $att
	 * @return void
	 */
	function set_submit_att($att = array()) {
		$this->_allowed_input_submit_attributes = $att;
	}

	// --------------------------------------------------------------------

	/**
	 * Add an input to the class
	 *
	 * @access public
	 * @param array $data
	 * @return void
	 */
	function add_input($data = array()) {
		if (!isset($data['type'])) {
			$this->set_error("No input type specificaded");
			return;
		}

		if (!isset($data['name'])) {
			$this->set_error("No input name specificaded");
			return;
		}

		if ($data['type'] == "file") {
			$this->_form_attributes['enctype'] = "multipart/form-data";
		}

		$method = "add_input_" . $data['type'];
		
		//Is there a specific method for this type?
		if (method_exists($this,$method)) {
			unset($data['type']);
			$this->$method($data);
			return;
		}

		//Looking for the attributes
		$allowed_att_key = "_allowed_input_" . $data['type'] . "_attributes";
		if (isset($this->$allowed_att_key))
			$allowed_att = array_merge($this->_allowed_input_attributes,$this->$allowed_att_key);
		else
			$allowed_att = $this->_allowed_input_attributes;

		//Validation rules
		$validation = "";
		if (isset($data['validation'])) {
			$validation .= $data['validation'];
			unset($data['validation']);
		}

		//Label for validation & printing
		if (isset($data['label'])) {
			$label = $data['label'];
			unset($data['label']);
		}

		//Default Value
		if (isset($_POST[$data['name']]) && $data['type'] != "password") {
			$default_value = $_POST[$data['name']];
		} else {
			$default_value = isset($data['value']) ? $data['value'] : "";
		}
		if (isset($data['value'])) unset($data['value']);
		
		//Value & type
		$att['type'] = $data['type'];
		$att['value'] = $default_value;
		unset($data['type']);

		foreach ($data as $key => $value) {
			//xHTML compatibility
			$key = strtolower($key);
			
			//is allowed?
			if (!in_array($key,$allowed_att)) {
				$this->set_error("Unsopported attribute: " . $key);
				continue;
			}
			$att[$key] = $value;
		}

		if (!isset($att['id']))
			$att['id'] = $att['name'];

		if (isset($att['maxlength']) && strpos($validation,"max_length") === false)
			$validation .= "|max_lengh[" . $att['maxlength'] . "]";

		if (isset($att['parent']) && strpos($validation,"is_child") === false)
			$validation .= "|is_child[" . $att['id'] . "]";

		$this->_inputs[$att['id']] = $att;
		if (isset($label)) {
			$this->_labels[$att['id']] = $label;
		}

		if (isset($validation)) {
			$this->_validation[$att['name']] = $validation;
			if ($this->autovalidation === true) {
				$this->set_validation();
			}
		}
	}
	
	function open() {
		$CI =& get_instance();
		if (!empty($this->js_validation_function)) $CI->validation->set_js_validation_function($this->js_validation_function);
		$js = $CI->validation->get_javascript();
		if (!empty($js)) $this->_form_attributes['onsubmit'] = "return " . $this->js_validation_function ."(this);";
		$action = $this->_form_attributes['action'];
		$parse = $this->_form_attributes;
		unset($parse['action']);
		return form_open($action,$parse) . $js;
	}

	function close() {
		return form_close();
	}

	function get_label($id,$data = array()) {
		return form_label(array_merge(array(
			"for" => $id,
			"label" => isset($this->_labels[$id]) ? $this->_labels[$id] : $id
		),$data));
	}

	function get_input($id) {
		if (!isset($this->_inputs[$id])) {
			$this->set_error("Unknown input: " . $id);
			return;
		}
		
		$method = "get_" . $this->_inputs[$id]['type'];
		if (!method_exists($this,$method)) {
			$this->set_error("Can't write input " . $this->_inputs[$id]['type']);
			return;
		}
		
		return $this->$method($id);
	}
	
	function get_text($id) {
		return form_input($this->_inputs[$id]);
	}
	
	function get_password($id) {
		return form_password($this->_inputs[$id]);
	}

	function get_file($id) {
		return form_upload($this->_inputs[$id]);
	}

	function get_textarea($id) {
		return form_textarea($this->_inputs[$id]);
	}

	function get_select($id) {
		$data = array($this->_inputs[$id]['name'],$this->_inputs[$id]['options'],$this->_inputs[$id]['value']);
		$parse = $this->_inputs[$id];
		unset($parse['name'],$parse['options'],$parse['value'],$parse['type']);
		$data[3] = parse_form_attributes($parse,array());
		return form_dropdown($data[0],$data[1],$data[2],$data[3]);
	}

	function get_linked_select($id) {
		$data = array($this->_inputs[$id]['name'],$this->_inputs[$id]['parent'],$this->_inputs[$id]['options'],isset($this->_inputs[$this->_inputs[$id]['parent']]['value']) ? $this->_inputs[$this->_inputs[$id]['parent']]['value'] : "",isset($this->_inputs[$id]['value']) ? $this->_inputs[$id]['value'] : "");
		$parse = $this->_inputs[$id];
		unset($parse['name'],$parse['parent'],$parse['options'],$parse['parent'],$parse['value']);
		return form_linked_dropdown($data[0],$data[1],$data[2],$data[3],$data[4],parse_form_attributes($parse,array()));
	}

	function get_checkbox($id) {
		$this->_inputs[$id]['checked'] = (isset($this->_inputs[$id]['checked']) && $this->_inputs[$id]['checked']) ? "checked" : false;
		return form_checkbox($this->_inputs[$id]);
	}

	function get_radio($id) {
		$this->_inputs[$id]['checked'] = (isset($this->_inputs[$id]['checked']) && $this->_inputs[$id]['checked']) ? "checked" : false;
		return form_radio($this->_inputs[$id]);
	}

	function get_submit($id) {
		return form_submit($this->_inputs[$id]);
	}

	function set_validation() {
		$CI =& get_instance();

		$CI->validation->set_fields($this->_labels);
		$CI->validation->set_rules($this->_validation);
		$CI->validation->set_message("is_child","Please select a correct %s");

		//avoid duplicated rules settings
		$this->_validation = array();
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Set an error message
	 *
	 * @access	public
	 * @param	string
	 * @return	void
	 */	
	function set_error($msg) {
		$this->error_msg[] = $msg;
		log_message('error', $msg);
	}

	// --------------------------------------------------------------------
	
	/**
	 * Display the error message
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	string
	 */	
	function display_errors($open = '<p>', $close = '</p>')
	{
		$str = '';
		foreach ($this->error_msg as $val)
		{
			$str .= $open.$val.$close;
		}
	
		return $str;
	}
}
?>