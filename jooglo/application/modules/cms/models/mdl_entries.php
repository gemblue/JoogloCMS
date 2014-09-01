<?php
class Mdl_entries extends CI_Model
{
	var $entries = 'jooglo_entries';
	var $entries_meta = 'jooglo_entrymetas';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| GET / RETRIEVE
	| ---------------------------------------------------------------
	|
	*/
	function get_meta_information($entry_type)
	{
		/*
		model to get meta information by the entry type
		this function returns array metas information that used by the entry type
		*/
		$sql = "SELECT id FROM $this->entries WHERE entry_type = '$entry_type' ORDER BY id DESC LIMIT 1";
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
				$id = $row->id;
			}
			
			# if we have got id that related to the entry type, now get the all meta key information
			$sql = "SELECT meta_key FROM $this->entries_meta WHERE entries_id = '$id' GROUP BY meta_key";
			$query = $this->db->query($sql);
			$data = $query->result_array();
			
			return $data;
		}
	}

	function get_list_entry($entry_type,$limit)
	{
		/*
		model to get the entries data with filter name
		example : get the slider data, get the contact data
		*/
		$this->db->select('*');
		$this->db->from($this->entries);
		$this->db->where($this->entries.'.entry_type',$entry_type);
		$this->db->order_by($this->entries.'.id','desc');
		$this->db->limit($limit);
		return $this->db->get()->result();
	}
		
	function get_entries_id($field_value, $by_field)
	{
		/*
		model to get entries id
		*/
		$sql = "SELECT id FROM $this->entries WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$data =  $query->result();
		
		foreach ($data as $row)
		{
			$id = $row->id;
		}
		
		return $id;
	}
	
	function get_entries_by_id($id)
	{
		/*
		model to get entries data by id
		*/
		$sql = "SELECT * FROM $this->entries WHERE id = '$id'";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_entry_slug($id)
	{
		/*
		model to get the slug
		*/
		$sql = "SELECT slug FROM $this->entries WHERE id = '$id'";
		$query = $this->db->query($sql);
		$data = null;
		$data = $query->result();
		
		foreach ($data as $row)
		{
			return $row->slug;
		}
	}
	
	function get_all_entry_type()
	{
		/*
		model to information about all entry type
		*/
		$sql = "SELECT entry_type FROM $this->entries GROUP BY entry_type";
		$query = $this->db->query($sql);
		return $query->result();
	}
		
	/*
	|
	| ---------------------------------------------------------------
	| CHECKER MODEL RETURN TRUE/FALSE IS_THIS
	| ---------------------------------------------------------------
	|
	*/
	function is_exist_slug($slug)
	{
		/*
		model to check is slug entries exist
		true = exist
		false = not exist
		*/
		$sql = "SELECT id FROM $this->entries WHERE slug = '$slug'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
	
	function is_exist_title($title)
	{
		/*
		model to check is title entries exist
		true = exist
		false = not exist
		*/
		$sql = "SELECT id FROM $this->entries WHERE title = '$title'";
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
	| UPDATE
	| ---------------------------------------------------------------
	|
	*/
	function update_entry_master($field_name, $value, $entries_id)
	{
		/*
		model to update entries master
		*/
		$sql = "UPDATE $this->entries SET $field_name = '$value' WHERE id = '$entries_id'";
		$query = $this->db->query($sql);
		return TRUE;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CREATE
	| ---------------------------------------------------------------
	|
	*/
	function new_entries($data)
	{
		/*
		model to save new entries
		*/
		$this->db->insert($this->entries, $data);
		return true;	
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| METADATA CRUD
	| ---------------------------------------------------------------
	|
	*/
	function update_entry_meta($meta_key, $meta_name, $meta_value, $entries_id)
	{
		/*
		model to update entries meta
		*/
		
		# before edit the meta, check is meta exist ? if no insert new with null
		$check = $this->mdl_entries->is_meta_exist($meta_key, $entries_id);
		
		if ($check == true)
		{
			# meta is exist, update the meta
			$sql = "UPDATE $this->entries_meta SET meta_value_text = '$meta_value' WHERE meta_key = '$meta_key' AND entries_id = '$entries_id'";
			$query = $this->db->query($sql);
			return TRUE;
		}
		else
		{
			# create meta
			$this->mdl_entries->insert_meta($entries_id,$meta_name,$meta_key,$meta_value);
			return true;
		}
	}
	
	private function insert_meta($entries_id,$meta_name,$meta_key,$meta_value)
	{
		/*
		model to insert meta
		*/
		$sql = "INSERT INTO $this->entries_meta (entries_id,meta_name,meta_key,meta_value_text) VALUES ('$entries_id','$meta_name','$meta_key','$meta_value')";
		$query = $this->db->query($sql);
		return true;
	}
	
	function is_meta_exist($meta_key, $entries_id)
	{
		/*
		model to check is meta exist  
		true = exist
		false = not exist
		*/
		$sql = "SELECT id FROM $this->entries_meta WHERE meta_key = '$meta_key' AND entries_id = '$entries_id'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
	
	function get_entry_meta($entries_id, $field_value)
	{
		/*
		model to get entry meta data
		*/
		$meta_value_text = null;
		
		$sql = "SELECT meta_value_text FROM $this->entries_meta WHERE meta_key = '$field_value' AND entries_id = '$entries_id' ";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		foreach ($data as $row)
		{
			$meta_value_text = $row->meta_value_text;
		}
		
		return $meta_value_text;
	}
	
	function delete_meta($meta_key, $entries_id)
	{
		/*
		model to delete entry meta
		*/
		$sql = "DELETE FROM $this->entries_meta WHERE meta_key = '$meta_key' AND entries_id = '$entries_id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| DELETE
	| ---------------------------------------------------------------
	|
	*/
	function delete($id)
	{
		/*
		model to delete entries and all meta
		*/
		$sql = "DELETE FROM $this->entries WHERE id = '$id'";
		$this->db->query($sql);
		$sql = "DELETE FROM $this->entries_meta WHERE entries_id = '$id'";
		$this->db->query($sql);
		return true;
	}
	
}