<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/Service/Vb_data_transfer_service.php");

class Vb_data_transfer_brand_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Brand_dao.php');
		$this->brand_dao = new Brand_dao();
		
	}
	
	public function get_dao()
	{
		return $this->brand_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->brand_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the brand table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		print $feed;
		exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_brands task_id="' . $task_id . '">';
					
		$c = count($xml_vb->brand);
		foreach($xml_vb->brand as $brand)
		{
			$c--;			
				
			$id = $brand->id;
						
			if($this->get_dao()->get(array("id"=>$brand->id)))
			{
				//update					
				$where = array("id"=>$id);
				
				$new_brand_obj = array();
				
				$new_brand_obj["brand_name"] = $brand->brand_name;
				$new_brand_obj["description"] = $brand->description;					
				$new_brand_obj["status"] = $brand->status;	
				
				$this->get_dao()->q_update($where, $new_brand_obj);
			}
			else
			{
				//insert
				$new_brand_obj = array();
				
				$new_brand_obj["id"] = $brand->id;
				$new_brand_obj["brand_name"] = $brand->brand_name;
				$new_brand_obj["description"] = $brand->description;						
				$new_brand_obj["status"] = $brand->status;	
				
				$this->get_dao()->q_insert($new_brand_obj);
			}            
		 }
		 
		$xml[] = '</no_updated_brands>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}