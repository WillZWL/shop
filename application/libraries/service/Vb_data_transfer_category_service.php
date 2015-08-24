<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_category_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Category_dao.php');
		$this->category_dao = new Category_dao();
		
        include_once(APPPATH . "libraries/service/Category_id_mapping_service.php");		
		$this->category_id_mapping_service = new Category_id_mapping_service();
		
        include_once(APPPATH . "libraries/dao/Category_id_mapping_dao.php");		
		$this->category_id_mapping_dao = new Category_id_mapping_dao();
	}
	
	public function get_dao()
	{
		return $this->category_dao;
	}
	
	public function get_map_dao()
	{
		return $this->category_id_mapping_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->category_dao = $dao;
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
			$ext_id = $category->id;
						
			$id = $this->category_id_mapping_service->get_local_id($ext_id);			
			
            if ($id == "" || $id == null)
			{
				//insert category and mapping
				$new_cat_obj = array();
				
				$new_cat_obj = $this->get_dao()->get();
				$new_cat_obj->set_name($category->name);
				$new_cat_obj->set_description($category->description);
				$new_cat_obj->set_parent_cat_id($category->parent_cat_id);
				$new_cat_obj->set_level($category->level);
				$new_cat_obj->set_add_colour_name($category->add_colour_name);
				$new_cat_obj->set_priority($category->priority);
				$new_cat_obj->set_bundle_discount($category->bundle_discount);
				$new_cat_obj->set_min_display_qty($category->min_display_qty);
				$new_cat_obj->set_status($category->status);
				
				$this->get_dao()->insert($new_cat_obj);	
				$new_id = $new_cat_obj->get_id();
					
				if ($new_id != 0 && $new_id != null)
				{					
					//Insert mapping 
					$new_map_obj["id"] = $new_id;
					$new_map_obj["ext_id"] = $ext_id;
					$new_map_obj["status"] = $category->status; //same status of the category ????
					
					$this->get_map_dao()->q_insert($new_map_obj);
				}
				
			}
			elseif ($id != "" && $id != null)
			{
				//Update the AtomV2 category data 					
				$where = array("id"=>$id);
				
				$new_cat_obj = array();
				
				$new_cat_obj["name"] = $category->name;
				$new_cat_obj["description"] = $category->description;	
				$new_cat_obj["parent_cat_id"] = $category->parent_cat_id;				
				$new_cat_obj["level"] = $category->level;
				$new_cat_obj["add_colour_name"] = $category->add_colour_name;	
				$new_cat_obj["priority"] = $category->priority;	
				$new_cat_obj["bundle_discount"] = $category->bundle_discount;					
				$new_cat_obj["min_display_qty"] = $category->min_display_qty;					
				$new_cat_obj["status"] = $category->status;	
				
				$this->get_dao()->q_update($where, $new_cat_obj);
			}
			else
			{
				//if the ext_id is not changed in atomv2, we store it in an xml string to send it to VB
				$xml[] = '<category>';
				$xml[] = '<id>' . $category->id . '</id>';
				$xml[] = '</category>';
			}
		 }
		 
		$xml[] = '</no_updated_categories>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}