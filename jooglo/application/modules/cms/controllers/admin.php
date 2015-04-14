<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Backend_Controller {

	public function __construct()
	{
		parent::__construct();

		// Load helper
		$this->load->helper('excerpt');
		
		// Delete preview data
		$this->mdl_post->delete_preview();
	}

	// Admin homepage
	public function index()
	{
		$this->data['title_page'] = 'Dashboard';
		$this->template->view('home', $this->data); 
	}
	
	// Report
	public function report()
	{
		echo 'Report';
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT ENTRIES
	| ---------------------------------------------------------------
	|
	| Entry master data free crud
	| Slide, Food, Band, People, Etc
	| 
	*/
	
	/*
	Show new entry form
	*/
	public function add_entry_type()
	{
		// Title_page
		$this->data['title_page'] = 'Add Entry';
		$this->template->view('entry_form_add_type', $this->data); 
	}
	
	/*
	Show entry list
	*/
	public function entry($entry_type = null)
	{
		if (empty($entry_type))
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_failed'));
			redirect('control');
		}
		else
		{
			// Title_page
			$this->data['title_page'] = make_lable($entry_type);
			$this->data['entry_type'] = $entry_type;
			
			// Breadcrumb
			$this->data['breadcrumb'] = array(
				make_lable($entry_type) => false
			);
		
			$this->data['pg_query'] = $this->mdl_entries->get_entries($entry_type,30);

			$this->template->view('entry', $this->data);
		}
	}
	
	public function delete_entry($entry_type = null, $id = null)
	{
		if (empty($id) || empty($entry_type))
		{
			echo 'undefined id or post type, delete?';
		}
		else
		{
			$op = $this->mdl_entries->delete($id);
			if ($op == true)
			{
				$this->session->set_flashdata('message', $this->lang->line('jooglo_success_delete'));
				redirect('cms/admin/entry/'.$entry_type);
			}
		}
	}
	
	public function edit_entry($entry_type = null,$id = null)
	{
		if (empty($entry_type) || empty($id))
		{
			echo 'undefined entries or undefined id';
		}
		else
		{	
			// Title_page
			$this->data['title_page'] = 'Edit '.ucfirst($entry_type);
			$this->data['entry_type'] = $entry_type;
			
			// Breadcrumb
			$this->data['breadcrumb'] = array(
				ucfirst($entry_type) => 'cms/admin/entry/'.$entry_type,
				'Edit '.ucfirst($entry_type) => false
			);
			
			$this->data['form_type'] = 'edit';
			$this->data['pg_query'] = $this->mdl_entries->get_entries_by_id($id);
			$this->template->view('entry_form', $this->data);
		}
	}
	
	public function new_entry($entry_type = null)
	{
		if (empty($entry_type))
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_undefined_entry'));
			redirect('cms/admin');
		}
		else
		{
			// Title_page
			$this->data['title_page'] = 'New '.ucfirst($entry_type);
			$this->data['entry_type'] = $entry_type;
			
			// Breadcrumb
			$this->data['breadcrumb'] = array(
				ucfirst($entry_type) => 'cms/admin/entry/'.$entry_type,
				'New '.ucfirst($entry_type) => false
			);
			
			$this->data['form_type'] = 'new';
			
			$this->template->view('entry_form', $this->data);
		}
	}
	
	public function update_entry()
	{
		// Master post
		$this->data['title'] = $this->input->post('title');
		$this->data['slug'] = $this->input->post('slug');
		$this->data['entry_type'] = $this->input->post('entry_type');
		$this->data['entry_id'] = $this->input->post('entry_id');
		$this->data['form_type'] = $this->input->post('form_type');
			
		// Get the extra field information
		$meta_info = $this->mdl_entries->get_meta_information($this->data['entry_type']);
			
		// Validation
		$this->load->helper(array('form', 'url'));
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('slug', 'Slug', 'required');
			
		if ($this->form_validation->run() == false)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_must_fill_global'));
			redirect('cms/admin/edit_entry/'.$this->data['entry_type'].'/'.$this->data['entry_id']);
		}
		else
		{
			if ($this->data['form_type'] == 'edit')
			{
				// Take and update extra post meta
				if (!empty($meta_info))
				{
					foreach ($meta_info as $row => $value)
					{
						// Get post loop
						$meta_value_post =  $this->input->post($value['meta_key']);
							
						// Update meta param(meta_key, meta_name, new_value, entry_id)
						$this->mdl_entries->update_entry_meta($value['meta_key'], make_lable($value['meta_key']), $meta_value_post, $this->data['entry_id']);
					}
				}
					
				// Update post master
				$this->mdl_entries->update_entry_master('title', $this->data['title'], $this->data['entry_id']);
					
				// Slug checker, every entry must have unique slug
				$check = $this->mdl_entries->is_exist_slug($this->data['slug']);
				if ($check == true)
				{
					// If old slug, permit
					$old_slug = $this->mdl_entries->get_entry_slug($this->data['entry_id']);
					if ($old_slug == $this->data['slug']) {
						$continue = true;
					} else {
						$continue = false;
					}
				}
				else
				{
					$continue = true;	
				}
					
				if ($continue == true) 	
				{
					$this->mdl_entries->update_entry_master('slug', $this->data['slug'], $this->data['entry_id']);
					$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
					redirect('cms/admin/edit_entry/'.$this->data['entry_type'].'/'.$this->data['entry_id']);
				} 
				else 
				{
					$this->session->set_flashdata('message', $this->lang->line('jooglo_error_slug'));
					redirect('cms/admin/edit_entry/'.$this->data['entry_type'].'/'.$this->data['entry_id']);
				}
			}
			else
			{
				// Slug checker, every entry must have unique slug
				$check = $this->mdl_entries->is_exist_slug($this->data['slug']);
				if ($check == true)
				{
					$this->session->set_flashdata('message', $this->lang->line('jooglo_error_slug'));
					redirect('cms/admin/new_entry/'.$this->data['entry_type']);
				}
				else
				{
					$data = array( 
							'title' => $this->data['title'], 
							'slug' => $this->data['slug'], 
							'entry_type' => $this->data['entry_type']
							);
							
					$op = $this->mdl_entries->new_entries($data);
						
					if ($op == true)
					{
						// Get the id
						$entry_id = $this->mdl_entries->get_entries_id($this->data['slug'], 'slug');
							
						// Take and update extra post meta that has been existing
						if (!empty($meta_info))
						{
							foreach ($meta_info as $row => $value)
							{
								// Get post loop
								$meta_value_post =  $this->input->post($value['meta_key']);
								
								// Update meta param(meta_key, meta_name, new_value, entry id)
								$this->mdl_entries->update_entry_meta($value['meta_key'], make_lable($value['meta_key']), $meta_value_post, $entry_id);
							}
						}
							
						// Take and update new field extra
						foreach ($_POST as $name => $val)
						{
							$name = htmlspecialchars($name);
							$val = htmlspecialchars($val);
								
							if ($name == 'title' || $name == 'slug' || $name == 'entry_type' || $name == 'form_type' || $name == 'entry_id')
							{
								// Don't update if its master data 
							}
							else
							{
								// Update meta param(meta_key, meta_name, new_value, entry id)
								$this->mdl_entries->update_entry_meta($name, make_lable($name), $val, $entry_id);
							}
						}
							
						$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
						redirect('cms/admin/entry/'.$this->data['entry_type']);
					}
				}		
			}
		}	 
	}
	
	public function add_field_entry()
	{
		$input = $this->input->post('data_post');
		$input = explode('|', $input);
		
		$meta_key = strtolower(str_replace(' ', '_', $input[1]));
		$this->mdl_entries->update_entry_meta($meta_key, '', $input[2], $input[0]);
		echo 'success';
	}
	
	public function delete_field_entry($meta_key, $entry_id, $url_callback)
	{
		$this->mdl_entries->delete_meta($meta_key, $entry_id);
		
		$url_callback = base64_decode(urldecode($url_callback));
		redirect($url_callback);
	}
	/*
	| Close
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT POSTING
	| ---------------------------------------------------------------
	| 
	*/
	
	/*
	Yeah, lets show the post data
	*/
	public function post($status = 'all', $post_type = 'post')
	{
		// Title_page
		$this->data['title_page'] = make_lable($post_type);
		$this->data['status'] = $status;
			
		// Post type
		$this->data['post_type'] = $post_type;
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			make_lable($post_type) => 'control/post/'.$status.'/'.$post_type
		);

		// Control status post navigation
		if ($status == 'all'){
			$this->data['breadcrumb']['All'] = false;
		} else {
			$this->data['breadcrumb'][ucfirst($status)] = false;
		}
			
		// Control config pagination
		$this->data['num_rows'] = $this->mdl_post->get_post($post_type, 'total', $status);
		$config['base_url'] = site_url('cms/admin/post/'.$status.'/'.$post_type);
		
		$config['uri_segment'] = 6; 			
		$config['total_rows'] = $this->data['num_rows']; 
		$config['per_page'] = 5; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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

		$this->pagination->initialize($config); // initialize pagination
		$this->data['pagination'] = $this->pagination->create_links();
		$this->data['pg_query'] = $this->mdl_post->get_post($post_type, 'array', $status, $config['per_page'], $this->uri->segment(6));
		$this->template->view('post', $this->data); 
	}
	
	/*
	Show only trashed
	*/
	public function post_trash()
	{
		// Title_page
		$this->data['title_page'] = 'Post Trashed';
		$this->data['status'] = 'Trashed';
			
		// Post type
		$this->data['post_type'] = 'all';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			make_lable('all') => 'control/post_trash/all'
		);
			
		// Control config pagination
		$this->data['num_rows'] = $this->mdl_post->get_trashed_post('total');
		$config['base_url'] = site_url('cms/admin/post_trash');
		
		$config['uri_segment'] = 4; 			
		$config['total_rows'] = $this->data['num_rows']; 
		$config['per_page'] = 10; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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

		$this->pagination->initialize($config); // initialize pagination
		$this->data['pagination'] = $this->pagination->create_links();
		$this->data['pg_query'] = $this->mdl_post->get_trashed_post('array', null, $config['per_page'], $this->uri->segment(4));
		$this->template->view('post', $this->data); 
	}
	
	/*
	Search post handle
	*/
	public function post_search()
	{
		// Get the param
		$keyword = $this->input->post('inp_search');
		$status = $this->input->post('status');
		$post_type = $this->input->post('post_type');
			
		// Title_page
		$this->data['title_page'] = make_lable($post_type);
		
		// Post type and status
		$this->data['status'] = $status;
		$this->data['post_type'] = $post_type;
		
		// Search mode var
		$this->data['keyword'] = $keyword;
		$this->data['search_mode'] = true;
		
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			make_lable($post_type) => 'control/post/'.$status.'/'.$post_type,
			ucfirst($status) => 'control/post/'.$status.'/'.$post_type,
			'Search' => false
		);
			
		$this->data['num_rows'] = $this->mdl_post->search_post($keyword, 'total', $post_type, $status, null, null);
		$this->data['status'] = $status;
		$this->data['pg_query'] = $this->mdl_post->search_post($keyword, 'array', $post_type, $status, null, null);
		$this->template->view('post', $this->data); 
	}
	
	/*
	Show new post type form
	*/
	public function add_post_type()
	{
		// Title_page
		$this->data['title_page'] = 'Add Post Type';
		$this->template->view('post_form_add_type', $this->data); 
	}
	
	/*
	Show new post form
	default post is normal
	*/
	public function post_new($post_type = 'post')
	{
		// Title_page
		$this->data['title_page'] = make_lable($post_type);
		
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			make_lable($post_type) => 'control/post/all/'.$post_type,
			'New' => false
		);
			
		// Type form post
		$this->data['type_form_post'] = 'new';
		
		$this->data['post_type'] = $post_type;
		$this->data['current_post_type'] = $post_type;
		$this->data['meta_information'] = $this->mdl_post->get_meta_information($post_type);
		$this->data['cat_query'] = $this->mdl_taxonomy->get_all_terms('category'); 
		$this->data['custom_page_template'] = $this->custom_page_template->get_custom_page();
		$this->template->view('post_form', $this->data); 
	}
	
	/* 
	Show post edit form
	*/
	public function post_edit($post_id)
	{
		// Get post type
		$post_type = $this->mdl_post->get_post_type('id', $post_id);
	
		// Title_page
		$this->data['title_page'] = make_lable($post_type);
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			make_lable($post_type) => 'control/post/all/'.$post_type,
			'Edit' => false
		);
			
		// Type form post
		$this->data['type_form_post'] = 'edit';
		
		$this->data['meta_information'] = $this->mdl_post->get_meta_information($post_type);
		$this->data['cat_query'] = $this->mdl_taxonomy->get_all_terms('category'); 
		$this->data['pg_query'] = $this->mdl_post->get_single_post(null, 'ID', $post_id);
		$this->data['custom_page_template'] = $this->custom_page_template->get_custom_page();
		$this->template->view('post_form', $this->data);
	}
	
	/*
	Update the posting
	*/
	public function post_update()
	{
		$allow_edit = false;
		$post_type =  $this->input->post('post_type', true);
		$this->url_source =  $this->input->post('url_source', true);
		$cat_id = $this->input->post('cat_id', true);
		$current_cat_id = $this->input->post('current_cat_id', true);
		$post_parent = $this->input->post('post_parent', true);
		$slug = $this->input->post('slug', true);
		$post_id = $this->input->post('post_id', true);
		$current_slug = $this->mdl_post->get_post_slug($post_id);
		$post_date = $this->input->post('post_date', true);
		$featured_image = $this->input->post('featured_image', true);
		
		// Cat id control
		if (empty($cat_id))
		{
			$cat_id = $current_cat_id;
		}
		
		// Slug check
		$slug_check = $this->mdl_post->is_slug_exist($slug);
		
		if ($slug_check == 1)
		{	
			// Check current slug
			if ($current_slug == $slug)
			{
				$allow_edit = true;
			}
			else
			{
				$allow_edit = false;
			}
		} 
		else 
		{
			$allow_edit = true;
		}
		
		// Start update
		if ($allow_edit == false)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_slug'));
			redirect($this->url_source);
		}
		else
		{
			$data = array (
				'cat_id' => $cat_id,
				'post_id' => $post_id,
				'post_status' => $this->input->post('post_status'),
				'post_type' => $post_type,
				'post_title' => $this->input->post('post_title'),
				'post_date' => $post_date,
				'post_content' => $this->input->post('post_content'),
				'post_author' => $this->data['user_id'],
				'slug' => $slug,
				'tags' => $this->input->post('tags')
			);
				
			if ($this->mdl_post->update_post($data))
			{
				$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
				$link_to = site_url('cms/admin/post_edit/'.$post_id);
				
				// Take and update extra post meta
				$meta_information = $this->mdl_post->get_meta_information($post_type);
				if (!empty($meta_information))
				{
					foreach ($meta_information as $row => $value)
					{
						// Get post loop
						$meta_value_post =  $this->input->post($value['meta_key']);
							
						// Update meta / custom field
						$this->mdl_post->update_meta($value['meta_key'], $meta_value_post, $post_id, 'publish');
					}
				}
				
				// Take and update new field extra
				foreach ($_POST as $name => $val)
				{
					$name = htmlspecialchars($name);
					$val = htmlspecialchars($val);
					
					$not_allowed_field = array('cat', 'url_source', 'post_id', 'post_content', 'current_cat_id', 'post_status', 'post_type', 'cat_id', 'post_date', 'slug', 'tags', 'post_title' , 'post_author', 'post_date');
					if (!in_array($name, $not_allowed_field))
					{
						// Update meta
						$this->mdl_post->update_meta($name, $val, $post_id, 'publish');
					}
				}
				
				// If the post is page. Update template.
				if ($post_type == 'page')
				{
					$this->mdl_post->update_meta('post_template', $this->input->post('post_template'), $post_id );
				}
					
				redirect($link_to);
			}
		}	
	}
	
	/*
	Create new post
	*/
	public function post_create()
	{
		$post_type =  $this->input->post('post_type');
		$this->url_source =  $this->input->post('url_source');
		$cat_id = $this->input->post('cat_id');
		$current_cat_id = $this->input->post('current_cat_id');
		$post_parent = $this->input->post('post_parent');
		$slug = $this->input->post('slug');
		$post_date = $this->input->post('post_date');
		$featured_image = $this->input->post('featured_image', true);
		
		// Get meta information
		$meta_information = $this->mdl_post->get_meta_information($post_type);
		
		// Cat id control
		if (empty($cat_id))
		{
			$cat_id = $current_cat_id;
		}
		
		// Slug check
		$slug_check = $this->mdl_post->is_slug_exist($slug);
		
		if ($slug_check == 1)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_slug'));
			redirect($this->url_source);
		} 
		else 
		{	
			$data = array (
				'cat_id' => $cat_id,
				'post_status' => $this->input->post('post_status'),
				'post_type' => $post_type,
				'post_title' => $this->input->post('post_title'),
				'post_date' => $post_date,
				'post_content' => $this->input->post('post_content'),
				'post_author' => $this->data['user_id'],
				'slug' => $slug,
				'tags' => $this->input->post('tags')
			);
					
			if ($this->mdl_post->insert_post($data))
			{
				$post_id = $this->mdl_post->get_post_id($slug);
				$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
				$link_to = site_url('cms/admin/post_edit/'.$post_id);
				
				// Take and update extra post meta that has been exist	
				if (!empty($meta_information))
				{
					foreach ($meta_information as $row => $value)
					{
						// Get post loop
						$meta_value_post =  $this->input->post($value['meta_key']);
							
						// Update meta / custom field
						$this->mdl_post->update_meta($value['meta_key'], $meta_value_post, $post_id, 'publish');
					}
				}
				
				// Take and update new field extra
				foreach ($_POST as $name => $val)
				{
					$name = htmlspecialchars($name);
					$val = htmlspecialchars($val);
					
					$not_allowed_field = array('cat', 'url_source', 'post_id', 'post_content', 'current_cat_id', 'post_status', 'post_type', 'cat_id', 'post_date', 'slug', 'tags', 'post_title' , 'post_author', 'post_date');
					if (!in_array($name, $not_allowed_field))
					{
						// Update meta
						$this->mdl_post->update_meta($name, $val, $post_id, 'publish');
					}
				}
				
				// Page meta
				if ($post_type == 'page')
				{
					$this->mdl_post->update_meta('post_template', $this->input->post('post_template'), $post_id );	
				}
					
				redirect($link_to);
			}
		}	
	}
	
	/*
	Live preview post
	*/
	public function post_preview()
	{
		$post_type =  $this->input->post('post_type');
		$this->url_source =  $this->input->post('url_source');
		$cat_id = $this->input->post('cat_id');
		$current_cat_id = $this->input->post('current_cat_id');
		$post_parent = $this->input->post('post_parent');
		$slug = $this->input->post('slug');
		$post_date = $this->input->post('post_date');
		$featured_image = $this->input->post('featured_image', true);
		
		// Cat id control
		if (empty($cat_id))
		{
			$cat_id = $current_cat_id;
		}
		
		// Slug check
		$slug_check = $this->mdl_post->is_slug_exist($slug);
		
		if ($slug_check == 1)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_slug'));
			redirect($this->url_source);
		} 
		else 
		{	
			$data = array (
				'cat_id' => $cat_id,
				'post_status' => 'preview',
				'post_type' => $post_type,
				'post_title' => $this->input->post('post_title'),
				'post_date' => $post_date,
				'post_content' => $this->input->post('post_content'),
				'post_author' => $this->data['user_id'],
				'slug' => $slug,
				'tags' => $this->input->post('tags'),
				'metadesc' => $this->input->post('metadesc'),
				'metakey' => $this->input->post('metakey')
			);
			
			if ($this->mdl_post->insert_post($data))
			{
				$post_id = $this->mdl_post->get_post_id($slug);
				$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
				$link_to = site_url('cms/admin/post_edit/'.$post_id);
					
				// General meta
				$this->mdl_post->update_meta('meta_description', $data['metadesc'], $post_id, 'preview');
				$this->mdl_post->update_meta('meta_keyword', $data['metakey'], $post_id, 'preview');
				$this->mdl_post->update_meta('featured_image', $featured_image, $post_id, 'preview');
				
				// Page meta
				if ($post_type == 'page')
				{
					$this->mdl_post->update_meta('post_template', $this->input->post('post_template'), $post_id, 'preview');	
				}
					
				echo $slug;
			}
		}	
	}
	
	/*
	| Close
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| PUBLIC FUNCTION SETTER
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Delete post
	*/
	public function set_delete($post_id, $url_decode)
	{
		$title = $this->mdl_post->get_post_title('id', $post_id);
		$this->mdl_post->delete_this($post_id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_decode));
		redirect($url_callback);
	}
	
	/*
	Set trash
	*/
	public function set_trash($post_id, $url_decode)
	{
		$title = $this->mdl_post->get_post_title('id', $post_id);
		$this->mdl_post->trash_this($post_id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_decode));
		redirect($url_callback);
	}
	
	/*
	Set restore
	*/
	public function set_restore($post_id, $url_decode)
	{
		$title = $this->mdl_post->get_post_title('id', $post_id);
		$this->mdl_post->restore_this($post_id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_decode));
		redirect($url_callback);
	}
	
	/*
	Publish post
	*/
	public function set_publish($post_id, $url_decode)
	{	
		$title = $this->mdl_post->get_post_title('id', $post_id);
		$this->mdl_post->publish_this($post_id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_decode));
		redirect($url_callback);
	}
	
	/*
	Set draft
	*/
	public function set_draft($post_id, $url_decode)
	{
		$title = $this->mdl_post->get_post_title('id', $post_id);
		$this->mdl_post->draft_this($post_id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_decode));
		redirect($url_callback);
	}
	/*
	| Close 
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT USER
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Show user all role
	*/
	public function user($status = 'all')
	{
		// Title_page
		$this->data['title_page'] = 'User';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => false
		);

		if($status != 'all')
		{
			$this->data['breadcrumb']['User'] = base_url('control/user');
			$this->data['breadcrumb'][ucfirst($status)] = false;
		}
			
		$this->data['num_rows'] = $this->mdl_user->get_tot_user($status);
			
		$config['base_url'] = site_url('cms/admin/user/'.$status);
		$config['total_rows'] = $this->data['num_rows']; 
		$config['per_page'] = 5; 
		$config['uri_segment'] = 5; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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
		$this->data['pg_query'] = $this->mdl_user->get_list_user($status, $config['per_page'], $this->uri->segment(5));
		$this->template->view('user', $this->data); 
	}
	
	/*
	Show form edit user
	*/
	public function user_edit($user_id)
	{
		// Permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		// Title_page
		$this->data['title_page'] = 'Edit User';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'User' => 'control/user',
			'Edit User' => false
		);
			
		$this->data['form_type'] = 'edit';
		$this->data['user_data'] = $this->mdl_user->get_user($user_id, 'id');
		$this->data['role'] = $this->mdl_role->get_list_role();
		$this->template->view('user_form', $this->data); 
	}
	
	/*
	Update user
	*/
	public function user_update()
	{
		// Permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		// Action
		$data = array (
			'user_id' => $this->input->post('user_id'),
			'username' => $this->input->post('username'),
			'email' =>$this->input->post('email'),
			'role' => $this->input->post('role'),
			'password' => $this->input->post('password')
		);
				
		// Role update		
		if (!empty($data['role']))
		{
			$this->mdl_role->update_user_role($data['user_id'], $data['role']);
		}
	
		// Check username
		if ($this->mdl_user->is_exist_username($data['username']))
		{	
			$current_username = $this->mdl_user->get_username($data['user_id'], 'id');
			
			if ($data['username'] != $current_username)
			{
				$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist'));
				redirect('cms/admin/user_edit/'.$data['user_id']);	
				exit;
			}
		}
		else
		{
			$this->mdl_user->update_user_master($data['user_id'], 'username', $data['username']);
		}
		
		// Check email
		if ($this->mdl_user->is_exist_email($data['email']))
		{
			$current_email = $this->mdl_user->get_user_mail($data['user_id'], 'id');
			
			if ($data['email'] != $current_email)
			{
				$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist'));
				redirect('cms/admin/user_edit/'.$data['user_id']);	
				exit;
			}
		}
		else
		{
			$this->mdl_user->update_user_master($data['user_id'], 'email', $data['email']);
		}
		
		// Password update		
		if (!empty($data['password']))
		{
			$password_hashed = $this->nyan_auth->hash_password($data['password']);
			$this->mdl_user->update_user_password($password_hashed, $data['user_id']);
		}
		
		// Meta update all
		$this->mdl_user->update_user_meta('biography', 'Biography', $this->input->post('biography'), $data['user_id']);
		$this->mdl_user->update_user_meta('first_name', 'First Name', $this->input->post('f_name'), $data['user_id']);
		$this->mdl_user->update_user_meta('last_name', 'Last Name', $this->input->post('l_name'), $data['user_id']);
		$this->mdl_user->update_user_meta('address', 'Address', $this->input->post('address'), $data['user_id']);
		$this->mdl_user->update_user_meta('phone', 'Phone', $this->input->post('phone'), $data['user_id']);
				
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
		redirect('cms/admin/user_edit/'.$data['user_id']);		
	}
	
	/*
	Activate user
	*/
	public function user_activate($id, $url_callback)
	{
		// Permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		$username = $this->mdl_user->get_username($id, 'id');
				
		$op = $this->mdl_user->activate_user($id);
		
		if ($op == true)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
			$url_callback = base64_decode(urldecode($url_callback));
			redirect($url_callback);
		}
	}
	
	/*
	Disactive user
	*/
	public function user_disactive($id, $url_callback)
	{
		// Permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		$username = $this->mdl_user->get_username($id, 'id');
				
		$op = $this->mdl_user->inactive_user($id);
	
		if ($op == true)
	 	{
	 		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
	 		$url_callback = base64_decode(urldecode($url_callback));
	 		redirect($url_callback);
	 	} 
	}
	
	/*
	Search user
	*/
	public function user_search()
	{
	 	$this->data['title_page'] = 'Search User';
		
		$this->data['breadcrumb'] = array(
	 		'User' => 'cms/admin/user',
	 		'Search User' => false
	 	);
			
	 	$this->data['search_result'] = 'search';
		
	 	$username = $this->input->post('inp_search');
	 	$this->data['keyword'] = $username;
			
	 	$this->data['num_rows'] = $this->mdl_user->search('total', $username);
		$this->data['pg_query'] = $this->mdl_user->search('array', $username);
		$this->template->view('user', $this->data); 
	}
	
	/*
	Show new form
	*/
	public function user_new()
	{
	 	// Permission action check
	 	$allowed_role = array (1);
	 	$this->permission_action($allowed_role);
		
	 	// Title_page
	 	$this->data['title_page'] = 'New User';
			
	 	// Breadcrumb
	 	$this->data['breadcrumb'] = array(
	 		'User' => 'control/user',
	 		'New User' => false
		);
			
		$this->data['form_type'] = 'new';
		$this->data['role'] = $this->mdl_role->get_list_role();
		$this->template->view('user_form', $this->data); 
	}
	
	/*
	Add user
	*/
	public function user_add()
	{
	 	// Permission action check
	 	$allowed_role = array (1);
	 	$this->permission_action($allowed_role);
		
	 	// Title_page
	 	$this->data['title_page'] = 'New User';
			
	 	// Breadcrumb
	 	$this->data['breadcrumb'] = array(
	 		'User' => 'control/user',
	 		'New User' => false
		);
		
		// Get post
		$param['username'] = $this->input->post('username');
		$param['email'] = $this->input->post('email');
		$param['role'] = $this->input->post('role');
		$param['role_id'] = $this->input->post('role');
		$param['status'] = 'active';
		$param['role'] = $this->input->post('role');
		$param['password'] = $this->input->post('password');
		
		// Check username
		$check = $this->mdl_user->is_exist_username($param['username']);
		if ($check == 1)
		{	
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist_username'));
			redirect('cms/admin/user_new');
			exit;
		}
		
		// Check email
		$check = $this->mdl_user->is_exist_email($param['email']);
		if ($check == 1)
		{	
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist_email'));
			redirect('cms/admin/user_new');
			exit;
		}
		
		// Validation
		$this->form_validation->set_rules('role', 'Role', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		
		if ($this->form_validation->run() == false) 
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_must_fill'));
			redirect('cms/admin/user_new');
			exit;
		} 
			
		// Insert new user
		$user_id = $this->mdl_user->insert_user($param);
		
		// Update meta
		$this->mdl_user->update_user_meta('first_name', 'First Name', $this->input->post('f_name'), $user_id);
		$this->mdl_user->update_user_meta('last_name', 'Last Name', $this->input->post('l_name'), $user_id);
		$this->mdl_user->update_user_meta('phone', 'Last Name', $this->input->post('phone'), $user_id);
		$this->mdl_user->update_user_meta('address', 'Last Name', $this->input->post('address'), $user_id);
		$this->mdl_user->update_user_meta('biography', 'Last Name', $this->input->post('biography'), $user_id);
		
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
		redirect('cms/admin/user_edit/'.$user_id);
	}
	
	/*
	Permanent delete
	*/
	public function user_delete($id)
	{
		// Permission action check
		$allowed_role = array (1);
		$this->permission_action($allowed_role);
		
		// Action
		$username = $this->mdl_user->get_username($id, 'id');
		$op = $this->mdl_user->permanent_delete($id);
		
		if ($op == true)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_success_delete'));
			redirect('cms/admin/user');
		}  
	}
	/*
	| Close 
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT COMMENT
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Show all comment with filter
	*/
	public function comment($status = 'all')
	{
		// Title_page
		$this->data['title_page'] = 'Comments';
		
		// Breadcrumb
		$this->data['breadcrumb']['Comment'] = false;
		
		if ($status != 'all')
		{
			$this->data['breadcrumb']['Comment'] = base_url('cms/admin/comment/'.$status);
			$this->data['breadcrumb'][ucfirst($status)] = false;
		}
		
		$config['base_url'] = site_url('cms/admin/comment/'.$status);
		
		// Set status
		if ($status == 'all')
		{
			$status = null;
		}
		
		// Get total
		$this->data['total'] = $this->mdl_comment->get_comments('total', $status);
	
		$config['total_rows'] = $this->data['total']; 
		$config['per_page'] = 5; 
		$config['uri_segment'] = 5; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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
		
		$limit_order = $this->uri->segment(5);
		
		if (empty($limit_order))
		{
			$limit_order = 1;
		}
		
		// Query
		$this->data['status'] = $status;
		$this->data['pg_query'] = $this->mdl_comment->get_comments('array', $status, null, null, null, $config['per_page'], $limit_order);
		
		$this->template->view('comment', $this->data); 
	}
	
	/*
	Search
	*/
	public function comment_search()
	{
		// Title_page
		$this->data['title_page'] = 'Comment';
		$this->data['keyword'] = $this->input->post('inp_search');
		$this->data['status'] = $this->input->post('status');
		$this->data['search_result'] = true;
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'Comment' => 'cms/admin/comment',
			'Search Comment' => false
		);
		
		$this->data['num_rows'] = $this->mdl_comment->search('total', null, $this->data['keyword']);
		$this->data['pg_query'] = $this->mdl_comment->search('array', null, $this->data['keyword'], null, 30, 0);
		
		$this->template->view('comment', $this->data); 
	}
	
	/*
	Comment action
	*/
	public function comment_action($id, $action = null, $url_callback)
	{
		if ($action == 'delete') 
		{
			$this->mdl_comment->delete($id);
		}
		else if ($action == 'unapprove') 
		{
			$this->mdl_comment->unapprove($id);
		}
		else if ($action == 'approve')
		{
			$this->mdl_comment->approve($id);
		}
		else if ($action == 'enable')
		{
			$this->mdl_post->enable_comm($id);
		}
		else if ($action == 'disable')
		{
			$this->mdl_post->disable_comm($id);
		}
		else
		{
			break;
			exit('Undefined comment action');
		}
		
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_global'));
		$url_callback = base64_decode(urldecode($url_callback));
		redirect($url_callback);
	}
	/*
	| Close 
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT CATEGORY
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Show category main menu
	*/
	public function category($post_type = 'post')
	{
		// Title_page
		$this->data['title_page'] = make_lable($post_type).' Category';
		$this->data['page_list'] = make_lable($post_type).' Category';
			
		// Breadcrumb
		$this->data['nav1'] = 'Post';
		$this->data['link_nav1'] = base_url('control/post_all');
		$this->data['nav2'] = 'Category';
		$this->data['post_type'] = $post_type;
		
		// Get total
		if ($post_type == 'post')
		{
			$this->data['total'] = $this->mdl_taxonomy->get_total_terms('category');
		}
		else
		{
			$this->data['total'] = $this->mdl_taxonomy->get_total_terms($post_type.'_category');
		}
		
		// Paging config
		$config['base_url'] = site_url('cms/admin/category/'.$post_type);
		$config['total_rows'] = $this->data['total']; 
		$config['per_page'] = 10; 
		$config['uri_segment'] = 5; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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
		if ($post_type == 'post')
		{
			$this->data['pg_query'] = $this->mdl_taxonomy->get_terms('category', $config['per_page'], $this->uri->segment(5));
		}
		else
		{
			$this->data['pg_query'] = $this->mdl_taxonomy->get_terms($post_type.'_category', $config['per_page'], $this->uri->segment(5));
		}
		
		// Show
		$this->template->view('category_tags', $this->data);
	}
	
	/*
	Show form new category
	*/
	function category_new($post_type = 'post')
	{
		// Title_page
		$this->data['title_page'] = 'New '.ucfirst($post_type).' Category';
			
		// Breadcrumb
		$this->data['nav1'] = ucfirst($post_type);
		$this->data['link_nav1'] = base_url('control/post_all');
		$this->data['nav2'] = 'Category';
		$this->data['link_nav2'] = base_url('control/category');
		$this->data['nav3'] = 'New Category';
			
		// Form type
		$this->data['form_type'] = 'new';
		$this->data['post_type'] = $post_type;

		$this->template->view('category_form', $this->data); 
	}
	
	/*
	Show form edit category
	*/
	function category_edit($id)
	{
		// Title_page
		$this->data['title_page'] = 'Edit Category';
			
		// Breadcrumb
		$this->data['nav1'] = 'Post';
		$this->data['link_nav1'] = base_url('control/post_all');
		$this->data['nav2'] = 'Category';
		$this->data['link_nav2'] = base_url('control/category');
		$this->data['nav3'] = 'Edit Category';
			
		// Form type
		$this->data['form_type'] = 'edit';
		$this->data['post_type'] = null;
			
		$this->data['pg_query'] = $this->mdl_taxonomy->get_term_by_id($id);
		$this->template->view('category_form', $this->data); 
	}
	
	/*
	Action add new category
	*/
	public function category_save()
	{
		// Get post
		$name = $this->input->post('name');
		$slug = $this->input->post('slug');
		$post_type = $this->input->post('post_type');
		$now = date('Y:m:d H:i:s');
			
		// Check is the category/term exist
		$check = $this->mdl_taxonomy->is_term_exist('slug', $slug);
			
		if ($check == true)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist'));
			redirect('cms/admin/category_new/'.$post_type);	
		}
		else
		{
			$data = array(
				'name' => $name,
				'slug' => $slug
			);
			
			if ($post_type == 'post')
			{
				$this->mdl_taxonomy->insert_term($data, 'category');
			}
			else
			{
				$this->mdl_taxonomy->insert_term($data, $post_type.'_category');
			}
			
			$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
			redirect('cms/admin/category/'.$post_type);	
		} 
	}
	
	/*
	Action update category
	*/
	public function category_update()
	{
		$name = $this->input->post('name');
		$slug = $this->input->post('slug');
			
		$id = $this->input->post('id');
			
		$data = array(
			'name' => $name,
			'slug' => $slug
		);
			
		$this->mdl_taxonomy->update_term($id, $data);
			
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
		redirect('cms/admin/category_edit/'.$id);		 
	}
	
	/*
	Action delete category
	*/
	public function category_delete($id, $url_callback)
	{
		$op = $this->mdl_taxonomy->delete_term($id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_delete'));

		$url_callback = base64_decode(urldecode($url_callback));
		redirect($url_callback);	 
	}
	/*
	| Close
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT TAGS
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Show tags
	*/
	public function tags()
	{
		// Title_page
		$this->data['title_page'] = 'Tags';
		$this->data['page_list'] = 'tags';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'Post' => 'control/post/post_all',
			'Tags' => false
		);
			
		$this->data['total'] = $this->mdl_taxonomy->get_tot_terms('post_tag');
			
		$config['base_url'] = site_url('cms/admin/post/tags');
		$config['total_rows'] = $this->data['total']; 
		$config['per_page'] = 15; 
		$config['uri_segment'] = 4; 
		$config['full_tag_open'] = '<div class="pagination pagination-small pagination-la"><ul>';
		$config['full_tag_close'] = '</ul></div>';
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

		$this->pagination->initialize($config); // initialize pagination
		$this->data['pagination'] = $this->pagination->create_links();
		$this->data['pg_query'] = $this->mdl_taxonomy->get_list_terms($config['per_page'], $this->uri->segment(4), 'post_tag');
		$this->template->view('category_tags', $this->data);
	}
	
	/*
	Show form new tags
	*/
	function tags_new()
	{
		// Title_page
		$this->data['title_page'] = 'New Tags';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'Post' => 'control/post/post_all',
			'Tags' => 'control/post/tags',
			'New Tags' => false
		);
			
		// Form type
		$this->data['form_type'] = 'new';

		$this->template->view('tags_form', $this->data); 
	}
	
	/*
	Show form edit tags
	*/
	function tags_edit($id)
	{
		// Title_page
		$this->data['title_page'] = 'Edit Tag';
			
		// Breadcrumb
		$this->data['breadcrumb'] = array(
			'Post' => 'control/post/post_all',
			'Tags' => 'control/post/tags',
			'Edit Tags' => false
		);
			
		// Form type
		$this->data['form_type'] = 'edit';
			
		$this->data['pg_query'] = $this->mdl_taxonomy->get_term_by_id($id);
		$this->template->view('tags_form', $this->data);
	}
	
	/*
	Action add new tags
	*/
	public function tags_save()
	{
		$name = $this->input->post('name');
		$slug = $this->input->post('slug');

		$now = date('Y:m:d H:i:s');
			
		// Before insert new, check is the term is exist
		$check = $this->mdl_taxonomy->is_term_exist('name', strtolower($name));
			
		if ($check == true)
		{
			$this->session->set_flashdata('message', $this->lang->line('jooglo_error_exist'));
			redirect('control/tags_new');	
		}
		else
		{
			$data = array(
				'name' => $name,
				'slug' => $slug
			);
			
			$this->mdl_taxonomy->insert_term($data, 'post_tag');
				
			$this->session->set_flashdata('message', $this->lang->line('jooglo_success_add'));
			redirect('control/post/tags');	
		} 
	}
	
	/*
	Action update tags
	*/
	public function tags_update()
	{
		$name = $this->input->post('name');
		$slug = $this->input->post('slug');
			
		$id = $this->input->post('id');
			
		$data = array(
			'name' => $name,
			'slug' => $slug
		);
				
		$this->mdl_taxonomy->update_term($id, $data);
			
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
		redirect('control/post/tags_edit/'.$id);		 
	}
	
	/*
	Action delete tags
	*/
	public function tags_delete($id)
	{
		$op = $this->mdl_taxonomy->delete_term($id);
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_delete'));
		redirect ('control/post/tags');	 
	}
	
	/*
	Action search tags
	*/
	public function search_tags()
	{
		$inp_search = $this->input->post('inp_search');
		
		$this->data['breadcrumb'] = array(
			'Display' => '//',
			'Tags' => false
		);
			
		$data['pg_query'] = $this->mdl_taxonomy->search_terms('post_tag','tbl_terms.name',$inp_search,'');
		$data['total'] = $this->mdl_taxonomy->search_terms('post_tag','tbl_terms.name',$inp_search,'total');
			
		$data['title'] = 'Tags Management';
		$this->load->view('admin/vw_dash_tags', $data);
	}
	/*
	| Close
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT MEDIA
	| ---------------------------------------------------------------
	|
	*/
	public function media()
	{
		// Title_page
		$this->data['title_page'] = 'Media';
			
		// Breadcrumb
		$this->data['breadcrumb']['Media'] = false;

		$this->template->view('media', $this->data);
	}
	/*
	| Close 
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT SETTING
	| ---------------------------------------------------------------
	|
	*/
	public function setting()
	{
		// Title_page
		$this->data['title_page'] = 'Setting';
		
		// Breadcrumb
		$this->data['breadcrumb']['Setting'] = false;
		
		// Get existing theme
		$theme_path = './jooglo/application/themes';
		
		$not_include = array('system', 'admin', '.', '..');
		foreach (scandir($theme_path) as $row => $folder_name)
		{	
			if (!in_array($folder_name, $not_include))
			{
				$themes[] = $folder_name;
			}
		}
		
		$this->data['themes'] = $themes;
		$this->template->view('setting', $this->data);
	}
	
	public function do_update_setting()
	{
		$site_title = $this->input->post('site_title');
		$template = $this->input->post('template');
		$logo_form_admin = $this->input->post('logo_form_admin');
				
		$this->mdl_options->update_options('site_title', $site_title);
		$this->mdl_options->update_options('template', $template);
		$this->mdl_options->update_options('logo_form_admin', $logo_form_admin);
			
		$this->session->set_flashdata('message', $this->lang->line('jooglo_success_update'));
		redirect('cms/admin/setting');
	}
	/*
	| Close 
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| MANAGEMENT DYNAMIC PAGE
	| ---------------------------------------------------------------
	|
	*/
	public function show($name = null, $url = null)
	{	
		if(isset($url))
		{
			$url_embed = $url;
			$this->data['title_page'] = ucfirst($name);
			$this->data['breadcrumb'][ucfirst($name)] = false;
		} 
		else 
		{
			$url_embed = $name;
			$this->data['title_page'] = 'Show Iframe';
			$this->data['breadcrumb']['Show Iframe'] = false;
		}
		
		$this->data['url'] = base64_decode(urldecode($url_embed));
		$this->template->view('iframe', $this->data);
	}
	/*
	| Close
	*/
	
	/*
	|
	| ---------------------------------------------------------------
	| BULK ACTION
	| ---------------------------------------------------------------
	| 
	*/

	public function bulk()
	{
		$action = $this->input->post('action');
		$post = $this->input->post('post_record');
		
		switch ($action) {
			
			// Make post draft
			case 'draft':
				foreach ($post as $value)
				{
					$this->mdl_post->draft_this($value);
				}
				break;
			
			// Make post publish
			case 'publish':
				foreach ($post as $value)
				{
					$this->mdl_post->publish_this($value);
				}
				break;
			
			// Make post trash
			case 'trash':
				foreach ($post as $value)
				{
					$this->mdl_post->trash_this($value);
				}
				break;
			
			// Make post restore from trashed
			case 'restore':
				foreach ($post as $value)
				{
					$this->mdl_post->restore_this($value);
				}
				break;
			
			// Approve comment
			case 'approve-comment':
				foreach ($post as $value)
				{
					$this->mdl_comment->approve($value);
				}
				break;
			
			// Unapprove comment
			case 'unapprove-comment':
				foreach ($post as $value)
				{
					$this->mdl_comment->unapprove($value);
				}
				break;
			
			// Activate users
			case 'activate':
				foreach ($post as $value)
				{
					$this->mdl_user->activate_user($value);
				}
				break;
			
			// Block users
			case 'block':
				foreach ($post as $value)
				{
					$this->mdl_user->inactive_user($value);
				}
				break;
			
			default:
			
				echo 'Failed';
				exit;
		}
		
		echo 'done';
	}
	/*
	| Close
	*/
	
	/*
	Private function to limits user admin action by role
	*/
	private function permission_action($param)
	{
		// Get current user role
	 	$user_role = $this->session->userdata('role_id');
		
	 	// Param are all role can do the action 
	 	if (in_array($user_role, $param)) 
	 	{
	 		return true;
	 	} 
	 	else 
	 	{
	 		$this->session->set_flashdata('message', $this->lang->line('jooglo_error_forbidden_role'));
	 		redirect('cms/admin/index');
	 		exit;
	 	}
	}	
	/*
	| Close
	*/
}