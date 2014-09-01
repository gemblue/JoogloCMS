<?php
class Mdl_role extends CI_Model
{
	var $role = 'jooglo_roles';
	var $role_relations = 'jooglo_role_user';
	
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
	
	function get_list_role()
	{
		/*
		model to get existing role
		*/
		$sql = "SELECT * FROM $this->role";
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_user_role($id_user)
	{
		/*
		model to get the user role
		result : return id role
		*/
		$sql = "SELECT role_id FROM $this->role_relations WHERE user_id = '$id_user'";
		$query = $this->db->query($sql);
		$data = $query->result();
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->role_id;
		}
		
		return $result;
	}
	
	function get_role_name($id_role)
	{
		/*
		model to get role name by id
		*/
		$sql = "SELECT name FROM $this->role WHERE id = '$id_role'";
		$query = $this->db->query($sql);
		$data =  $query->result();
		
		$name = false;
		foreach ($data as $row)
		{
			$name = $row->name;
		}
		
		return $name;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CREATE
	| ---------------------------------------------------------------
	|
	*/
	function insert_user_role($id_user,$role_id)
	{
		/*
		model to get save new role user
		true : success
		false : failed, the user have been roled
		*/
		
		# first check if he have role
		$check = $this->mdl_role->is_have_role($id_user);
		
		if ($check == true) 
		{
			return false;
		} 
		else 
		{
			$sql = "INSERT INTO $this->role_relations (role_id,user_id) VALUES ('$role_id','$id_user')";
			$query = $this->db->query($sql);
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
	function update_user_role($id_user, $role_id)
	{
		/*
		model to get update user role
		true : success
		false : failed
		*/
		
		$sql = "UPDATE $this->role_relations SET role_id = '$role_id' WHERE user_id = '$id_user' ";
		$query = $this->db->query($sql);
		return true;
			
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CHECKER MODEL RETURN TRUE/FALSE IS_THIS
	| ---------------------------------------------------------------
	|
	*/
	
	function is_have_role($id_user)
	{
		/*
		model to check is user have the role
		true : exist
		false : not exist
		*/
		$sql = "SELECT role_id FROM $this->role_relations WHERE user_id = '$id_user'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
}
?>