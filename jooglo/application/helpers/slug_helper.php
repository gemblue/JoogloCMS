<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	fungsi untuk generate unic password
*/
if ( ! function_exists('get_slug'))
{

	function get_slug($kata){
	
		$kata = strtolower($kata);
		$kata = strip_tags($kata,""); 
		$kata = preg_replace('/[^A-Za-z0-9\s.\s-]/','',$kata); 
		$kata = str_replace( ' ', '-', $kata);
		$slug = str_replace( '.', '', $kata);
		
		return $slug;

	}

}


if ( ! function_exists('slugify'))
{
	function slugify($text){
		
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}
}


/* End of file slug_helper.php */
/* Location: ./system/helpers/slug_helper.php */