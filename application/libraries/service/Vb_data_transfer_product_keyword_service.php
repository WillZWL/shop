<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/Service/Vb_data_transfer_service.php");

class Vb_data_transfer_product_keyword_service extends Vb_data_transfer_service
{
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Product_keyword_dao.php');
		$this->pc_dao = new Product_keyword_dao();
		
        include_once(APPPATH . "libraries/Service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();
	}
	
	public function get_dao()
	{
		return $this->pc_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->pc_dao = $dao;
	}
		
	/*******************************************************************
	*	process_vb_data, get the VB data to save it in the price table
	********************************************************************/
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
		$xml[] = '<no_updated_products task_id="' . $task_id . '">';
		
		$error_nodes = array();	
		$error_nodes[] = '<errors task_id="' . $task_id . '">';		
				
		$current_sku = "";
		
		$c = count($xml_vb->product);
		foreach($xml_vb->product as $pc)
		{
			$c--;
			
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $pc->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
						
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
						
			if ($fail_reason == "")
			{
				if($sku != $current_sku)
				{
					//First, we delete the AtomV2 product data 					
					$where = array("sku"=>$sku);		
					$this->get_dao()->q_delete($where);
					
					$current_sku = $sku;
				}
				
				//After deleting, we insert de VB data
				$new_pc_obj = array();
				
				$new_pc_obj["sku"] = $sku;
				$new_pc_obj["lang_id"] = $pc->lang_id; 
				$new_pc_obj["keyword"] = $pc->keyword; 
				$new_pc_obj["type"] = $pc->type;	
				
				$this->get_dao()->q_insert($new_pc_obj);
				
				// print $this->db->last_query();
				// print "------------";
				// exit;
			}
			elseif ($sku == "" || $sku == null)
			{				
				//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
				$xml[] = '<product>';
				$xml[] = '<sku>' . $pc->sku . '</sku>';
				$xml[] = '<platform_id>' . $pc->lang_id . '</platform_id>';
				$xml[] = '<master_sku>' . $pc->master_sku . '</master_sku>';		
				$xml[] = '</product>';
			}
			else
			{
				$error_nodes[] = '<error>';
				$error_nodes[] = '<sku>' . $pc->sku . '</sku>';		
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