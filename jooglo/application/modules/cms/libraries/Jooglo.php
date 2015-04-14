<?php

/*
Library that contain function to use in theming.

- Header snippets (info, meta, etc)
- Snippets for template (get post, paging, featured image etc)
- User status (Login, Logout, Username, Etc)
*/

class Jooglo
{
	var $ci;
	
	// Construct
	function Jooglo()
	{
		// Get Codeigniter Instance
		$this->ci =& get_instance(); 
	}
	
	# Post #
	
	// Get post
	function get_post($post_type, $result, $post_status, $limit = 10, $limit_order = 0, $by = null, $post_parent = null, $jooglo_paging = null)
	{
		return $this->ci->mdl_post->get_post($post_type, $result, $post_status, $limit, $limit_order, $by, $post_parent, $jooglo_paging);
	}
	
	// Get most view post
	function get_most_view_post($post_type, $result, $post_status, $limit = 10, $limit_order = 0, $jooglo_paging = null)
	{
		return $this->ci->mdl_post->get_most_view_post($post_type, $result, $post_status, $limit, $limit_order, $jooglo_paging);
	}
	
	// Get post meta
	function get_post_meta($post_id, $meta_key)
	{
		return $this->ci->mdl_post->get_post_meta($post_id, $meta_key);
	}
	
	// Get post field value
	function get_field_value($field, $by_field, $by_field_value)
	{
		return $this->ci->mdl_post->get_field_value($field, $by_field, $by_field_value);
	}
	
	// Get post thumbnail or featured image
	function get_post_thumbnail($post_id, $size = 'md')
	{
		return $this->ci->mdl_post->get_post_thumbnail($post_id, $size);
	}
	
	// Get categories / tags list
	function get_terms($type, $limit = null, $limit_order = null)
	{
		return $this->ci->mdl_taxonomy->get_terms($type, $limit, $limit_order);
	}
	
	// Get post tags
	function get_post_tags($post_id)
	{
		return $this->ci->mdl_taxonomy->get_post_tags($post_id);
	}
	
	# User #
	
	// Get user meta
	function get_user_meta($user_id, $field_value)
	{
		return $this->ci->mdl_user->get_user_meta($user_id, $field_value);
	}
}
?>