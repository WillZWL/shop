<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/Service/Vb_data_transfer_service.php");

class Vb_data_transfer_category_extend_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Category_extend_dao.php');
		$this->category_extend_dao = new Category_extend_dao();
		
        include_once(APPPATH . "libraries/Service/Category_id_mapping_service.php");		
		$this->category_id_mapping_service = new Category_id_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->category_extend_dao;
	}
	
	public function get_map_dao()
	{
		return $this->category_id_mapping_service;
	}

	public function set_dao(base_dao $dao)
	{
		$this->category_extend_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the category table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_categories task_id="' . $task_id . '">';
					
		$c = count($xml_vb->category);
		foreach($xml_vb->category as $category)
		{
			$c--;			
				
			//Get the external (VB) category id to search the corresponding id in atomv2 database
			$ext_id = $category->cat_id;
						
			$id = $this->category_id_mapping_service->get_local_id($ext_id);
			
			if ($id != "" && $id != null)
			{
				//category exists
				$lang_id = "";
				
				if($cat_ext_atomv2 = $this->get_dao()->get(array("cat_id"=>$id, "lang_id"=>$category->lang_id)))
				{
					$lang_id .= $cat_ext_atomv2["lang_id"];
				}				
				//if extend content exists, update
				if ($lang_id != "" && $lang_id != null)
				{
					//Update the AtomV2 category extend data 					
					$where = array("cat_id"=>$id, "lang_id"=>$lang_id);
					
					$new_cat_obj = array();
					
					$new_cat_obj["name"] = $category->name;
					
					$this->get_dao()->q_update($where, $new_cat_obj);
				}
				//if not exists, insert
				else
				{
					$new_cat_obj = array();
					
					$new_cat_obj["cat_id"] = $id;
					$new_cat_obj["lang_id"] = $lang_id;
					$new_cat_obj["name"] = $category->name;
					
					$this->get_dao()->q_insert($new_cat_obj);
				}
			}
			elseif ($id == "" || $id == null)
			{
				//if the ext_id is not changed in atomv2, we store it in an xml string to send it to VB
				$xml[] = '<category>';
				$xml[] = '<id>' . $category->cat_id . '</id>';
				$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
				$xml[] = '</category>';
			}
		 }
		 
		$xml[] = '</no_updated_categories>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}