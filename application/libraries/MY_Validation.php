<?php
class MY_Validation extends CI_Validation {
	var $_js_validation_function = "validate";
	var $_js_error_prefix = "";
	var $_js_error_suffix = "\\n";
	var $_js_code = "";
	var $_js_error_handler = "alert";
	
	function get_javascript() {
		$script = "var error = '';\nvar to_focus = null;\n";
		
		// Load the language file containing error messages
		$this->CI->lang->load('validation');

		// Cycle through the rules and test for errors
		foreach ($this->_rules as $field => $rules)
		{
			//Explode out the rules!
			$ex = explode('|', $rules);
			foreach ($ex as $rule) {
				$param = FALSE;
				if (preg_match("/(.*?)\[(.*?)\]/", $rule, $match))
				{
					$rule	= $match[1];
					$param	= $match[2];
				}

				if ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = $this->CI->lang->line($rule)))
					{
						$line = 'Unable to access an error message corresponding to your field name.';
					}						
				}
				else
				{
					$line = $this->_error_messages[$rule];;
				}
				$mfield = ( ! isset($this->_fields[$field])) ? $field : $this->_fields[$field];
				$mparam = ( ! isset($this->_fields[$param])) ? $param : $this->_fields[$param];

				$argument = "";
				switch ($rule) {
					case "isset":
						$argument = "!form." . $field . ".checked";
						break;

					case "required":
						$argument = "form." . $field . ".value == ''";
						break;

					case "matches":
						$argument = "form." . $field . ".value != form." . $param . ".value";
						break;

					case "min_length":
					case "max_length":
					case "exact_length":
						$argument = "form." . $field . ".value.length ".($rule == "min_length" ? "<" : ($rule == "max_length" ? ">" : "!="))." ".$mparam;
						break;
					
					case "alpha":
						$argument= "!/^([-a-z])+$/i.test(form." . $field . ".value)";
						break;

					case "alpha_numeric":
						$argument= "!/^([-a-z0-9])+$/i.test(form." . $field . ".value)";
						break;

					case "alpha_dash":
						$argument= "!/^([-a-z0-9_-])+$/i.test(form." . $field . ".value)";
						break;

					case "numeric":
						$argument= "!/^([0-9])+$/i.test(form." . $field . ".value)";
						break;

					case "valid_email":
						$argument= "!/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i.test(form." . $field . ".value)";
						break;

					case "valid_ip":
						$argument= "!/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/.test(form." . $field . ".value)";
						break;

					default:
						if (strpos($rule,"js_") === 0)
							$argument = substr($rule,3)."(form." . $field . ".value)";
				}
				if (!empty($argument)) {
					$script .= "if (" . $argument . ") {\n"
					."error += '" . $this->_js_error_prefix . addslashes(sprintf($line,$mfield,$mparam)) . $this->_js_error_suffix . "';\n"
					."if (to_focus == null) to_focus = form." . $field . ";\n"
					."}\n";
				}
			}
		}
		$script .= "if (error.length > 0) {\n";
		if (is_array($this->_js_error_handler))
			foreach ($this->_js_error_handler as $h)
				$script .= $h . "(error);\n";
		else 
			$script .= $this->_js_error_handler . "(error);\n";
		$script .= "to_focus.focus();\n"
		."return false;\n"
		."}\n";
		return empty($script) ? "" : "<script type=\"text/javascript\">\n//<![CDATA[\n//<!--\nfunction " . $this->_js_validation_function . "(form)\n{\n" . $script . "\n}\n" . $this->_js_code . "\n//-->\n//]]>\n</script>";
	}
	
	function set_js_error_delimiters($prefix = '', $suffix = '\\n') {
		$this->_js_error_prefix = $prefix;
		$this->_js_error_suffix = $suffix;
	}
	
	function set_js_function($function,$args,$code = "") {
		$this->_js_code .= "function " . $function . " ( " . (empty($code) ? "" : $args) . " ) {\n"
		. (empty($code) ? $args : $code) ."\n"
		."}\n";
	}

	function set_js_validation_function($function) {
		$this->_js_validation_function = $function;
	}

	function set_js_error_handler($action = "alert") {
		$this->_js_error_handler = $action;
	}
	
	function is_child($child,$dropdown) {
		$CI =& get_instance();
		return in_array($child,array_keys($CI->form_generation->_inputs[$dropdown]['options'][$CI->form_generation->_inputs[$CI->form_generation->_inputs[$dropdown]['parent']]['value']]));
	}
}
?>