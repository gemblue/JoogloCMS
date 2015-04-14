<?php
class Mdl_post extends CI_Model
{
	var $posts = 'jooglo_posts';
	var $post_meta = 'jooglo_postmeta';
	var $term_relationships = 'jooglo_term_relationships';
	var $term_taxonomy = 'jooglo_term_taxonomy';
	var $terms = 'jooglo_terms';
	var $users = 'jooglo_users';
	
	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('post_filter');
		$this->load->helper('slug');
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| GET / RETRIEVE
	| ---------------------------------------------------------------
	|
	*/
	function get_random_post($limit)
	{
		/*
		model to get random post
		*/
		
		$sql = "SELECT ID FROM $this->posts, (SELECT ID AS sid FROM $this->posts ORDER BY RAND( ) LIMIT $limit) tmp 
		        WHERE $this->posts.ID = tmp.sid 
				AND $this->posts.post_type != 'attachment' 
				AND $this->posts.post_type != 'acf'
				AND $this->posts.post_type != 'nav_menu_item' 
				AND $this->posts.post_type != 'page' 
				AND $this->posts.post_type != 'revision' 
				ORDER BY $this->posts.ID";
				
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_related_post($object, $limit)
	{
		/*
		model to get related post
		*/
		
		$this->db->select($this->posts.'.post_title,'.$this->posts.'.post_slug');
		$this->db->from($this->posts);
		
		# join
		$this->db->join($this->term_relationships, $this->term_relationships.'.object_id'.'='.$this->posts.'.id');
		$this->db->join($this->term_taxonomy , $this->term_taxonomy.'.term_taxonomy_id'.'='.$this->term_relationships.'.term_taxonomy_id');
		$this->db->join($this->terms, $this->terms.'.term_id'.'='.$this->term_taxonomy .'.term_id');
		
		# if tags is not empty return by related tags
		if (!empty($object))
		{
			$this->db->where($this->terms.'.name', $object);
		}
		
		$this->db->like($this->term_taxonomy.'.taxonomy', 'category');
		$this->db->where($this->posts.'.post_status', 'publish');
				
		# limitation
		if (!empty($limit))
		{
			$this->db->limit($limit);
		}
		
		# order
		$this->db->order_by($this->posts.'.post_date_gmt','desc');
		
		return $this->db->get()->result();
	}
	
	function get_post_by_term($term_slug, $result = 'array', $status, $term_type, $limit = null, $limit_order = null)
	{
		if ($result == 'total')
		{
			$this->db->select($this->posts.'.id as ID');
		}
		else
		{
			$this->db->select($this->posts.'.id as ID,'.$this->posts.'.post_slug,'.$this->posts.'.post_title,'.$this->users.'.id');
		}

		$this->db->from($this->posts);
		
		$this->db->join($this->term_relationships, $this->term_relationships.'.object_id'.'='.$this->posts.'.id');
		$this->db->join($this->users, $this->users.'.id'.'='.$this->posts.'.post_author');
		$this->db->join($this->term_taxonomy, $this->term_taxonomy.'.term_taxonomy_id'.'='.$this->term_relationships.'.term_taxonomy_id');
		$this->db->join($this->terms, $this->terms.'.term_id'.'='.$this->term_taxonomy.'.term_id');
		
		$this->db->where($this->terms.'.slug', $term_slug);
		$this->db->where($this->posts.'.post_status', $status);
		$this->db->where($this->term_taxonomy.'.taxonomy', $term_type);
		
		$this->db->order_by($this->posts.'.id', 'desc');
		
		if ($limit != null)
		{
			$this->db->limit($limit, $limit_order);
		}
		
		if ($result == 'total')
		{
			return $this->db->get()->num_rows();
		}
		else
		{
			return $this->db->get()->result();
		}
	}
	
	function get_post($post_type, $result, $post_status, $limit = 10, $limit_order = 0, $by = null, $post_parent = null, $jooglo_paging = null)
	{
		/*
		model to get page/post draft/publish/trash loop!
		*/
		
		if ($result == 'total') {
			$this->db->select($this->posts.'.id');
		} else {
			$this->db->select($this->posts.'.post_date,'.$this->posts.'.post_type,'.$this->posts.'.post_status,'.$this->posts.'.post_date_gmt,'.$this->posts.'.post_slug,'.$this->posts.'.post_author,'.$this->posts.'.id as ID,'.$this->posts.'.post_title');
		}
		
		$this->db->from($this->posts);
		$this->db->join($this->users, $this->users.'.id'.'='.$this->posts.'.post_author');
		
		# filter by username
		if (!empty($by))
		{
			$this->db->where($this->posts.'.post_author', $by);
		}
		
		# filter parent post
		if (!empty($post_parent))
		{
			$this->db->where($this->posts.'.post_parent', $post_parent);
		}
		else
		{
			$this->db->where($this->posts.'.post_parent', 0);
		}
		
		# control post type result
		if (!empty($post_type))
		{
			if ($post_status == 'all')
			{
				$this->db->where($this->posts.'.post_status', 'publish');
				$this->db->where($this->posts.'.post_type', $post_type);
				$this->db->or_where($this->posts.'.post_status', 'draft');
				$this->db->where($this->posts.'.post_type', $post_type);
			} 
			else 
			{
				$this->db->where($this->posts.'.post_status', $post_status);
				$this->db->where($this->posts.'.post_type', $post_type);
			}
		}
		else
		{
			if ($post_status == 'all') 
			{
				$this->db->where($this->posts.'.post_status', 'publish');
				$this->db->or_where($this->posts.'.post_status', 'draft');
			} 
			else 
			{
				$this->db->where($this->posts.'.post_status', $post_status);
			}
		}
	
		if ($result == 'total') 
		{
			return $this->db->get()->num_rows();
		} 
		else 
		{
			# override when jooglo paging on. 
			if ($jooglo_paging == 'jooglo_paging_on')
			{
				if($limit_order == 0 || $limit_order == 1)
				{
					$page_post = 0;
				} 
				else 
				{
					$page_post = ($limit_order - 1) * $limit;
				}
				
				$this->db->limit($limit, $page_post);
			}
			else
			{
				$this->db->limit($limit, $limit_order);
			}
			
			$this->db->order_by('post_date','desc');
			return $this->db->get()->result();
		}
	}
	
	function get_trashed_post($result = 'array', $post_type = null, $limit = 10, $limit_order = 0)
	{
		/*
		model to get trashed page/post
		*/
		
		if ($result == 'total') {
			$this->db->select($this->posts.'.id');
		} else {
			$this->db->select($this->posts.'.post_date,'.$this->posts.'.post_type,'.$this->posts.'.post_status,'.$this->posts.'.post_date_gmt,'.$this->posts.'.post_slug,'.$this->posts.'.post_author,'.$this->posts.'.id as ID,'.$this->posts.'.post_title');
		}
		
		$this->db->from($this->posts);
		
		# control post type result
		if (!empty($post_type))
		{
			$this->db->where($this->posts.'.post_type', $post_type);
		}
		
		$this->db->where($this->posts.'.post_status', 'trash');
	
		if ($result == 'total') 
		{
			return $this->db->get()->num_rows();
		} 
		else 
		{
			$this->db->limit($limit, $limit_order);
			$this->db->order_by('post_date','desc');
			return $this->db->get()->result();
		}
	}
	
	function get_single_post($status, $by_field, $by_field_value)
	{
		/*
		get single post by any field
		*/
		$this->db->select('*');
		$this->db->from($this->posts);
		$this->db->join($this->users, $this->users.'.id'.'='.$this->posts.'.post_author');
		
		// By any field. Can be by slug or id.
		$this->db->where($this->posts.'.'.$by_field, $by_field_value);
		
		if ((isset($status)) && (!empty($status)))
		{
			$this->db->where($this->posts.'.post_status', $status);
		}
		
		return $this->db->get()->result();
	}
	
	function get_post_slug($post_id)
	{
		/*
		model to get slug by id
		*/
		$sql = "SELECT post_slug FROM $this->posts WHERE id = '$post_id'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->post_slug;
		}
		
		return $result;
	}
	
