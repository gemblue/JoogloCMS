<?php
class Mdl_taxonomy extends CI_Model
{
	var $posts = 'jooglo_posts';
	var $terms = 'jooglo_terms';
	var $term_relationships = 'jooglo_term_relationships';
	var $term_taxonomy = 'jooglo_term_taxonomy';
	
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| CREATE
	| ---------------------------------------------------------------
	|
	*/
	
	function insert_term($data, $type)
	{
		/*
		model to insert new term/categories/tags
		*/
		
		if (!empty($data['name']))
		{
			$sql_insert_term = "INSERT INTO $this->terms (name, slug) VALUES ('$data[name]','$data[slug]')";
			$query_insert_term = $this->db->query($sql_insert_term);
			
			if ($query_insert_term)
			{
				# after insert term, we have to get its id
				$sql_get_term_id = "SELECT term_id FROM $this->terms WHERE slug = '$data[slug]' ";
				$query = $this->db->query($sql_get_term_id);
				$query_result = $query->result();
				
				foreach ($query_result as $row)
				{
					$term_id = $row->term_id;
				}
				
				# yes, then insert the term id into taxonomy
				$sql_insert_tax = "INSERT INTO $this->term_taxonomy (term_id, taxonomy) VALUES ('$term_id','$type')";
				$query_insert_tax = $this->db->query($sql_insert_tax);
				
				return true;
				
			}
		}
	}
	
