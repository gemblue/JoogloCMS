<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

/*
The template view paging library for Jooglo CMS
We can't use Codeigniter default paging lib because of the function is different.
We use this library for template only. Not in controller.
*/

class Paging {

	var $ci;
	var $total_per_page;
	var $page_on;
	var $total_page;
	var $show_page = '';
	var $slug;
	
	/*
	Constructor
	*/
	function Paging()
	{
		$this->ci =& get_instance();
	}
	
	/*
	Setter and process
	*/
	function set($total_per_page, $total_post, $slug)
	{
		$total_page = ceil($total_post/$total_per_page);
		
		if (isset( $_GET['p']))
		{
			$page_on = $_GET['p'];
		}
		else
		{
			$page_on = 1;
		}
					
		# setted
		$this->page_on = $page_on;
		$this->total_per_page = $total_per_page;
		$this->total_page = $total_page;
		$this->slug = $slug;
		
		return true;
	}
	
	/*
	Getter
	*/
	function get($type)
	{
		if ($type == 'total_per_page')
		{
			return $this->total_per_page;
		}
		
		if ($type == 'page_on')
		{
			return $this->page_on;
		}
	}
	
	/*
	Show paging navigation
	*/
	function navigation($separator = '?')
	{
		if ($this->page_on > 1) 
		{
			echo "<li><a href='".site_url($this->slug).$separator."p=".($this->page_on-1)." ' class='btn btn-primary' ><i class='fa fa-angle-left'></i> Prev</a></li>";
		} 
		else 
		{
			echo "";
		}
		
		for ($page = 1; $page <= $this->total_page; $page++)
		{
			if ((($page >= $this->page_on - 3) && ($page <= $this->page_on + 3)) || ($page == 1) || ($page == $this->total_page))
			{
				if (($this->show_page == 1) && ($page != 2))  echo "...";
				if (($this->show_page != ($this->total_page - 1)) && ($page == $this->total_page))  echo "...";
				
				if ($page == $this->page_on) 
				{
					echo '<li><a href="'.site_url($this->slug).$separator.'p='.$page.'" class="btn btn-primary active">'.$page.'</a></li>';
				}
				else 
				{
					echo "<li><a href='".site_url($this->slug).$separator."p=".$page."' class='btn btn-primary'>".$page."</a></li>";
				}
				
				$this->show_page = $page;
			}
		}
		
		if ($this->page_on < $this->total_page) 
		{
			echo "<li><a href='".site_url($this->slug).$separator."p=".($this->page_on + 1)." ' class='btn btn-primary' >Next <i class='fa fa-angle-right'></i></a></li>";
		} 
		else 
		{
			echo "";
		}
	}
}