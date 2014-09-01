<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
The controller that handle page/post show
*/

class Post extends Frontend_Controller
{
	public $init_control_function = array('category', 'search');
	
	public function __construct()
	{
		parent::__construct();
	}

	// Remapping custom URI 
	public function _remap($method)
	{
		$uri = $this->uri->segment_array();

		// If first segment not specified
		if(! isset($uri[1])) {
			$this->index();
		}

		// If first segment match with one of the array
		elseif (in_array($uri[1], $this->init_control_function)) {
			$func = $uri[1];
			array_shift($uri);
			call_user_func_array('Post::'.$func, $uri);
		}

		// If first segment match one of the pages
		elseif ($this->mdl_post->is_slug_exist($uri[1]) || isset($uri[2]) && $this->mdl_post->is_slug_exist($uri[2])) {
			$this->detail_post($uri[1], isset($uri[2])?$uri[2]:null);
		}

		// Otherwise, set 404 status
		else 
		{
			show_404();
		}
	}
	
	// Cms home page
	function index()
	{	
		// Set as home
		$this->data['is_home'] = true;

		// Call current theme and show
		$this->template
			->set_theme($this->data['site_template'])
			->view('home', $this->data);
	}

	// Show category/loop page
	function category($param_one = null, $param_two = null, $param_three = null)
	{
		// Check, if get variable p is exist
		if (isset($_GET['p']) && $_GET['p'] != '')
		{	
			$limit_order = $_GET['p'];
		} 
		else
		{
			if(isset($param_three))
			{
				$limit_order = $param_three;
			} 
			else
			{
				$limit_order = 1;
			}
		}

		// Parameter control
		if ($param_two == null) 
		{
			$post_type = 'post';
			$category_slug = $param_one;
		} 
		else 
		{
			$post_type = $param_one;
			$category_slug = $param_two;
		}
		
		// Get loop post by category slug
		$limit = 2;
		$this->data['post_data'] = $this->mdl_post->get_loop_post_with_content($category_slug, 'array', 'latest', $limit, $limit_order, $post_type);
		$this->data['slug_cat']	= $category_slug;
		$this->data['total_post'] = $this->mdl_post->get_loop_post_with_content($category_slug, 'total', 'latest', null, null, $post_type);
		$this->data['is_loop_post'] = true;
		$this->load->view($this->data['template_name'].'category', $this->data);
	}
	
	// Show single/detail post/page
	function detail_post($category_slug = null, $post_slug = null)
	{
		// Is set post_slug?
		if(isset($post_slug)) {
			// If is set, set post_slug as slug
			$slug = $post_slug;
		} else {
			// If not, set post_slug as category slug
			$slug = $category_slug;
		}

		// Get the post id from slug
		$post_id = $this->mdl_post->get_post_id($slug);
			
		// Update view counter ...
			
		// Set data post/page support
		$this->data['metadesc'] = $this->mdl_post->get_post_meta($post_id, 'meta_description');
		$this->data['metakey'] = $this->mdl_post->get_post_meta($post_id, 'meta_keyword');
		$this->data['og_image'] = $this->mdl_post->get_post_meta($post_id, 'thumbnail', 'md');
		$this->data['slug'] = $slug;
		$this->data['post_id'] = $post_id;
		$this->data['is_single'] = true;
		$this->data['draft_mode_html'] = '';
		
		// If role is admin, get post data otherwise not published, this is draft mode
		if ((isset($this->data['role_id'])) && ($this->data['role_id'] == '1'))
		{
			$this->data['post_data'] =  $this->mdl_post->get_single_post(null, 'post_slug', $slug);
			
			if ($this->data['post_data'][0]->post_status == 'draft')
			{
				$this->data['draft_mode_html'] = '
				<div class="jooglo_draft_mode" style="font-family:arial;opacity:0.5;z-index:99999;position:absolute;top:120px;left:0px;padding:10px;background:#444;color:#fff;">
					Draft Mode<br/>
				</div>';
			}
		}
		else
		{
			$this->data['post_data'] =  $this->mdl_post->get_single_post('publish', 'post_slug', $slug);
		}
			
		if (empty($this->data['post_data']))
		{
			// There is no data maybe page is not published, show 404
			show_404();
		}
		else
		{
			// The post/page data
			foreach ($this->data['post_data'] as $row)
			{
				$this->data['comment_enable'] = $row->comment_status;
				$this->data['thumbnail'] = $this->mdl_post->get_post_meta($row->ID, 'thumbnail');
				$this->data['content'] = $row->post_content;
				$this->data['type'] = $row->post_type;
				$this->data['title'] = $row->post_title;
				$this->data['date'] = $row->post_date;
				$this->data['parent'] = $row->post_parent;
				$this->data['author'] = $row->post_author;
			}
			
			// Check if the slug is page? process with page logic
			if ($this->mdl_post->is_this_page($slug))
			{
				// Check is the page has been set with custom page template ?
				if ($this->custom_page_template->is_this_use_custom($slug))
				{
					// Custom page template activate
					$the_page_template = $this->mdl_post->get_post_meta($post_id, 'template');
				}
				else
				{
					// Default template page activate
					$the_page_template = 'page';
				}
					
				$this->template
					->set_partial('header', 'header')
					->set_partial('footer', 'footer')
					->view($the_page_template, $this->data);
			}
			else
			{	
				// Else process with post logic
				$this->data['category'] = $this->mdl_taxonomy->get_post_category($post_id);
				$this->data['tags'] = $this->mdl_taxonomy->get_post_tags($post_id);
						
				$this->template
					->set_partial('header', 'header')
					->set_partial('footer', 'footer')
					->view('detail', $this->data);
			}
			
			// Show draft mode html
			echo $this->data['draft_mode_html'];
		}
	}
	
	// Search handle
	function search($type = 'basic', $keyword = null, $page = 1)
	{
		$limit = 15;
		$this->data['is_search'] = true;
		
		if (isset($_GET['s']) && $_GET['s'] != '')
		{
			if(isset($_GET['p']) && $_GET['p'] != '')
			{
				$page = $_GET['p'];
			} 
			else
			{
				$page = 1;
			}
				
			$this->data['search_data'] = true;
			$this->data['search_item'] = $_GET['s'];

			$this->data['post_data'] = $this->mdl_post->search_post($this->data['search_item'], 'array', null, 'publish', $limit, $page);
			$this->data['count'] = $this->mdl_post->search_post($this->data['search_item'], 'total', null, 'publish', $limit, $page);
		} 
		else 
		{
			$this->data['search_data'] = false;
		}
			
		// Check is template search exist ? if exist show, if no set not found
		$file_path = 'application/views/'.$this->data['template_name'].'search.php';

		$check = file_exists($file_path);

		if(!$check)
		{
			redirect();
		}
		else
		{
			$this->load->view($this->data['template_name'].'search', $this->data);
		}
	}
}