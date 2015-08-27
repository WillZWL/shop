<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_identifier_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Product_identifier_dao.php');
		$this->product_identifier_dao = new Product_identifier_dao();
	}
	
	public function get_dao()
	{
		return $this->product_identifier_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->product_identifier_dao = $dao;
	}
		
	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_product_identifiers task_id="' . $task_id . '">';
		
		$error_nodes = array();	
		$error_nodes[] = '<errors task_id="' . $task_id . '">';		
				
		$c = count($xml_vb->product_identifier);
		foreach($xml_vb->product_identifier as $product)
		{
			$c--;
			
			if(!$pc_obj_atomv2 = $this->get_dao()->get(array("prod_grp_cd"=>$product->prod_grp_cd, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id)))
			{
				$fail_reason .= "Product identifier not specified, ";
			}
				
			if ($fail_reason == "")
			{
				//Update the AtomV2 product data 					
				$where = array("prod_grp_cd"=>$product->prod_grp_cd, "colour_id"=>$product->colour_id, "country_id"=>$product->country_id);
				
				$new_prod_obj = array();
				
				$new_prod_obj["ean"] = $product->ean;
				$new_prod_obj["mpn"] = $product->mpn; 
				$new_prod_obj["upc"] = $product->upc;	
				$new_prod_obj["status"] = $product->status;			
				
				$this->get_dao()->q_update($where, $new_prod_obj);
			}
			else
			{
				//insert				
				$new_prod_obj = $this->get_dao()->get();
				
				$new_prod_obj->set_prod_grp_cd($product->prod_grp_cd); 
				$new_prod_obj->set_colour_id($product->colour_id); 	
				$new_prod_obj->set_country_id($product->country_id); 	
				$new_prod_obj->set_ean($product->ean);
				$new_prod_obj->set_mpn($product->mpn); 
				$new_prod_obj->set_upc($product->upc);	
				$new_prod_obj->set_status($product->status);			
				
				$this->get_dao()->insert($new_prod_obj);
			}
			// else
			// {		
				// //insert ??????
				
				// //if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
				// $xml[] = '<product_identifier>';
				// $xml[] = '<prod_grp_cd>' . $product->prod_grp_cd . '</prod_grp_cd>';
				// $xml[] = '<colour_id>' . $product->colour_id . '</colour_id>';
				// $xml[] = '<country_id>' . $product->country_id . '</country_id>';
				// $xml[] = '</product_identifier>';
			// }
		 }
		 
		$xml[] = '</no_updated_product_identifiers>';
				
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}