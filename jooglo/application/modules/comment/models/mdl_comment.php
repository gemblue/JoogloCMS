<?php
class Mdl_comment extends CI_Model
{
	var $comments = 'jooglo_comments';
	var $posts = 'jooglo_posts';
	var $users = 'jooglo_users';
	
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
	function get_comments($result, $status = null, $object_id = null, $object_type = null, $parent = null, $limit = null, $limit_order = null)
	{
		/*
		model to get the comment data with filter
		status : approved/unapproved
		result : array/total
		*/
		
		if ($result == 'total'){
			$this->db->select($this->comments.'.id');
		} else {
			$this->db->select($this->comments.'.id as comment_id,'.$this->comments.'.object_id,'.$this->comments.'.object_type,'.$this->comments.'.comment,'.$this->comments.'.created_at,'.$this->comments.'.author,'.$this->users.'.username,'.$this->comments.'.status');
		}
		
		$this->db->from($this->comments);
		
		// Join with user table
		$this->db->join($this->users, $this->users.'.id'.'='.$this->comments.'.author');
		
		// By detail comment to
		if ($object_id)
		{
			$this->db->where($this->comments.'.object_id', $object_id);
			$this->db->where($this->comments.'.object_type', $object_type);
		}
		
		// Parent
		if (!empty($parent))
		{
			$this->db->where($this->comments.'.parent', $parent);
		}
		else
		{
			$this->db->where($this->comments.'.parent', 0);
		}
		
		// Status
		if (!empty($status))
		{
			$this->db->where($this->comments.'.status', $status);
		}
		
		// Order
		$this->db->order_by($this->comments.'.created_at', 'asc');
	
		// Limit
		if (!empty($limit) && !empty($limit_order))
		{
			$this->db->limit($limit, $limit_order);
		}
		
		// Result
		if ($result == 'total'){
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| UPDATE STATUS FIELD, 0/1 TRUE/FALSE ON/OFF YES/NO
	| ---------------------------------------------------------------
	|
	*/
	function approve($comment_id)
	{
		/*
		model to activate comment by id
		*/
		$sql = "UPDATE $this->comments SET status = 'publish' WHERE id = '$comment_id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	function unapprove($comment_id)
	{
		/*
		model to disactive comment by id
		*/
		$sql = "UPDATE $this->comments SET status = 'draft' WHERE id = '$comment_id'";
		$query = $this->db->query($sql);
		return true;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| GET / RETRIEVE SEARCH
	| ---------------------------------------------------------------
	|
	*/
	function search($result, $status = null, $comment, $object_id = null, $limit = null, $limit_order = null)
	{
		/*
		model to search comment by comment content with filter 
		*/
		
		$this->db->select($this->comments.'.author,'.$this->users.'.username,'.$this->comments.'.status,'.$this->comments.'.id as comment_id,'.$this->comments.'.comment,'.$this->comments.'.object_id,');
		
		$this->db->from($this->comments);
		$this->db->join($this->users, $this->users.'.id'.'='.$this->comments.'.author');
		
		// By Id
		if ($object_id != null)
		{
			$this->db->where($this->comments.'.object_id', $object_id);
		}
		
		// Status
		if ($status != null) 
		{
			$this->db->where($this->comments.'.status', $status);
		}
		
		$this->db->like($this->comments.'.comment', $comment);
		$this->db->order_by($this->comments.'.id','desc');
		
		// Limit
		if ($limit != null)
		{
			$this->db->limit($limit, $limit_order);
		}
		
		if ($result == 'total') {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
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
		model to delete comment
		*/
		$sql = "DELETE FROM $this->comments WHERE id = '$id'";
		$query = $this->db->query($sql);
		return true;	
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CREATE
	| ---------------------------------------------------------------
	|
	*/
	function create($data)
	{
		$this->db->insert($this->comments, $data); 
		return $this->db->insert_id();
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| ADDITIONAL / STATISTIC
	| ---------------------------------------------------------------
	|
	*/
	function get_most_commented($limit)
	{
		/*
		Get most commented post
		*/
		$sql = "SELECT post_id, COUNT(post_id) AS total FROM $this->comments GROUP BY post_id ORDER BY total DESC LIMIT $limit ";
		$query = $this->db->query($sql);
		return $query->result();
	}	
}
?>