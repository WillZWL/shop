<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_category_extend_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Category_extend_dao.php');
		$this->category_extend_dao = new Category_extend_dao();
				
		include_once(APPPATH . 'libraries/dao/Category_dao.php');
		$this->category_dao = new Category_dao();
		
        // include_once(APPPATH . "libraries/service/Category_id_mapping_service.php");		
		// $this->category_id_mapping_service = new Category_id_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->category_extend_dao;
	}
	
	public function get_cat_dao()
	{
		return $this->category_dao;
	}
	
	// public function get_map_dao()
	// {
		// return $this->category_id_mapping_service;
	// }

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
		$xml[] = '<categories task_id="' . $task_id . '">';
					
		$c = count($xml_vb->category);
		foreach($xml_vb->category as $category)
		{
			$c--;			
						
			//$id = $this->category_id_mapping_service->get_local_id($ext_id);
			
			//We look if the category exists
			//$id = $category->cat_id;
			
			try
			{
				if($cat_atomv2 = $this->category_dao->get(array("id"=>$category->cat_id)))
				{
					$id = $cat_atomv2->get_id();				
				}
				else
				{
					$id = "";
				}
				
				if ($id != "" && $id != null)
				{
					//category exists
					$lang_id = "";
					
					if($cat_ext_atomv2 = $this->get_dao()->get(array("cat_id"=>$id, "lang_id"=>$category->lang_id)))
					{
						$lang_id .= $cat_ext_atomv2->get_lang_id();
					}				
					//if extend content exists, update
					if ($lang_id != "" && $lang_id != null)
					{
						/*//Update the AtomV2 category extend data 					
						$where = array("cat_id"=>$id, "lang_id"=>$lang_id);
						
						$new_cat_obj = array();
						
						$new_cat_obj["name"] = $category->name;
						
						$this->get_dao()->q_update($where, $new_cat_obj);*/				
						
						//return result
						$xml[] = '<category>';
						$xml[] = '<id>' . $category->cat_id . '</id>';
						$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
						$xml[] = '<status>2</status>'; //we dont update the display name of the category (only insert) --> not updated
						$xml[] = '<is_error>' . $category->is_error . '</is_error>';
						$xml[] = '</category>';		
					}
					//if not exists, insert
					else
					{
						//insert				
						$new_cat_obj = array();
						
						$new_cat_obj = $this->get_dao()->get();
						$new_cat_obj->set_cat_id($category->cat_id);
						$new_cat_obj->set_lang_id($category->lang_id);
						$new_cat_obj->set_name($category->name);
						$this->get_dao()->insert($new_cat_obj);		

						$xml[] = '<category>';
						$xml[] = '<id>' . $category->cat_id . '</id>';
						$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $category->is_error . '</is_error>';
						$xml[] = '</category>';						
					}
				}
				elseif ($id == "" || $id == null)
				{
					//if the ext_id is not changed in atomv2, we store it in an xml string to send it to VB
					$xml[] = '<category>';
					$xml[] = '<id>' . $category->cat_id . '</id>';
					$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
					$xml[] = '<status>2</status>'; //category not found
					$xml[] = '<is_error>' . $category->is_error . '</is_error>';
					$xml[] = '</category>';
				}	
			}	
			catch(Exception $e)
			{
				$xml[] = '<category>';
				$xml[] = '<id>' . $category->cat_id . '</id>';
				$xml[] = '<lang_id>' . $category->lang_id . '</lang_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $category->is_error . '</is_error>';
				$xml[] = '</category>';
			}
		 }
		 
		$xml[] = '</categories>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}