	function get_post_title($by_field, $field_value)
	{
		/*
		model to get post title by field
		*/
		$sql = "SELECT post_title FROM $this->posts WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		foreach ($data as $row)
		{
			$data = $row->post_title;
		}
		
		return $data;
	}

	function get_post_type($by_field,$field_value)
	{
		/*
		model to get post type by field
		*/
		$sql = "SELECT post_type FROM $this->posts WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		foreach ($data as $row)
		{
			$data = $row->post_type;
		}
		
		return $data;
	}
	
	function get_post_author($by_field, $field_value)
	{
		/*
		model to get post author by field
		*/
		$sql = "SELECT post_author FROM $this->posts WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->post_author;
		}
		
		return $result;
	}
	
	function get_post_date($by_field, $field_value)
	{
		/*
		model to get post date by field
		*/
		$sql = "SELECT post_date FROM $this->posts WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->post_date;
		}
		
		return $result;
	}
	
	function get_all_post_type()
	{
		/*
		model to get all post type that exist in cms
		*/
		$sql = "SELECT post_type FROM $this->posts WHERE 1 GROUP BY post_type";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_post_id($slug)
	{
		$this->db->select('id');
		$this->db->from($this->posts);
		$this->db->where($this->posts.'.post_slug', $slug);
		$data = $this->db->get()->result();
		
		foreach ($data as $row)
		{
			$post_id = $row->id;
		}
		
		return $post_id;
	}
	
	function get_post_status($id)
	{
		$this->db->select('post_status');
		$this->db->from($this->posts);
		$this->db->where($this->posts.'.id', $id);
		$data = $this->db->get()->result();
		
		$post_status = null;
		
		foreach ($data as $row)
		{
			$post_status = $row->post_status;
		}
		
		return $post_status;
	}
	
	function get_field_value($field, $by_field, $by_field_value)
	{
		/*
		model to get field value by any field
		*/
		
		$this->db->select($this->posts.'.'.$field.' AS result');
		$this->db->from($this->posts);
		$this->db->where($this->posts.'.'.$by_field, $by_field_value);
		$data = $this->db->get()->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->result;
		}
		
		return $result;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CREATE
	| ---------------------------------------------------------------
	|
	*/
	function insert_post($param)
	{
		/*
		model to post new 
		first : we have to create post and connect to category
		second : connect the post to tags
		
		post type is post that have different type, 
		we can use post type for deep post categorizing
		every post type have they own categorize
		*/

		# insert new post into database
		$first_process = false;
		
		$sql = "INSERT INTO $this->posts (post_title, post_date, post_date_gmt, post_content, post_author, post_status, post_slug, post_type) VALUES
			   ('$param[post_title]','$param[post_date]','$param[post_date]','$param[post_content]','$param[post_author]','$param[post_status]','$param[slug]','$param[post_type]')";
		
		$query = $this->db->query($sql);
		
		# yes, now we have to know the post id
		$post_id = $this->mdl_post->get_post_id($param['slug']);
		
		# then, we have to know the term taxonomy id from the choosed category	
		$term_taxonomy_id = $this->mdl_taxonomy->get_term_taxonomy_id($param['cat_id'], $param['post_type']);
		
		# first process end, insert new relationship between post and category
		$this->mdl_taxonomy->insert_relation($term_taxonomy_id, $post_id);
		
		$first_process = true;
		
		if ($first_process == true)
		{
			# tags process	
			$this->load->helper('slug');
			$tags = $param['tags'];
			$tags = str_replace(',   ', ',', $tags);
			$tags = str_replace(',  ', ',', $tags);
			$tags = str_replace(', ', ',', $tags);
			
			$tags_array = explode(',', $tags);
			
			# check existing tag, if its not exist add new
			foreach ($tags_array as $row)
			{
				$check = $this->mdl_taxonomy->is_term_exist('name', strtolower($row));
				if ($check == true)
				{
					# tags is exist bro
				}
				else
				{
					# tags is not exist, insert this into term
					$param = array (
								'name' => $row,
								'slug' => get_slug($row)
							);
					
					$this->mdl_taxonomy->insert_term($param, 'post_tag');
				}
			}
			
			# get the tags term taxonomy id by name
			$tag_term_tax_id = null;
			foreach ($tags_array as $row)
			{
				$op = $this->mdl_taxonomy->get_field_value($this->term_taxonomy.'.term_taxonomy_id', $this->terms.'.name', $row);
				foreach ($op as $row)
				{
					$tag_term_tax_id[] = $row->term_taxonomy_id;
				}
			}
			
			# now, we connect the tag to the post, creating a relation
			if ($tag_term_tax_id != null)
			{
				if ($tag_term_tax_id != '')
				{
					foreach ($tag_term_tax_id as $row)
					{
						$check = $this->mdl_taxonomy->is_relation_exist($row, $post_id);
						if ($check == true)
						{
							# relation is exist, do nothing
						}
						else
						{
							# relation is not exist, add relation to the post
							$this->mdl_taxonomy->insert_relation($row, $post_id);
						}
					}
				}
			}
			
			return true;
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| GET / RETRIEVE SEARCH
	| ---------------------------------------------------------------
	|
	*/
	function search_post($title, $result, $post_type, $status, $limit = null, $limit_order = null)
	{
		/*
		model to search the post by filter
		*/
		
		if ($result == 'total') {
			$this->db->select($this->posts.'.id');
		} else {
			$this->db->select($this->posts.'.post_author,'.$this->posts.'.post_type,'.$this->posts.'.post_status,'.$this->posts.'.post_title,'.$this->posts.'.post_slug,'.$this->posts.'.post_date_gmt,'.$this->posts.'.ID');
		}
		
		$this->db->from($this->posts);
		
		if (!empty($post_type))
		{
			$this->db->where($this->posts.'.post_type', $post_type);
		}
		
		# status control
		if ($status == 'all')
		{
			
		}
		else 
		{
			$this->db->where($this->posts.'.post_status', $status);
		}
		
		$this->db->like($this->posts.'.post_title', $title);
	
		if ($limit != null)
		{
			$this->db->limit($limit, $limit_order);
		}
		
		$this->db->order_by('post_date', 'desc');
		
		if ($result == 'total') {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| UPDATE STATUS FIELD, 0/1 true/FALSE ON/OFF YES/NO
	| ---------------------------------------------------------------
	|
	*/
	
	function enable_comm($id)
	{
		/*
		model enable comment at post
		*/
		$sql = "UPDATE $this->posts SET comment_status = 'open' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function disable_comm($id)
	{
		/*
		model disable comment at post
		*/
		$sql = "UPDATE $this->posts SET comment_status = 'closed' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function draft_this($id)
	{
		/*
		model to draft the post
		*/
		$sql = "UPDATE $this->posts SET post_status = 'draft' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function publish_this($id)
	{
		/*
		model to publish the post
		*/
		$sql = "UPDATE $this->posts SET post_status = 'publish' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function trash_this($id)
	{
		/*
		model to trash the post
		*/
		$sql = "UPDATE $this->posts SET post_status = 'trash' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function restore_this($id)
	{
		/*
		model to restore the post
		*/
		$sql = "UPDATE $this->posts SET post_status = 'draft' WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| UPDATE
	| ---------------------------------------------------------------
	|
	*/
	
	function update_view($post_id)
	{
		/*
		model to update page views
		*/
		
		$sql = "UPDATE $this->posts SET view = view + 1 WHERE ID = '$post_id'";
		$query = $this->db->query($sql);
		return true;
	}
		
	function update_post($param)
	{
		/*
		model to update post
		first process : control category relation with post
		second process : control post tag relation with post
		second process : update post master
		*/
		
		$first_process = false;
		
		# get category term taxonomy id
		$cat_term_tax_id = $this->mdl_taxonomy->get_term_taxonomy_id($param['cat_id'], $param['post_type']);
		
		# check is there relation between category and post before ?
		$check = $this->mdl_taxonomy->is_relation_exist($cat_term_tax_id, $param['post_id']);
		if ($check == true)
		{
			# there is a relation, nothing category change
			$first_process = true;
		}
		else
		{
			# reset/delete all post category relation
			$this->mdl_taxonomy->delete_relation($param['post_id'], 'category');
			
			# make new relation between cat and post
			$this->mdl_taxonomy->insert_relation($cat_term_tax_id, $param['post_id']);
			$first_process = true;
		}
		
		if ($first_process == true)
		{
			# tags process
			$second_process = false;
			
			$tags = $param['tags'];
			$tags = str_replace(',   ', ',', $tags);
			$tags = str_replace(',  ', ',', $tags);
			$tags = str_replace(', ', ',', $tags);
			
			$tags_array = explode(',', $tags);
			
			# reset/delete all post category relation
			$this->mdl_taxonomy->delete_relation($param['post_id'], 'post_tag');
			
			# check existing tag, if its not exist add new
			foreach ($tags_array as $row)
			{
				$check = $this->mdl_taxonomy->is_term_exist('name', strtolower($row));
				if ($check == true)
				{
					# tags is exist bro
				}
				else
				{
					# tags is not exist, insert this into term
					$data = array (
								'name' => $row,
								'slug' => get_slug($row)
							);
					
					$this->mdl_taxonomy->insert_term($data, 'post_tag');
				}
			}
			
			# get the tags term taxonomy id by name
			$tag_term_tax_id = null;
			foreach ($tags_array as $row)
			{
				$op = $this->mdl_taxonomy->get_field_value($this->term_taxonomy.'.term_taxonomy_id', $this->terms.'.name', $row);
				foreach ($op as $row)
				{
					$tag_term_tax_id[] = $row->term_taxonomy_id;
				}
			}
			
			# now, we connect the tag to the post, creating a relation
			if ($tag_term_tax_id != null)
			{
				foreach ($tag_term_tax_id as $row)
				{
					$check = $this->mdl_taxonomy->is_relation_exist($row, $param['post_id']);
					if ($check == true)
					{
						# relation is exist, do nothing
					}
					else
					{
						# relation is not exist, add relation to the post
						$this->mdl_taxonomy->insert_relation($row, $param['post_id']);
					}
				}
			}
			
			$second_process = true;
		}
		
		if ($second_process == true)
		{
			# update post master
			$sql = "UPDATE $this->posts SET post_slug = '$param[slug]', post_date = '$param[post_date]', post_date_gmt = '$param[post_date]', post_title = '$param[post_title]', post_content = '$param[post_content]' WHERE id = '$param[post_id]'";
			$query = $this->db->query($sql);
			return true;
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| DELETE
	| ---------------------------------------------------------------
	|
	*/
	function delete_post($id)
	{
		/*
		model to delete permanent the post with all the relationships 
		# delete relation to the category and tags
		$op = $this->mdl_taxonomy->delete_relation($id, 'all');
		
		if ($op == true)
		{
			# delete master post
			$sql = "DELETE FROM $this->posts WHERE id = '$id'";
			$this->db->query($sql);
	
			# delete post meta
			$sql = "DELETE FROM $this->post_meta WHERE post_id = '$id'";
			$this->db->query($sql);
			
			return true;
		}
		*/
		
		# change post status become deleted to keep data
		$now = date('Y-m-d H:i:s');
		$sql = "UPDATE $this->posts SET post_status = 'deleted', post_slug = '$now|deleted' WHERE id = '$id'";
		$this->db->query($sql);
		
		return true;
	}
	
	function delete_preview()
	{
		$sql = "DELETE FROM $this->posts WHERE post_status = 'preview'";
		$this->db->query($sql);
	
		$sql = "DELETE FROM $this->post_meta WHERE status = 'preview'";
		$this->db->query($sql);
			
		return true;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CHECKER MODEL RETURN TRUE/FALSE IS_THIS
	| ---------------------------------------------------------------
	|
	*/
	function is_this_page($slug)
	{
		/*
		model to check is post is page by slug
		*/
		$sql = "SELECT post_type FROM $this->posts WHERE post_slug = '$slug'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		foreach ($data as $row)
		{
			$is_page = $row->post_type;
		}
		
		if ($is_page == 'page')
		{
			return true;
		}
	}
	
	function is_slug_exist($slug)
	{
		/*
		model to check existing slug
		true : exist
		false : not exist
		*/
		$sql = "SELECT post_slug FROM $this->posts WHERE post_slug = '$slug'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| METADATA CRUD
	| ---------------------------------------------------------------
	|
	*/
	function get_meta_information($post_type)
	{
		/*
		model to get meta information by the post type
		this function returns array meta information that used by the post type
		*/
		$sql = "SELECT ID FROM $this->posts WHERE post_type = '$post_type' ORDER BY ID DESC LIMIT 1";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		if (empty($data))
		{
			return null;
		}
		else
		{
			$id = null;
			
			foreach ($data as $row)
			{
				$id = $row->ID;
			}
			
			# if we have got id that related to the post type, now get the all meta key information
			$sql = "SELECT meta_key FROM $this->post_meta WHERE post_id = '$id' GROUP BY meta_key";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			
			return $data;
		}
	}
	
	function get_post_thumbnail($post_id, $size = 'md')
	{
		$sql = "SELECT meta_value FROM $this->post_meta WHERE meta_key = 'featured_image' AND post_id = '$post_id'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = base_url().$row->meta_value;
		}	
		
		if (empty($result) || $result == site_url())
		{
			// Return default
			$result = base_url().'jooglo/uploads/images/default/'.$size.'_default.png';
		}
		
		return $result;
	}
	
	function get_post_meta($post_id, $meta_key)
	{
		/*
		model to get post meta
		*/
	
		$sql = "SELECT meta_value FROM $this->post_meta WHERE meta_key = '$meta_key' AND post_id = '$post_id'";
		$query = $this->db->query($sql);
		$data = $query->result();
			
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->meta_value;
		}	
			
		return $result;
	}
	
	function is_meta_exist($meta_key,$post_id)
	{
		/*
		model to check is meta exist  
		true = exist
		false = not exist
		*/
		$sql = "SELECT meta_id FROM $this->post_meta WHERE meta_key = '$meta_key' AND post_id = '$post_id'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
	
	function insert_meta($post_id, $meta_key, $meta_value, $status)
	{
		/*
		model to insert meta
		*/
		$sql = "INSERT INTO $this->post_meta (post_id, meta_key, meta_value, status) VALUES ('$post_id', '$meta_key', '$meta_value', '$status')";
		$query = $this->db->query($sql);
		return true;
	}
	
	function update_meta($meta_key, $meta_value, $post_id, $status = null)
	{
		/*
		model to update meta
		*/
		
		# before edit the meta, check is meta exist ? if no insert new with null
		$check = $this->mdl_post->is_meta_exist($meta_key, $post_id);
		
		if ($check == true)
		{
			$sql = "UPDATE $this->post_meta SET meta_value = '$meta_value', status = '$status' WHERE meta_key = '$meta_key' AND post_id = '$post_id'";
			$query = $this->db->query($sql);
			return true;
		}
		else
		{
			# create meta
			$this->mdl_post->insert_meta($post_id, $meta_key, $meta_value, $status);
			return true;
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| ADDITIONAL / STATISTIC
	| ---------------------------------------------------------------
	|
	*/
	
	/*
	Get most views page/post
	*/
	function get_most_view_post($post_type, $result, $post_status, $limit = 10, $limit_order = 0, $jooglo_paging = null)
	{
		/*
		model to get most views post
		*/
		
		if ($result == 'total') {
			$this->db->select($this->posts.'.id');
		} else {
			$this->db->select($this->posts.'.post_date,'.$this->posts.'.post_type,'.$this->posts.'.post_status,'.$this->posts.'.post_date_gmt,'.$this->posts.'.post_slug,'.$this->posts.'.post_author,'.$this->posts.'.id as ID,'.$this->posts.'.post_title');
		}
		
		$this->db->from($this->posts);
		$this->db->join($this->users, $this->users.'.id'.'='.$this->posts.'.post_author');
		
		# control post type result
		if (!empty($post_type))
		{
			if ($post_status == 'all')
			{
				$this->db->where($this->posts.'.post_status', 'publish');
				$this->db->where($this->posts.'.post_type', $post_type);
				$this->db->or_where($this->posts.'.post_status', 'draft');
				$this->db->where($this->posts.'.post_type', $post_type);
			} 
			else 
			{
				$this->db->where($this->posts.'.post_status', $post_status);
				$this->db->where($this->posts.'.post_type', $post_type);
			}
		}
		else
		{
			if ($post_status == 'all') 
			{
				$this->db->where($this->posts.'.post_status', 'publish');
				$this->db->or_where($this->posts.'.post_status', 'draft');
			} 
			else 
			{
				$this->db->where($this->posts.'.post_status', $post_status);
			}
		}
	
		if ($result == 'total') 
		{
			return $this->db->get()->num_rows();
		} 
		else 
		{
			# override when jooglo paging on. 
			if ($jooglo_paging == 'jooglo_paging_on')
			{
				if($limit_order == 0 || $limit_order == 1)
				{
					$page_post = 0;
				} 
				else 
				{
					$page_post = ($limit_order - 1) * $limit;
				}
				
				$this->db->limit($limit, $page_post);
			}
			else
			{
				$this->db->limit($limit, $limit_order);
			}
			
			$this->db->order_by('view', 'desc');
			return $this->db->get()->result();
		}
	}
}
?>