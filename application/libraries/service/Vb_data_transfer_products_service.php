<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_products_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Product_dao.php');
		$this->product_dao = new Product_dao();
		include_once(APPPATH . 'libraries/dao/Sku_mapping_dao.php');
		$this->sku_mapping_dao = new Sku_mapping_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();

		include_once(APPPATH . 'libraries/service/Product_identifier_service.php');
		$this->product_identifier_service = new Product_identifier_service();
	}
	
	public function get_dao()
	{
		return $this->product_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->product_dao = $dao;
	}
	
	public function get_sku_dao()
	{
		return $this->sku_mapping_dao;
	}
		
	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
	public function process_vb_data ($feed)
	{		
		//print $feed; exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
		$is_error_task = $xml_vb->attributes()->is_error_task;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<products task_id="' . $task_id . '">';	
				
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $product)
		{
			$c--;
				
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = strtoupper($product->master_sku);						
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
			
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			/*
				MASTER SKU VALIDATION
				1.- sku from VB exists in AV2 product table in the sku_vb field
					1.1.- Look for the master sku un AV2, get the local sku
					1.2.- Compare the local sku in mapping table with local sku in product table
						1.2.1.- Both skus are equal -> update product
						1.2.2.- Different skus -> update mapping with the new master, update product
				2.- sku from VB doesnt exist in AV2 product table in the sku_vb field
					Normal process - Look for the master sku un AV2:
						2.1.- exists -> if the product exists, update, if the product doesnt exist, insert product
						2.2.- doesnt exist: no update
			*/				
			
			//get the sku for the product table with the VB sku
			$sku_table = "";
			if($pc_obj_atomv2 = $this->get_dao()->get(array("sku_vb"=> $product->sku)))
			{
				$sku_table = $pc_obj_atomv2["sku"];
			}
			
			$berror_mapping = false;
			//if the VB sku exists in product table
			if ($sku_table != "" && $sku_table != null)
			{
				//if the mapping for the new master sku doesnt exist, we change the mapping and continue the update
				if ($sku == "" || $sku == null) 
				{
					$bchange_mapping = true;
					$sku = $sku_table;
					$master_sku = $product->master_sku;
					$fail_reason = "";
				}
				//if the new mapping has a different sku, we dont continue with the update, we return a message error
				elseif($sku != $sku_table)
				{
					$fail_reason = "Different master sku. Sku exists, ";
					$berror_mapping = true;
				}
				//elseif $sku == $sku_table --> normal update
			}		
			
            //if the sku is mapped, we get the atomv prod_gro_id
            $master_prod_grp_id = "";
            if ($fail_reason == "")
            	$master_prod_grp_id = $this->product_identifier_service->get_prod_grp_cd_by_sku($master_sku);			
            
			try
			{
				if ($fail_reason == "")
				{
					if ($bchange_mapping == true)
					{						
						//update sku mapping						
						$where = array("sku"=>$sku_table);					
						$sku_map_obj = array();
						
						$sku_map_obj["ext_sku"] = $product->master_sku; 	
					
						$this->sku_mapping_service->get_dao()->q_update($where, $sku_map_obj);
					}
				
					//Update the AtomV2 product data 					
					$where = array("sku"=>$sku);
					
					$new_prod_obj = array();
					
					$new_prod_obj["sku_vb"] = $product->sku; //sku from VB
					$new_prod_obj["prod_grp_cd"] = $master_prod_grp_id;//$product->prod_grp_cd;
					$new_prod_obj["colour_id"] = $product->colour_id; //FK colour
					$new_prod_obj["version_id"] = $product->version_id;	
					$new_prod_obj["name"] = $product->name;				
					$new_prod_obj["freight_cat_id"] = $product->freight_cat_id; //FK freight_category
					$new_prod_obj["cat_id"] = $product->cat_id; //FK category
					$new_prod_obj["sub_cat_id"] = $product->sub_cat_id; //FK category
					$new_prod_obj["sub_sub_cat_id"] = $product->sub_sub_cat_id; //FK category
					$new_prod_obj["brand_id"] = $product->brand_id; //FK brand
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
					$new_prod_obj["shipment_restricted_type"] = $product->shipment_restricted_type; 
					//$new_prod_obj["comments"] = $product->comments; 	
					$new_prod_obj["status"] = $product->status; 		
					
					$this->get_dao()->q_update($where, $new_prod_obj);				
					
					$xml[] = '<product>';
					$xml[] = '<sku>' . $product->sku . '</sku>';
					$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
					$xml[] = '<status>5</status>';	 //updated				
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';	
					$xml[] = '<reason></reason>';		
					$xml[] = '</product>';
				}
				elseif ($berror_mapping == true)
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $product->sku . '</sku>';
					$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';				
					$xml[] = '<status>6</status>'; //mapping error	
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';	
					$xml[] = '<reason>' . $fail_reason . '</reason>';		
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{	
					//TO DO --> Create product function
					
					//insert									
					$new_prod_obj = $this->get_dao()->get();
					
					$sku = $this->get_dao()->getNewSku();
					$prod_grp_cd = $this->get_dao()->getNewProductGroup();
					if ($sku != "" && $sku != null && $prod_grp_cd != "" && $prod_grp_cd != null)
					{
						//insert sku mapping
						$new_sku_map_obj = $this->get_sku_dao()->get();
						
						$new_sku_map_obj->set_ext_sku($master_sku);
						$new_sku_map_obj->set_ext_sys("WMS");
						$new_sku_map_obj->set_sku($sku);
						$new_sku_map_obj->set_status(1);
						
						$this->sku_mapping_service->get_dao()->insert($new_sku_map_obj);
						
						//insert the product
						$new_prod_obj->set_sku($sku);
						$new_prod_obj->set_sku_vb($product->sku); // Sku from VB
						$new_prod_obj->set_prod_grp_cd($prod_grp_cd);
						$new_prod_obj->set_colour_id($product->colour_id); //FK colour
						$new_prod_obj->set_version_id($product->version_id);	
						$new_prod_obj->set_name($product->name);				
						$new_prod_obj->set_freight_cat_id($product->freight_cat_id); //FK freight_category
						$new_prod_obj->set_cat_id($product->cat_id); //FK category
						$new_prod_obj->set_sub_cat_id($product->sub_cat_id); //FK category
						$new_prod_obj->set_sub_sub_cat_id($product->sub_sub_cat_id); //FK category
						$new_prod_obj->set_brand_id($product->brand_id); //FK brand
						$new_prod_obj->set_clearance($product->clearance);
						$new_prod_obj->set_surplus_quantity($product->surplus_quantity);
						$new_prod_obj->set_slow_move_7_days($product->slow_move_7_days);
						$new_prod_obj->set_quantity($product->quantity);
						$new_prod_obj->set_display_quantity($product->display_quantity);
						$new_prod_obj->set_website_quantity($product->website_quantity);
						$new_prod_obj->set_china_oem($product->china_oem); 
						$new_prod_obj->set_ex_demo($product->ex_demo);
						$new_prod_obj->set_rrp($product->rrp);
						$new_prod_obj->set_image($product->image);
						$new_prod_obj->set_flash($product->flash);
						$new_prod_obj->set_youtube_id($product->youtube_id);
						$new_prod_obj->set_ean($product->ean);
						$new_prod_obj->set_mpn($product->mpn);
						$new_prod_obj->set_upc($product->upc);
						$new_prod_obj->set_discount($product->discount);
						$new_prod_obj->set_proc_status($product->proc_status);
						$new_prod_obj->set_website_status($product->website_status);
						$new_prod_obj->set_sourcing_status($product->sourcing_status);
						$new_prod_obj->set_expected_delivery_date($product->expected_delivery_date);
						$new_prod_obj->set_warranty_in_month($product->warranty_in_month);
						$new_prod_obj->set_cat_upselling($product->cat_upselling);
						$new_prod_obj->set_lang_restricted($product->lang_restricted);
						$new_prod_obj->set_shipment_restricted_type($product->shipment_restricted_type); 
						//$new_prod_obj->set_comments($product->comments); 	
						$new_prod_obj->set_status($product->status); 
						
						$this->get_dao()->insert($new_prod_obj);
						
						//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
						$xml[] = '<product>';
						$xml[] = '<sku>' . $product->sku . '</sku>';
						$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';				
						$xml[] = '<status>5</status>'; //inserted	
						$xml[] = '<is_error>' . $product->is_error . '</is_error>';	
						$xml[] = '<reason></reason>';			
						$xml[] = '</product>';
					}
					else
					{
						$xml[] = '<product>';
						$xml[] = '<sku>' . $product->sku . '</sku>';
						$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
						$xml[] = '<status>2</status>'; //not found	
						$xml[] = '<is_error>' . $product->is_error . '</is_error>';
						$xml[] = '<reason>' . $fail_reason . '</reason>';	
						$xml[] = '</product>';	
					}
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<sku>' . $product->sku . '</sku>';
					$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
					$xml[] = '<status>3</status>'; //not updated	
					$xml[] = '<is_error>' . $product->is_error . '</is_error>';
					$xml[] = '<reason>' . $fail_reason . '</reason>';	
					$xml[] = '</product>';				
				}	
			}	
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<sku>' . $product->sku . '</sku>';
				$xml[] = '<master_sku>' . $product->master_sku . '</master_sku>';					
				$xml[] = '<status>4</status>';	//error		
				$xml[] = '<is_error>' . $product->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';	
				$xml[] = '</product>';	
			}
		 }
		 
		$xml[] = '</products>';
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}