<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.2.4 or newer
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world wide web, please send an email to
 * licensing@ellislab.com so we can send you a copy immediately.
 *
 * @package		CodeIgniter
 * @author		EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @license		http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Exceptions Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Exceptions
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/exceptions.html
 */
class MY_Exceptions extends CI_Exceptions {

	public $theme;

	function __construct()
	{
		parent::__construct();

		// get the current theme
		require_once BASEPATH.'database/DB'.EXT;
		$query = DB()
				->select('value')
				->where('name', 'template')
				->get('jooglo_options')
				->row();
		$this->theme = $query->value;
	}

	// --------------------------------------------------------------------

	/**
	 * General Error Page
	 *
	 * Takes an error message as input (either as a string or an array)
	 * and displays it using the specified template.
	 *
	 * @param	string		$heading	Page heading
	 * @param	string|string[]	$message	Error message
	 * @param	string		$template	Template name
	 * @param 	int		$status_code	(default: 500)
	 *
	 * @return	string	Error page output
	 */
	public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		set_status_header($status_code);

		$message = '<p>'.implode('</p><p>', is_array($message) ? $message : array($message)).'</p>';

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();

		$templates_path = file_exists(DEVPATH.'themes/'.$this->theme.'/views/errors/'.$template.'.php')
			? DEVPATH.'themes/'.$this->theme.'/views/errors/'.$template.'.php'
			: VIEWPATH.'errors'.DIRECTORY_SEPARATOR.$template.'.php';

		include($templates_path);
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;	
	}

	// --------------------------------------------------------------------

	/**
	 * Native PHP error handler
	 *
	 * @param	string	the error severity
	 * @param	string	the error string
	 * @param	string	the error filepath
	 * @param	string	the error line number
	 * @return	string
	 */
	public function show_php_error($severity, $message, $filepath, $line)
	{
		$severity = isset($this->levels[$severity]) ? $this->levels[$severity] : $severity;
		$filepath = str_replace('\\', '/', $filepath);

		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}

		$templates_path = file_exists(DEVPATH.'themes/'.$this->theme.'/views/errors/error_php.php')
			? DEVPATH.'themes/'.$this->theme.'/views/errors/error_php.php'
			: VIEWPATH.'errors'.DIRECTORY_SEPARATOR.'error_php.php';
			
		ob_start();
		include($templates_path);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}

}

/* End of file Exceptions.php */
/* Location: ./system/core/Exceptions.php */