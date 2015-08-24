<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_products_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Product_dao.php');
		$this->product_dao = new Product_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->product_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->product_dao = $dao;
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
		$xml[] = '<no_updated_products task_id="' . $task_id . '">';
		
		$error_nodes = array();	
		$error_nodes[] = '<errors task_id="' . $task_id . '">';		
				
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $product)
		{
			$c--;
				
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $product->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
			
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			if ($fail_reason == "")
			{
				//Update the AtomV2 product data 					
				$where = array("sku"=>$sku);
				
				$new_prod_obj = array();
				
				$new_prod_obj["prod_grp_cd"] = $product->prod_grp_cd;
				//$new_prod_obj["colour_id"] = $product->colour_id; //FK colour
				$new_prod_obj["version_id"] = $product->version_id;	
				$new_prod_obj["name"] = $product->name;				
				//$new_prod_obj["freight_cat_id"] = $product->freight_cat_id; //FK freight_category
				//$new_prod_obj["cat_id"] = $product->cat_id; //FK category
				//$new_prod_obj["sub_cat_id"] = $product->sub_cat_id; //FK category
				//$new_prod_obj["sub_sub_cat_id"] = $product->sub_sub_cat_id; //FK category
				//$new_prod_obj["brand_id"] = $product->brand_id; //FK brand
				$new_prod_obj["clearance"] = $product->clearance;
				$new_prod_obj["surplus_quantity"] = $product->surplus_quantity; // NOT EXIST IN ATOMV2
				$new_prod_obj["slow_move_7_days"] = $product->slow_move_7_days; // NOT EXIST IN ATOMV2
				$new_prod_obj["quantity"] = $product->quantity;
				$new_prod_obj["display_quantity"] = $product->display_quantity;
				$new_prod_obj["website_quantity"] = $product->website_quantity;
				$new_prod_obj["china_oem"] = $product->china_oem; // NOT EXIST IN ATOMV2
				$new_prod_obj["ex_demo"] = $product->ex_demo;
				$new_prod_obj["rrp"] = $product->rrp;
				$new_prod_obj["image"] = $product->image;
				$new_prod_obj["flash"] = $product->flash;
				$new_prod_obj["youtube_id"] = $product->youtube_id;
				$new_prod_obj["ean"] = $product->ean;
				$new_prod_obj["mpn"] = $product->mpn;
				$new_prod_obj["upc"] = $product->upc;
				$new_prod_obj["discount"] = $product->discount;
				$new_prod_obj["proc_status"] = $product->proc_status;
				$new_prod_obj["website_status"] = $product->website_status;
				$new_prod_obj["sourcing_status"] = $product->sourcing_status;
				$new_prod_obj["expected_delivery_date"] = $product->expected_delivery_date;
				$new_prod_obj["warranty_in_month"] = $product->warranty_in_month;
				$new_prod_obj["cat_upselling"] = $product->cat_upselling;
				$new_prod_obj["lang_restricted"] = $product->lang_restricted;
				$new_prod_obj["shipment_restricted_type"] = $product->shipment_restricted_type; // NOT EXIST IN ATOMV2		
				
				$this->get_dao()->q_update($where, $new_prod_obj);
			}
			elseif ($sku == "" || $sku == null)
			{				
				//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
				$xml[] = '<product>';
				$xml[] = '<sku>' . $product->sku . '</sku>';
				$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';		
				$xml[] = '</product>';
			}
			else
			{
				$error_nodes[] = '<error>';
				$error_nodes[] = '<sku>' . $product->sku . '</sku>';		
				$error_nodes[] = '<description>' . $fail_reason . '</description>';				
				$error_nodes[] = '</error>';				
			}
		 }
		 
		$error_nodes[] = '</errors>';
		$xml[] = '</no_updated_products>';
		
		//array_merge($xml, $error_nodes);
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}