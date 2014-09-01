<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
	* pagination link
	* 
	* @param String $str String to get an excerpt from
	* @param Integer $startPos Position int string to start excerpt from
	* @param Integer $maxLength Maximum length the excerpt may be
	* @return String excerpt
	*/
if ( ! function_exists('pagination_link'))
{
	function pagination_link($uri, $total, $segment = 3, $pagenum = 1) {
		$CI =& get_instance();

		// ambil jumlah per page dari database
		$limit = $CI->mdl_options->get_options('perpage');

		// hitung batas awal pengambilan data
		$offset = $limit * ($pagenum-1);
	
		// setting dasar
		$config['base_url'] = site_url($uri);
		$config['uri_segment'] = $segment;
		$config['total_rows'] = $total;
		$config['per_page'] = $limit;
		$config['num_links'] = 5;

		// set ini supaya linknya pake page number, bukan offset
		$config['use_page_numbers'] = TRUE;

		// layouting
		$config['anchor_class'] = 'btn btn-primary';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['next_link'] = 'Next &gt;';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = '&lt; Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a class="btn btn-primary active">';
		$config['cur_tag_close'] = '</a></li>';

		$CI->pagination->initialize($config); 

		return array(
			'link' => $CI->pagination->create_links(),
			'limit' => $limit,
			'offset' => $offset,
			'total_page' => ceil($total/$limit)
		);
	}
}

/* End of file excerpt_helper.php */
/* Location: ./system/helpers/excerpt_helper.php */