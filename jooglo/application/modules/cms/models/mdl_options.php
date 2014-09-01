<?php
class Mdl_options extends CI_Model
{
	var $options = 'jooglo_options';
	
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
	
	function get_options($name)
	{
		/*
		model to get option value by name
		*/
		$sql = "SELECT value FROM $this->options WHERE name = '$name'";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$options = null;
		
		foreach ($data as $row)
		{
			$options = $row->value;
		}
		
		return $options;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| UPDATE
	| ---------------------------------------------------------------
	|
	*/
	
	function update_options($name, $value)
	{
		/*
		model to update option
		*/								
		
		# before update, check id field exist				
		$sql = "SELECT id FROM $this->options WHERE name = '$name' ";		
		$query = $this->db->query($sql);				
		$total = $query->num_rows();	
		
		if ($total > 0)				
		{						
			# option is exist					
			$sql = "UPDATE $this->options SET value = '$value' WHERE name = '$name'";		
			$query = $this->db->query($sql);					
			
			return true;		
		}				
		else	
		{			
			# insert new			
			$sql = "INSERT INTO $this->options (name,value) VALUES ('$name','$value') ";	
			$query = $this->db->query($sql);						
			
			return true;		
		}	
	}
}
?>