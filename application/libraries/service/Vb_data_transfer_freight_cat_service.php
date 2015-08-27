<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_freight_cat_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Freight_category_dao.php');
		$this->freight_cat_dao = new Freight_category_dao();
	}
	
	public function get_dao()
	{
		return $this->freight_cat_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->freight_cat_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the freight_cat table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		// print $feed;
		// exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
						
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_freight_cats task_id="' . $task_id . '">';
					
		$c = count($xml_vb->freight_cat);
		foreach($xml_vb->freight_cat as $freight_cat)
		{
			$c--;			
				
			$id = $freight_cat->id;
						
			if($this->get_dao()->get(array("id"=>$freight_cat->id)))
			{
				//update					
				$where = array("id"=>$id);
				
				$new_freight_cat_obj = array();
				
				$new_freight_cat_obj["name"] = $freight_cat->name;
				$new_freight_cat_obj["weight"] = $freight_cat->weight;	
				$new_freight_cat_obj["declared_pcent"] = $freight_cat->declared_pcent;
				$new_freight_cat_obj["bulk_admin_chrg"] = $freight_cat->bulk_admin_chrg;					
				$new_freight_cat_obj["status"] = $freight_cat->status;	
				
				$this->get_dao()->q_update($where, $new_freight_cat_obj);
			}
			else
			{
				//insert			
				$new_freight_cat_obj = array();
				
				$new_freight_cat_obj = $this->get_dao()->get();
				$new_freight_cat_obj->set_id($freight_cat->id);
				$new_freight_cat_obj->set_name($freight_cat->name);
				$new_freight_cat_obj->set_weight($freight_cat->weight);
				$new_freight_cat_obj->set_declared_pcent($freight_cat->declared_pcent);
				$new_freight_cat_obj->set_bulk_admin_chrg($freight_cat->bulk_admin_chrg);
				$new_freight_cat_obj->set_status($freight_cat->status);
				
				$this->get_dao()->insert($new_freight_cat_obj);	
			}            
		 }
		 
		$xml[] = '</no_updated_freight_cats>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}