	function insert_relation($term_taxonomy_id,$object_id)
	{
		/*
		model to insert new term/categories/tags relation with object (post/etc)
		*/
		$sql = "INSERT INTO $this->term_relationships (object_id, term_taxonomy_id) VALUES ('$object_id','$term_taxonomy_id')";
		$query = $this->db->query($sql);
		return true;
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| GET / RETRIEVE
	| ---------------------------------------------------------------
	|
	*/
	
	function get_post_category($id_post)
	{
		/*
		model to get the post category from the taxonomy by id post
		*/
		$sql = "SELECT d.term_id, d.name FROM $this->posts AS a LEFT JOIN ($this->term_relationships AS b, $this->term_taxonomy AS c, $this->terms AS d) ON (a.id = b.object_id AND b.term_taxonomy_id = c.term_taxonomy_id AND c.term_id = d.term_id) WHERE a.id = '$id_post' AND c.taxonomy LIKE '%category%' AND c.parent = '0'";

		$query = $this->db->query($sql);
		$data =  $query->result();
		foreach ($data as $row)
		{
			$result = array(
				'name' => $row->name,
				'id' => $row->term_id
			); 
			
			return $result;
			break;
		}
	}
	
	function get_post_tags($id_post)
	{
		/*
		model to get the post tags from the taxonomy by id post
		*/
		$sql = "SELECT d.name FROM $this->posts AS a LEFT JOIN ($this->term_relationships AS b, $this->term_taxonomy AS c, $this->terms AS d) ON (a.id = b.object_id AND b.term_taxonomy_id = c.term_taxonomy_id AND c.term_id = d.term_id) WHERE a.id = '$id_post' AND c.taxonomy = 'post_tag'";

		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_all_terms($type)
	{
		/*
		model to get all the terms from the taxonomy
		*/
		$sql = "SELECT b.slug,b.term_id,b.name FROM $this->term_taxonomy AS a INNER JOIN $this->terms AS b ON a.term_id = b.term_id WHERE a.taxonomy = '$type' AND a.parent = '0'";

		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_tot_terms($type)
	{
		/*
		model to get total all the post category from the taxonomy
		*/
		$sql = "SELECT b.term_id FROM $this->term_taxonomy AS a INNER JOIN $this->terms AS b ON a.term_id = b.term_id WHERE a.taxonomy = '$type' AND a.parent = '0'";

		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	function get_term_by_id($id)
	{
		/*
		model get the category/term/tag data by id
		*/
		$sql = "SELECT * FROM $this->terms WHERE term_id = '$id'";

		$query = $this->db->query($sql);
		return $query->result();
	}
	
	function get_term_taxonomy_id($term_id, $post_type)
	{
		/*
		model get the term taxonomy id by term id
		*/
		if ($post_type == 'post') {
			$taxonomy = 'category';
		} else {
			$taxonomy = $post_type.'_category';
		}
		
		$sql = "SELECT term_taxonomy_id FROM $this->term_taxonomy WHERE term_id = '$term_id' AND taxonomy = '$taxonomy' ";
		$query = $this->db->query($sql);
		$data = $query->result();
		
		$result = null;
		
		foreach ($data as $row)
		{
			$result = $row->term_taxonomy_id;
		}
		
		return $result;
	}

	function get_slug_name_by_id($id_term)
	{
		/*
		model to get slug term by id term
		*/
		$sql = "SELECT slug FROM $this->terms WHERE term_id = '$id_term'";

		$query = $this->db->query($sql);
		$data = $query->result();
		
		foreach ($data as $row)
		{
			$slug = $row->slug;
		}
		
		return $slug;
		
	}
	
	function get_field_value($field_name, $by_field, $field_value)
	{
		/*
		model to get field value by other field
		
		$sql = "SELECT $field_name FROM $this->terms INNER JOIN $this->term_taxonomy ON tbl_terms.term_id = tbl_term_taxonomy.term_id WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		return $query->result();
		*/
		
		$this->db->select($field_name);
		$this->db->from($this->terms);
		$this->db->join($this->term_taxonomy, $this->terms.'.term_id'.'='.$this->term_taxonomy.'.term_id');
		$this->db->where($by_field, $field_value);
		return $this->db->get()->result();
	}
	
	function get_list_terms($num, $pg, $type)
	{
		/*
		model to get all terms/category/tags with limit, for paging
		*/
		$this->db->select($this->terms.'.slug,'.$this->terms.'.term_id,'.$this->terms.'.name');
		$this->db->from($this->term_taxonomy);
		$this->db->join($this->terms, $this->terms.'.term_id'.'='.$this->term_taxonomy.'.term_id');
		$this->db->where('taxonomy', $type);
		$this->db->where('parent', '0');
		$this->db->limit($num, $pg);
		$this->db->order_by('term_id','desc');
		return $this->db->get()->result();
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| UPDATE
	| ---------------------------------------------------------------
	|
	*/
	
	function update_term($term_id, $data)
	{
		/*
		model update the term/category/tag record by id
		*/
		$this->db->where('term_id', $term_id);
		$this->db->update($this->terms, $data);
	}

	/*
	|
	| ---------------------------------------------------------------
	| CHECKER MODEL RETURN TRUE/FALSE IS_THIS
	| ---------------------------------------------------------------
	|
	*/
	
	function is_term_exist($by_field, $field_value)
	{
		/*
		model to check is the category/tag/term exist
		true : exist
		false : not exist
		*/
		$sql = "SELECT term_id FROM $this->terms WHERE $by_field = '$field_value'";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		
		if ($total > 0)
		{
			return true;
		}
	}
	
	function is_relation_exist($term_taxonomy_id,$object_id)
	{
		/*
		model to check is relation exist
		true : exist
		false : is not exist
		*/
		$sql = "SELECT object_id FROM $this->term_relationships WHERE term_taxonomy_id = '$term_taxonomy_id' AND object_id = '$object_id' ";
		$query = $this->db->query($sql);
		$total = $query->num_rows();
		if ($total > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/*
	|
	| ---------------------------------------------------------------
	| DELETE
	| ---------------------------------------------------------------
	|
	*/
	
	function delete_relation($object_id, $type)
	{
		/*
		model to delete the relation that related to the object id. Object id can be the post 
		*/
		
		if ($type == 'all')
		{
			$sql = "DELETE FROM $this->term_relationships WHERE object_id = '$object_id'";
		}
		else if ($type == 'category')
		{
			$sql = "DELETE s.* FROM $this->term_relationships s INNER JOIN $this->term_taxonomy n ON s.term_taxonomy_id = n.term_taxonomy_id WHERE s.object_id = '$object_id' AND n.taxonomy LIKE '%category%' ";
		}
		else if ($type == 'post_tag')
		{
			$sql = "DELETE s.* FROM $this->term_relationships s INNER JOIN $this->term_taxonomy n ON s.term_taxonomy_id = n.term_taxonomy_id WHERE s.object_id = '$object_id' AND n.taxonomy = 'post_tag' ";
		}
		
		$query = $this->db->query($sql);
		return true;
	}
	
	function delete_term($id)
	{
		/*
		model to delete the term master
		
		if the term is category :
		when we delete the term master we have to make sure the post that related to the term is save
		the solution is change the post that related become uncategories (change the term relationship value)
		uncategories term id is 1
		
		if the term is tag :
		delete the term master and delete all the relationship that related to the tag
		*/
		
		# knowing it's taxonomy id and type
		$sql = "SELECT term_taxonomy_id, taxonomy FROM $this->term_taxonomy WHERE term_id = '$id'";
		$query = $this->db->query($sql);
		$result = $query->result();
		
		foreach ($result as $row)
		{
			$term_taxonomy_id = $row->term_taxonomy_id;
			$term_type = $row->taxonomy;
		}
		
		if ($term_type == 'category')
		{
			# its category
			# change all the term relationship value that related to the term taxonomy id become uncategories
			$sql = "UPDATE $this->term_relationships SET term_taxonomy_id = '1' WHERE term_taxonomy_id = '$term_taxonomy_id' ";
			$query = $this->db->query($sql);
			
			# okey we have done a good job, then say bye bye to the term 
			$sql = "DELETE FROM $this->terms WHERE term_id = '$id'";
			$query = $this->db->query($sql);
			$sql = "DELETE FROM $this->term_taxonomy WHERE term_id = '$id'";
			$query = $this->db->query($sql);
			
			return true;
		} 
		else
		{
			# its post tag
			# delete all the term relationship value that related to the term taxonomy id
			$sql = "DELETE FROM $this->term_relationships WHERE term_taxonomy_id = '$term_taxonomy_id' ";
			$query = $this->db->query($sql);
			
			# okey we have done a good job, then say bye bye to the term 
			$sql = "DELETE FROM $this->terms WHERE term_id = '$id'";
			$query = $this->db->query($sql);
			$sql = "DELETE FROM $this->term_taxonomy WHERE term_id = '$id'";
			$query = $this->db->query($sql);
			
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
	
	function search_terms($type,$by_field,$field_value,$result)
	{
		/*
		model to get search terms by field 
		*/
		
		if ($result == 'total')
		{
			$this->db->select('term_taxonomy_id');
		}
		else
		{
			$this->db->select($this->terms.'.slug,'.$this->terms.'.term_id,'.$this->terms.'.name');
		}
	
		$this->db->from($this->term_taxonomy);
		$this->db->join($this->terms, $this->term_taxonomy.'.term_id'.'='.$this->terms.'.term_id');
		$this->db->where($this->term_taxonomy.'.taxonomy',$type);
		$this->db->where($this->term_taxonomy.'.parent',0);
		$this->db->like($by_field, $field_value);
		
		if ($result == 'total')
		{
			return $this->db->get()->num_rows();
		}
		else
		{
			return $this->db->get()->result();
		}
	}
}
?>