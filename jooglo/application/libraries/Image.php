<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

class Image {

	var $ci = null;
	
	# constructor
	function Image()
	{
		$this->ci =& get_instance();
		$this->ci->load->library('image_moo');
	}
	
	function delete_file($path)
	{	
		$exists = file_exists($path);
		
		if ($exists) {
			unlink($path); 
		}
		
		return true;
	}

	function crop_proporsional($upload_path, $image_name)
	{	
		$image_path = $upload_path.$image_name;
  		$size = GetImageSize($image_path, $info);
  		$img_x = $size[0]; 
  		$img_y = $size[1]; 
		
		$center_width = $img_x / 2;
		$center_height = $img_y /2;
		
		if ($img_x < $img_y)
		{
			$x1 = 0;
			$x2 = $img_x;
			$y1 = $center_height - $center_width;
			$y2 = $center_height + $center_width;
		}
		else
		{
			$y1 = 0;
			$y2 = $img_y;
			$x1 = $center_width - $center_height;
			$x2 = $center_width + $center_height;
		}
	
        $this->ci->image_moo
			->load($image_path)
			->crop($x1,$y1,$x2,$y2)
			->save($upload_path.'croped_'.$image_name);
		
		return true;
	}

	function generate_thumbnail($upload_path, $image_name)
	{	
		$this->ci->image_moo
			->load($upload_path. $image_name)
			->resize(150,150)
			->save($upload_path. 'sm_'.$image_name)
			->resize(300,300)
			->save($upload_path. 'md_'.$image_name)
			->resize(600,600)
			->save($upload_path. 'lg_'.$image_name);	
			
		$this->ci->image_moo
			->load($upload_path. 'croped_'.$image_name)
			->resize(80,80)
			->save($upload_path. 'xs_'.$image_name)
			->resize(300,300)
			->save($upload_path. 'square_'.$image_name);
	}
}