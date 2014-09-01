<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
|
|  Author : Toni Haryanto
|  Modules library for managing modules
|  http://id.toniharyanto.net
|
*/

class Module
{
	var	$modules;
	var	$modules_folder;
	var $CI;
	
	function __construct()
	{
		$this->modules = array();
		$this->modules_folder = array();

		$this->CI = &get_instance();
	}

	/*
	get valid module
	*/
	function get_module_list()
	{
		// get all module folders
		foreach($this->CI->config->item('modules_locations') as $dir => $value){
			$folders = opendir($dir);

			while (($entry = readdir($folders)) !== false) {
				if ($entry != "." && $entry != "..")
					if(is_dir($dir.$entry))
						$this->modules_folder[$dir][] = $entry;
			}

    		closedir($folders);
		}

		// get module only if it has info.json file
		foreach ($this->modules_folder as $dir => $folders) {
			foreach ($folders as $folder) {
				$handle = opendir($dir.$folder);

				while (($entry = readdir($handle)) !== false) {
					if($entry == "info.json"){
						$this->modules[$folder]['location'] = (DEVPATH.'modules/' == $dir)?'dev':'core';
						$this->modules[$folder]['path'] = $dir.$folder.'/';
						$this->modules[$folder] += $this->get_module_info($folder, $this->modules[$folder]['path']);
					}
				}

				closedir($handle);
			}
		}
		return $this->modules;
	}

	function get_module_info($module, $path = false)
	{
		if($path)
			return (array) json_decode(file_get_contents($path.'info.json'));
		else {
			$module_info = array();
			foreach($this->CI->config->item('modules_locations') as $dir => $value){
				if(file_exists($dir.$module.'/info.json')){
					$module_info[$module]['location'] = (DEVPATH.'modules/' == $dir)?'dev':'core';
					$module_info[$module]['path'] = $dir.$module.'/';
					$module_info[$module] += $this->get_module_info('discuss', $module_info[$module]['path']);
				}
				continue;
			}

			return $module_info;
		}
	}

	/*
		check the right asset location of file in module
	*/
	function module_location($module_name)
	{
		$right_path = false;
		foreach($this->CI->config->item('modules_locations') as $dir => $value){
			if(is_dir($dir.$module_name)){
				$right_path = $dir.$module_name;
			}
		}
		return $right_path;
	}

	function module_asset_location($module_name, $file_path)
	{
		$right_path = false;
		foreach($this->CI->config->item('modules_locations') as $dir => $value){
			if(file_exists($dir.$module_name.'/'.$file_path)){
				$right_path = $dir.$module_name.'/'.$file_path;
			}
		}
		return $right_path;
	}
}