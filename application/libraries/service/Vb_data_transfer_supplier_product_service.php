<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_supplier_product_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Supplier_prod_dao.php');
		$this->sp_dao = new Supplier_prod_dao();
		include_once(APPPATH . 'libraries/dao/Supplier_dao.php');
		$this->sup_dao = new Supplier_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->sp_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->sp_dao = $dao;
	}
	
	public function get_sup_dao()
	{
		return $this->sup_dao;
	}

	public function set_sup_dao(base_dao $dao)
	{
		$this->sup_dao = $dao;
	}
	
		
	/**************************************************************************
	*	process_vb_data, get the VB data to save it in the supplier prod table
	***************************************************************************/
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
		$xml[] = '<products task_id="' . $task_id . '">';
				
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $pc)
		{
			$c--;
			
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
						
			$fail_reason = "";
			$id = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			if(!$sup_obj_atomv2 = $this->get_sup_dao()->get(array("name"=>$pc->supplier_name, "id"=>$pc->supplier_id)))
			{
				$fail_reason .= "Supplier not exists, ";
				$id = "";
			}
			else
			{
				if(!$pc_obj_atomv2 = $this->get_dao()->get(array("supplier_id"=>$pc->supplier_id, "prod_sku"=>$sku)))
				{
					$fail_reason .= "ID/SKU not specified, ";
				}
				else
				{
					$id = $pc->supplier_id;
				}
			}
			
			try
			{
				if ($fail_reason == "")
				{
					//Update the AtomV2 product data 					
					$where = array("supplier_id"=>$pc->supplier_id, "prod_sku"=>$pc->prod_sku);
					
					$new_pc_obj = array();
				
					$new_pc_obj["currency_id"] = $pc->currency_id; 
					$new_pc_obj["cost"] = $pc->cost;	
					$new_pc_obj["pricehkd"] = $pc->pricehkd;	
					$new_pc_obj["lead_day"]  = $pc->lead_day;	  
					$new_pc_obj["moq"] = $pc->moq;
					$new_pc_obj["location"] = $pc->location;
					$new_pc_obj["region"] = $pc->region;
					$new_pc_obj["order_default"] = $pc->order_default;	
					$new_pc_obj["region_default"] = $pc->region_default;
					$new_pc_obj["supplier_status"] = $pc->supplier_status;
					$new_pc_obj["comments"] = $pc->comments;
					
					$this->get_dao()->q_update($where, $new_pc_obj);
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($id != "" && $id != null)
				{
					//insert				
					$new_pc_obj = array();
					
					$new_pc_obj = $this->get_dao()->get();
					$new_pc_obj->set_prod_sku($sku);
					$new_pc_obj->set_supplier_id($pc->supplier_id);
					$new_pc_obj->set_currency_id($pc->currency_id); 
					$new_pc_obj->set_cost($pc->cost);	
					$new_pc_obj->set_pricehkd($pc->pricehkd);	
					$new_pc_obj->set_lead_day($pc->lead_day);	  
					$new_pc_obj->set_moq($pc->moq);
					$new_pc_obj->set_location($pc->location);
					$new_pc_obj->set_region($pc->region);
					$new_pc_obj->set_order_default($pc->order_default);	
					$new_pc_obj->set_region_default($pc->region_default);
					$new_pc_obj->set_supplier_status($pc->supplier_status);
					$new_pc_obj->set_comments($pc->comments);
					
					$this->get_dao()->insert($new_pc_obj);	
					
					//return result
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';
				}
				else
				{
					$xml[] = '<product>';
					$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
					$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
					$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
					$xml[] = '</product>';				
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<product>';
				$xml[] = '<prod_sku>' . $pc->prod_sku . '</prod_sku>';
				$xml[] = '<supplier_id>' . $pc->supplier_id . '</supplier_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';					
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $pc->is_error . '</is_error>';
				$xml[] = '</product>';	
			}
		 }
		$xml[] = '</products>';
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}