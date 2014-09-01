<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
Customized String Manipulation
*/

if ( ! function_exists('my_string'))
{
	function make_lable($string) 
	{
		$string = str_replace('-',' ', $string);
		$string = ucfirst(str_replace('_',' ', $string));
		return $string;
	}
}