<?php
class Star_m extends CI_Model
{
	var $star = 'module_star';
	var $star_accumulative = 'module_star_accumulative';
	var $posts = 'jooglo_posts';

	function __construct()
	{
		parent::__construct();
	}
	
	// Get most starred object
	function get_most_starred($post_type, $result, $post_status, $limit = 10, $limit_order = 0, $jooglo_paging = null)
	{
		if ($result == 'total')
		{
			$this->db->select($this->star_accumulative.'.object_id');
		}
		else
		{
			$this->db->select($this->posts.'.post_date,'.$this->posts.'.post_type,'.$this->posts.'.post_status,'.$this->posts.'.post_date_gmt,'.$this->posts.'.post_slug,'.$this->posts.'.post_author,'.$this->posts.'.id as ID,'.$this->posts.'.post_title');
		}
		
		$this->db->from($this->star_accumulative);
		$this->db->join($this->posts, $this->posts.'.id'.'='.$this->star_accumulative.'.object_id');
		
		$this->db->where($this->posts.'.post_status', $post_status);
		$this->db->where($this->posts.'.post_type', $post_type);
		
		$this->db->order_by($this->star_accumulative.'.star', 'desc');
		
		if ($limit != null)
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
		}
		
		if ($result == 'total')
		{
			$result = $this->db->get()->num_rows();
		}
		else
		{
			$result = $this->db->get()->result();
		}
		
		return $result;
	}
	
	// Delete star
	function delete($user_id, $object_id)
	{
		$check = $this->is_starred($user_id, $object_id);
	
		if ($check == true)
		{
			$sql = "DELETE FROM $this->star WHERE user_id = '$user_id' AND object_id = '$object_id'";
			$this->db->query($sql);
			
			// Down accumulative
			$this->down_accumulative($object_id);
				
			return $this->get_accumulative($object_id);
		}
		else
		{
			return false;
		}
	}
	
	// Insert star and accumulative
	function insert($param)
	{
		$check = $this->is_starred($param['user_id'], $param['object_id']);
	
		if ($check == true)
		{
			return false;
		}
		else
		{
			if ($this->db->insert($this->star, $param))
			{
				// Up accumulative
				$this->up_accumulative($param['object_id']);
				
				return $this->get_accumulative($param['object_id']);
			}
			else
			{
				return false;
			}
		}
	}
	
	// Get total/accumulative star
	function get_accumulative($object_id)
	{
		$this->db->select($this->star_accumulative.'.star');
		$this->db->from($this->star_accumulative);
		$this->db->where($this->star_accumulative.'.object_id', $object_id);
		$result = $this->db->get()->result();
		
		foreach ($result as $row)
		{
			return $row->star;
		}
	}
	
	// Down accumulative star
	function down_accumulative($object_id)
	{
		$sql = "UPDATE $this->star_accumulative SET star = star - 1 WHERE object_id = '$object_id'";
		$this->db->query($sql);
		
		return true;
	}
	
	// Up accumulative star
	function up_accumulative($object_id)
	{
		$check = $this->is_accumulative_exist($object_id);
		
		if ($check == false)
		{
			$sql = "INSERT INTO $this->star_accumulative (star, object_id) VALUES ('1', '$object_id')";
			$this->db->query($sql);
		}
		else
		{
			$sql = "UPDATE $this->star_accumulative SET star = star + 1 WHERE object_id = '$object_id'";
			$this->db->query($sql);
		}
		
		return true;
	}
	
	// Is starred?
	function is_starred($user_id, $object_id)
	{
		$this->db->select($this->star.'.id');
		$this->db->from($this->star);
		$this->db->where($this->star.'.object_id', $object_id);
		$this->db->where($this->star.'.user_id', $user_id);
		$result = $this->db->get()->num_rows();
		
		if ($result > 0) return true;
	}
	
	// Is accumulative record exist?
	function is_accumulative_exist($object_id)
	{
		$this->db->select($this->star_accumulative.'.id');
		$this->db->from($this->star_accumulative);
		$this->db->where($this->star_accumulative.'.object_id', $object_id);
		$result = $this->db->get()->num_rows();
		
		if ($result > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>