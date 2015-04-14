<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
The controller that handle page/post show
*/

class Post extends Frontend_Controller
{
	public $init_control_function = array('category', 'search', 'tags');
	
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

	// Show category loop page
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
		$this->mdl_post->update_view($post_id);
			
		// Set data post/page support
		$this->data['metadesc'] = $this->mdl_post->get_post_meta($post_id, 'meta_description');
		$this->data['metakey'] = $this->mdl_post->get_post_meta($post_id, 'meta_keyword');
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
				<div class="jooglo_draft_mode" style="font-family:arial;opacity:0.5;z-index:99999;position:fixed;top:120px;left:0px;padding:10px;background:#444;color:#fff;">
					Draft Mode<br/>
				</div>';
			}
		}
		else
		{
			// If active user is post author, allow him to see his post.
			$post_author = $this->mdl_post->get_post_author('ID', $post_id);
			if ($this->data['user_id'] == $post_author)
			{
				$this->data['post_data'] =  $this->mdl_post->get_single_post(null, 'post_slug', $slug);
				
				if ($this->data['post_data'][0]->post_status == 'draft')
				{
					$this->data['draft_mode_html'] = '
					<div class="jooglo_draft_mode" style="font-family:arial;opacity:0.5;z-index:99999;position:fixed;top:120px;left:0px;padding:10px;background:#444;color:#fff;">
						Draft Mode | Your post<br/>
					</div>';
				}
			}
			else
			{
				$this->data['post_data'] =  $this->mdl_post->get_single_post('publish', 'post_slug', $slug);
			}
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
				$this->data['status'] = $row->post_status;
				$this->data['type'] = $row->post_type;
				$this->data['slug'] = $row->post_slug;
				$this->data['title'] = $row->post_title;
				$this->data['date'] = $row->post_date;
				$this->data['parent'] = $row->post_parent;
				$this->data['author'] = $row->post_author;
			}
	
			// Check if the slug is page? process with page logic
			if ($this->mdl_post->is_this_page($slug))
			{	
				// Check is the page has been set with custom page template ?
				if ($this->custom_page_template->is_this_use_custom($post_id))
				{
					// Custom page template activate
					$the_page_template = $this->mdl_post->get_post_meta($post_id, 'post_template');
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
				$this->data['tags_array'] = $this->mdl_taxonomy->get_post_tags($post_id);
				
				$tags = null;
				
				foreach ($this->data['tags_array'] as $row)
				{
					$tags .= $row->name.', ';
				}
				
				$this->data['tags']  = $tags;
				
				$this->template
					->set_partial('header', 'header')
					->set_partial('footer', 'footer')
					->view('detail', $this->data);
			}
			
			// Show draft mode html
			echo $this->data['draft_mode_html'];
		}
	}
	
	// Show tags loop page
	function tags($tag_slug = null)
	{
		$this->data['total'] = $this->mdl_post->get_post_by_term($tag_slug, 'total', 'publish', 'post_tag');
		
		// Paging
		$config['base_url'] = site_url('tags/'.$tag_slug);
		$config['total_rows'] = $this->data['total']; 
		$config['per_page'] = 2; 
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = '<i class="icon-long-arrow-left"></i> First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last <i class="icon-long-arrow-right"></i>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="//" class="paging-item">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();
		
		// Query
		$this->data['results'] =  $this->mdl_post->get_post_by_term($tag_slug, 'array', 'publish', 'post_tag', $config['per_page'], $this->uri->segment(3));
		
		$this->template
			->set_partial('header', 'header')
			->set_partial('footer', 'footer')
			->view('tags', $this->data);
	}
	
	// Search handle
	function search($keyword = null)
	{
		$this->data['keyword'] = urldecode($keyword);
		$this->data['total'] = $this->mdl_post->search_post($this->data['keyword'], 'total', 'product', 'publish');
		
		// Paging
		$config['base_url'] = site_url('search/'.$keyword);
		$config['total_rows'] = $this->data['total']; 
		$config['per_page'] = 2; 
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="pagination">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = '<i class="icon-long-arrow-left"></i> First';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_link'] = 'Last <i class="icon-long-arrow-right"></i>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="//">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config); 
		$this->data['pagination'] = $this->pagination->create_links();
		
		// Query
		$this->data['results'] = $this->mdl_post->search_post($this->data['keyword'], 'array', 'product', 'publish', $config['per_page'], $this->uri->segment(3));
		
		$this->template
			->set_partial('header', 'header')
			->set_partial('footer', 'footer')
			->view('search', $this->data);
	}
}