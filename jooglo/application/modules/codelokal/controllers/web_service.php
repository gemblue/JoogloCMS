<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This is the controller that handle codelokal action post or request
 
class Web_service extends MY_Controller 
{
	public function __construct()
	{		
		parent::__construct();
		
		$this->load->helper('slug');
		
		// Load Jooglo Library
		$this->load->library('cms/jooglo');
	}
	
	// Save suggest
	public function save_suggest()
	{
		$suggestion = $this->input->post('suggestion');
		
		$result = array (
			'status' => 'success',
			'message' => 'Terimakasih banyak atas masukannya.'
		);
			
		echo json_encode($result);
	}
	
	// Save product
	public function save_product()
	{
		// Get and sanitize post
		$product_name = $this->input->post('product_name');
		$categories = $this->input->post('categories');
		$developer = $this->input->post('developer');
		$email = $this->input->post('email');
		$website = $this->input->post('website');
		$contact = $this->input->post('contact');
		$latest_version = $this->input->post('latest_version');
		$description = $this->input->post('description');
		$tags = $this->input->post('tags');
		$slug = get_slug($product_name);
		
		// Logged in is a must
		if ($this->data['logged_in'] != true)
		{
			$result = array (
				'status' => 'error_login',
				'message' => 'Login required.'
			);
			
			echo json_encode($result);
			exit;
		}
		
		// Slug check
		$check = $this->mdl_post->is_slug_exist($slug);
		if ($check ==  true)
		{
			$result = array (
				'status' => 'error',
				'message' => 'Nama product "'.$product_name.'" sudah ada yang menggunakan. Coba dengan nama lain atau tambahkan prefix untuk sedikit membedakan
							  nama satu sama lain.'
			);
				
			echo json_encode($result);
			exit;
		}
		
		// Validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('product_name', 'Product name', 'required');
		$this->form_validation->set_rules('categories', 'Categories', 'required');
		$this->form_validation->set_rules('developer', 'Developer', 'required');
		$this->form_validation->set_rules('website', 'Website', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('contact', 'Contact', 'required');
		$this->form_validation->set_rules('latest_version', 'Latest version', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('tags', 'Tags', 'required');
		
		if ($this->form_validation->run() == false)
		{
			$result = array (
				'status' => 'error',
				'message' => validation_errors()
			);
			
			echo json_encode($result);
			exit;
		}
		
		// Insert custom post type - kode
		$param = array (
			'cat_id' => $categories,
			'post_status' => 'draft',
			'post_type' => 'product',
			'post_title' => $product_name,
			'post_date' => date('Y-m-d H:i:s'),
			'post_content' => $description,
			'post_author' => $this->session->userdata('id'),
			'slug' => $slug,
			'tags' => $tags,
			'metadesc' => $description,
			'metakey' => $tags
		);
		
		if($this->mdl_post->insert_post($param))
		{
			$post_id = $this->mdl_post->get_post_id($slug);
			
			// Insert meta
			$this->mdl_post->insert_meta($post_id, 'latest_version', $latest_version, 'publish');
			$this->mdl_post->insert_meta($post_id, 'contact', $contact, 'publish');
			$this->mdl_post->insert_meta($post_id, 'developer', $developer, 'publish');
			$this->mdl_post->insert_meta($post_id, 'website', $website, 'publish');
			$this->mdl_post->insert_meta($post_id, 'email', $email, 'publish');
			
			$result = array (
				'status' => 'success',
				'message' => $slug
			);
			
			echo json_encode($result);
			exit;
		}
		else
		{
			$result = array (
				'status' => 'error',
				'message' => 'Unknown error'
			);
			
			echo json_encode($result);
			exit;
		}
	}
	
	// Update product
	public function update_product()
	{
		// Get and sanitize post
		$product_name = $this->input->post('product_name');
		$post_id = $this->input->post('post_id');
		$post_date = $this->input->post('post_date');
		$categories = $this->input->post('categories');
		$developer = $this->input->post('developer');
		$website = $this->input->post('website');
		$contact = $this->input->post('contact');
		$latest_version = $this->input->post('latest_version');
		$description = $this->input->post('description');
		$tags = $this->input->post('tags');
		$slug = get_slug($product_name);
		
		// Logged in is a must
		if ($this->data['logged_in'] != true)
		{
			$result = array (
				'status' => 'error',
				'message' => 'Login required.'
			);
			
			echo json_encode($result);
		}
		
		// Get current slug
		$current_slug = $this->mdl_post->get_post_slug($post_id);
		
		// Slug check
		$check = $this->mdl_post->is_slug_exist($slug);
		if ($check ==  true)
		{
			if ($slug == $current_slug)
			{
				// Do nothing
			}
			else
			{
				$result = array (
					'status' => 'error',
					'message' => 'Nama product sudah ada yang menggunakan. Coba dengan nama lain.'
				);
				
				echo json_encode($result);
				exit;
			}
		}
		
		// Validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('product_name', 'Product name', 'required');
		$this->form_validation->set_rules('categories', 'Categories', 'required');
		$this->form_validation->set_rules('developer', 'Developer', 'required');
		$this->form_validation->set_rules('website', 'Website', 'required');
		$this->form_validation->set_rules('contact', 'Contact', 'required');
		$this->form_validation->set_rules('latest_version', 'Latest version', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('tags', 'Tags', 'required');
		
		if ($this->form_validation->run() == false)
		{
			$result = array (
				'status' => 'error',
				'message' => validation_errors()
			);
			
			echo json_encode($result);
			exit;
		}
		
		// Check owner
		$post_owner_id = $this->mdl_post->get_post_author('id', $post_id);
		if ($this->data['user_id'] != $post_owner_id)
		{
			$result = array (
				'status' => 'error',
				'message' => 'Access denied'
			);
			
			echo json_encode($result);
		}
		
		$data = array (
			'cat_id' => $categories,
			'post_id' => $post_id,
			'post_type' => 'product',
			'post_title' => $product_name,
			'post_date' => $post_date,
			'post_content' => $description,
			'post_author' => $this->data['user_id'],
			'slug' => $slug,
			'tags' => $tags
		);
				
		if ($this->mdl_post->update_post($data))
		{
			// Update meta
			$this->mdl_post->update_meta('latest_version', $latest_version, $post_id, 'publish');
			$this->mdl_post->update_meta('contact', $contact, $post_id, 'publish');
			$this->mdl_post->update_meta('developer', $developer, $post_id, 'publish');
			$this->mdl_post->update_meta('website', $website, $post_id, 'publish');
			
			$result = array (
				'status' => 'success',
				'message' => $slug
			);
			
			echo json_encode($result);
		}
		else
		{
			$result = array (
				'status' => 'error',
				'message' => 'Unknown error.'
			);
			
			echo json_encode($result);
		}
	}
	
	// Crop picture
	function crop()
	{
		$imgUrl = $_POST['imgUrl'];
		$imgInitW = $_POST['imgInitW'];
		$imgInitH = $_POST['imgInitH'];
		$imgW = $_POST['imgW'];
		$imgH = $_POST['imgH'];
		$imgY1 = $_POST['imgY1'];
		$imgX1 = $_POST['imgX1'];
		$cropW = $_POST['cropW'];
		$cropH = $_POST['cropH'];	

		$jpeg_quality = 100;

		$output_filename = "./jooglo/uploads/product/croppedImg_".rand();

		$what = getimagesize($imgUrl);
		
		switch(strtolower($what['mime']))
		{
			case 'image/png':
				$img_r = imagecreatefrompng($imgUrl);
				$source_image = imagecreatefrompng($imgUrl);
				$type = '.png';
				break;
			case 'image/jpeg':
				$img_r = imagecreatefromjpeg($imgUrl);
				$source_image = imagecreatefromjpeg($imgUrl);
				$type = '.jpeg';
				break;
			case 'image/gif':
				$img_r = imagecreatefromgif($imgUrl);
				$source_image = imagecreatefromgif($imgUrl);
				$type = '.gif';
				break;
			default: die('image type not supported');
		}
			
		$resizedImage = imagecreatetruecolor($imgW, $imgH);
		imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);	
			
		$dest_image = imagecreatetruecolor($cropW, $cropH);
		imagecopyresampled($dest_image, $resizedImage, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);	
		imagejpeg($dest_image, $output_filename.$type, $jpeg_quality);
			
		$response = array(
			"status" => 'success',
			"url" => $output_filename.$type 
		);
		print json_encode($response);
	}
	
	// Upload screenshot
	function upload()
	{
		$imagePath = "./jooglo/uploads/product/";

		$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
		$temp = explode(".", $_FILES["img"]["name"]);
		$extension = end($temp);

		if ( in_array($extension, $allowedExts))
		{
			if ($_FILES["img"]["error"] > 0)
			{
				 $response = array(
					"status" => 'error',
					"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
				);
				echo "Return Code: " . $_FILES["img"]["error"] . "<br>";
			}
			else
			{
				
				$filename = $_FILES["img"]["tmp_name"];
				list($width, $height) = getimagesize( $filename );

				move_uploaded_file($filename,  $imagePath . $_FILES["img"]["name"]);

				$response = array(
					"status" => 'success',
					"url" => $imagePath.$_FILES["img"]["name"],
					"width" => $width,
					"height" => $height
				);
			  
			}
		}
		else
		{
			$response = array(
				"status" => 'error',
				"message" => 'something went wrong',
			);
		}
		  
		print json_encode($response);
	}
	
	// Save product
	public function save_image()
	{
		// Get post data
		$img_url = str_replace('./', '', $this->input->post('img_url'));
		$post_id = $this->input->post('post_id');
		
		// Logged in is a must
		if ($this->data['logged_in'] != true)
		{
			echo 'login_required';
			exit;
		}
		
		// Check if current user is post owner
		$post_author = $this->mdl_post->get_post_author('ID', $post_id);
		
		if ($this->data['user_id'] != $post_author)
		{
			echo 'not_allowed';
			exit;
		}
		
		// Update image
		$this->mdl_post->update_meta('featured_image', $img_url, $post_id, 'publish');
		echo 'success';
	}
}