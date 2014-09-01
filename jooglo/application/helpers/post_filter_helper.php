<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
filter post from xss and inject
*/

if ( ! function_exists('post_filter'))
{
	
	
	function post_filter($data) {
		$data = trim(htmlentities(strip_tags($data)));
	 
		if (get_magic_quotes_gpc())
			$data = stripslashes($data);
	 
		$data = mysql_real_escape_string($data);
	 
		return $data;
	}
	
	/*
	function post_img_video_link($data) {
		
		$data = preg_replace_callback('#(?:https?://\S+)|(?:www.\S+)#', function($arr)
				{
					if(strpos($arr[0], 'http://') !== 0)
					{
						$arr[0] = 'http://' . $arr[0];
					}
					$url = parse_url($arr[0]);

					# images
					
					if(preg_match('#\.(png|jpg|gif)$#', $url['path']))
					{
						return '<div><img src="'. $arr[0] . '" /></div>';
					}
					
						
					# youtube
					if (in_array($url['host'], array('www.youtube.com', 'youtube.com')) && $url['path'] == '/watch' && isset($url['query']))
					{
						parse_str($url['query'], $query);
						return sprintf('<div><iframe class="embedded-video" src="http://www.youtube.com/embed/%s" allowfullscreen></iframe></div>', $query['v']);
					}
						
					# links
					return sprintf('<a href="%1$s">%1$s</a>', $arr[0]);
				}, $data );
				
		return $data;
	}
	
	*/
	
	
	
	

}

