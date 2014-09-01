<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	* Get excerpt from string
	* 
	* @param String $str String to get an excerpt from
	* @param Integer $startPos Position int string to start excerpt from
	* @param Integer $maxLength Maximum length the excerpt may be
	* @return String excerpt
	*/
if ( ! function_exists('get_excerpt'))
{
	function get_excerpt($str, $startPos=0, $maxLength=100) {
		if(strlen($str) > $maxLength) {
			$excerpt   = substr($str, $startPos, $maxLength-3);
			$lastSpace = strrpos($excerpt, ' ');
			$excerpt   = substr($excerpt, 0, $lastSpace);
			$excerpt  .= '...';
		} else {
			$excerpt = $str;
		}
	
	return $excerpt;
	}
	
	
	function getNamaPotong($str, $startPos=0, $maxLength=100) {
		if(strlen($str) > $maxLength) {
			$excerpt   = substr($str, $startPos, $maxLength-3);
			$lastSpace = strrpos($excerpt, ' ');
			$excerpt   = substr($excerpt, 0, $lastSpace);
			$excerpt   = rtrim($excerpt);
		} else {
			$excerpt = $str;
		}
	
	return $excerpt;
	}
}

/* End of file excerpt_helper.php */
/* Location: ./system/helpers/excerpt_helper.php */