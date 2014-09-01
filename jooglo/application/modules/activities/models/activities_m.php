<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Activities_m extends CI_Model {

	# setting tabel
	var $activities = 'module_activities';
	
	public function __construct()
	{		
		parent::__construct();
	}
	
	public function get_activities($param)
	{
		/*
		Model to get activities with filter
		
		Param (Array)
		
		result  : the result of model, array or total
		limit, limit order : How much to show
		action  : by action
		user_id : by single user
		*/
		
		$param['limit_order'] = 0;
		
		if ($param['result'] == 'total') {
			$this->db->select($this->activities.'.id');
		} else {
			$this->db->select('*');
		}
		
		$this->db->from($this->activities);
		
		if (!empty($param['action']))
		{
			$this->db->where($this->activities.'.action', $param['action']);
		}
		
		if (!empty($param['user_id']))
		{
			$this->db->where($this->activities.'.user_id', $param['user_id']);
		}
		
		if ($param['limit'])
		{
			$this->db->limit($param['limit'], $param['limit_order']);
		} 
		
		if ($param['result'] == 'total') {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}	
	}
	
	function insert_activities($user_id, $object, $object_id, $action, $log, $campaign)
	{
		/*
		model to insert new activities
		true : success
		false : failed
		*/
		
		$ip = $_SERVER['REMOTE_ADDR'];
		$now = date('Y:m:d H:i:s');
		
		$sql = "INSERT INTO $this->activities (user_id, object, object_id, action, log, ip_address, campaign, created_at) VALUES 
			   ('$user_id','$object','$object_id','$action','$log','$ip','$campaign','$now')";
	 
		$query = $this->db->query($sql);
			
		if ($query)
		{
			$sql = "SELECT id FROM $this->activities WHERE user_id = '$user_id' AND object = '$object' AND object_id = '$object_id' AND action = '$action' AND 
					log = '$log' AND ip_address = '$ip' AND campaign = '$campaign' AND created_at = '$now' ";
			$query = $this->db->query($sql);
			$result = $query->result();
			return $result[0]->id;
		}
	}
	
	######
	##Only for Devository
	######
	public function get_activities_devo($param)
	{
		/*
		Model to get activities with filter
		
		Param (Array)
		
		result  : the result of model, array or total
		limit, limit order : How much to show
		action  : by action
		user_id : by single user
		*/
		
		$question = 'discuss_question';
		
		if ($param['result'] == 'total') {
			$this->db->select($this->activities.'.id');
		} else {
			$this->db->select('*');
		}
		
		$this->db->from($this->activities);
		$this->db->join($question, $this->activities.'.object_id'.'='.$question.'.id');
		
		if (!empty($param['action']))
		{
			$this->db->where($this->activities.'.action', $param['action']);
		}
		
		if (!empty($param['user_id']))
		{
			$this->db->where($this->activities.'.user_id', $param['user_id']);
		}
		
		if (!empty($param['limit']))
		{
			$this->db->limit($param['limit'], $param['limit_order']);
		} 
		
		$this->db->order_by($this->activities.'.id', 'desc');
		
		if ($param['result'] == 'total') {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}	
	}
}