<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// This is the controller that handle comment action (native/ajax).
 
class Comment extends MY_Controller 
{
	// Comment setting
	var $comment_direct_approved = true;
	
	public function __construct()
	{		
		parent::__construct();
	}
	
	// Delete comment
	public function delete($id_comm, $url_from)
	{
		$role = $this->session->userdata('role_id');
		if ($role == '1')
		{
			$op = $this->mdl_comm->del_comment($id_comm);
			if ($op == TRUE)
			{
				if (!empty($url_from)){
					# jika dari comment all by kembalikan lagi ke sana
					redirect('dash/comment_all_by/'.$url_from);
				} else {
					$this->session->set_flashdata('message', '<div class="alert alert-success">Comment successfully deleted!</div>');
					redirect('dash/comment_all');
				}
			} 
			else 
			{
				echo "failed";
			}
		} 
	}
	
	// Create new
	public function create()
	{
		// Get post
		$object_id = post_filter($this->input->post('object_id'));
		$object_type = post_filter($this->input->post('object_type'));
		$comment = post_filter($this->input->post('comment'));
		$parent = $this->input->post('parent');
		$callback = post_filter(urldecode($this->input->post('callback')));
		$comment_token = $this->input->post('comment_token');
		
		// Only authenticated user can comment
		if ($this->data['logged_in'] != true)
		{
			if (!empty($callback))
			{
				$this->session->set_flashdata('message', 'login first.');
				redirect('u/login?callback='.$callback);
			}
			else
			{
				echo 'login_required';
				exit;
			}
		}
		else
		{
			// Token check
			
			/*
			if ($comment_token != $this->session->userdata('comment_token'))
			{
				$this->session->set_flashdata('message', 'Comment token invalid.');
				redirect($callback);
				exit;
			}
			*/
			
			// Validate
			if (empty($comment))
			{
				if (!empty($callback))
				{
					$this->session->set_flashdata('message', 'Comment can not be empty');
					redirect($callback);
					exit;
				}
				else
				{
					echo 'comment_must_be_filled';
					exit;
				}
			}
			
			$data = array(
				'object_id' => $object_id,
				'object_type' => $object_type,
				'comment' => $comment,
				'parent' => $parent,
				'author' => $this->session->userdata('id'),
				'approved' => $this->comment_direct_approved,
				'created_at' => date('Y-m-d H:i:s')
			);
			
			$comment_id = $this->mdl_comment->create($data);
			
			if ($comment_id > 0)
			{
				// Add user activities
				$activity_id = $this->activities_m->insert_activities($this->session->userdata('id'), 'discuss', $object_id, 'comment', '', 'devository');
				
				// Add user point
				$this->point_m->add_users_point_log($activity_id, 'comment');
				
				// Get question id by answer 
				$question_id = $this->mdl_discuss->get_questions_id_by_answer($object_id);
				
				// Get question owner
				$question_owner = $this->mdl_discuss->get_questions_author($question_id);
				
				// Subscribe to question
				$this->subscribe_m->new_subscribe($this->session->userdata('id'), $question_id, 1, 'discuss_question');
				
				// Push notification
				$this->notification->push($question_owner, $this->session->userdata('id'), 'mengomentari pertanyaan', $question_id);
				
				// Token update
				$this->session->set_userdata('comment_token', random_string('alnum', 7));
				
				// Redirect
				if (!empty($callback))
				{
					$this->session->set_flashdata('message', 'Comment added');
					redirect($callback);
				}
				else
				{
					echo $comment_id;
				}
			}
		}		
	}

	function show_comments($object_id, $object_type, $template = 'comments')
	{
		$data['comment'] = $this->mdl_comment->get_comment('array', true, $object_id, $object_type, $parent);
		return $this->load->view($template, $data, true);
	}

	function coba()
	{
		header("Content-Type: text/plain");
		$comments = $this->db
							->where('object_id', 57)
							->where('object_type', 'discuss_answer')
							->get('jooglo_comments')->result_array();

		$the_comment = array();
		foreach ($comments as $comment) {
			if($comment['parent'] == 0)
				$the_comment[0] = $comment;
		}

		print_r($comments);

	}
}