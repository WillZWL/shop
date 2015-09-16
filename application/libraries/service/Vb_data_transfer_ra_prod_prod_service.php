<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_ra_prod_prod_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Ra_prod_prod_dao.php');
		$this->ra_prod_prod_dao = new Ra_prod_prod_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();	
	}
	
	public function get_dao()
	{
		return $this->ra_prod_prod_dao;
	}
	
	public function set_dao(base_dao $dao)
	{
		$this->ra_prod_prod_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the ra_prod_prod table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_sku = $xml_vb->attributes()->task_sku;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_products task_sku="' . $task_sku . '">';
					
		$c = count($xml_vb->ra_product);
		foreach($xml_vb->ra_product as $ra_product)
		{
			$c--;			
			
			try
			{	//Get the master sku to search the corresponding sku in atomv2 database
				$master_sku = $ra_product->master_sku;
							
				$master_sku = strtoupper($master_sku);
				$sku = $this->sku_mapping_service->get_local_sku($master_sku);
				
				$fail_reason = "";
				if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
				if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
				
				//Get the external (VB) ra_product id to search the corresponding row in atomv2 database
				$rcm_prod_id_1 = "";
				if ($fail_reason == "")
				{
					if($ra_product_ext_atomv2 = $this->get_dao()->get(array("sku"=>$sku, "rcm_prod_id_1"=>$ra_product->rcm_prod_id_1)))
					{
						$rcm_prod_id_1 = $ra_product_ext_atomv2->get_rcm_prod_id_1();
					}				
					//if ra_prod_prod exists, update
					if ($rcm_prod_id_1 != "" && $rcm_prod_id_1 != null)
					{
						//Update the AtomV2 ra_product extend data 					
						$where = array("sku"=>$sku, "rcm_prod_id_1"=>$rcm_prod_id_1);
						
						$new_ra_product_obj = array();
						
						$new_ra_product_obj["rcm_prod_id_2"] = $ra_product->rcm_prod_id_2;
						$new_ra_product_obj["rcm_prod_id_3"] = $ra_product->rcm_prod_id_3;
						$new_ra_product_obj["rcm_prod_id_4"] = $ra_product->rcm_prod_id_4;
						$new_ra_product_obj["rcm_prod_id_5"] = $ra_product->rcm_prod_id_5;
						$new_ra_product_obj["rcm_prod_id_6"] = $ra_product->rcm_prod_id_6;
						$new_ra_product_obj["rcm_prod_id_7"] = $ra_product->rcm_prod_id_7;
						$new_ra_product_obj["rcm_prod_id_8"] = $ra_product->rcm_prod_id_8;
						
						$this->get_dao()->q_update($where, $new_ra_product_obj);
						
						$xml[] = '<ra_product>';
						$xml[] = '<sku>' . $ra_product->sku . '</sku>';				
						$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';	
						$xml[] = '<rcm_prod_id_1>' . $ra_product->rcm_prod_id_1 . '</rcm_prod_id_1>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
						$xml[] = '</ra_product>';
					}
					//if not exists, insert
					else
					{
						$new_ra_product_obj = $this->get_dao()->get();
						
						$new_ra_product_obj->set_sku($sku);
						$new_ra_product_obj->set_rcm_prod_id_1($ra_product->rcm_prod_id_1);
						$new_ra_product_obj->set_rcm_prod_id_2($ra_product->rcm_prod_id_2);
						$new_ra_product_obj->set_rcm_prod_id_3($ra_product->rcm_prod_id_3);
						$new_ra_product_obj->set_rcm_prod_id_4($ra_product->rcm_prod_id_4);
						$new_ra_product_obj->set_rcm_prod_id_5($ra_product->rcm_prod_id_5);
						$new_ra_product_obj->set_rcm_prod_id_6($ra_product->rcm_prod_id_6);
						$new_ra_product_obj->set_rcm_prod_id_7($ra_product->rcm_prod_id_7);
						$new_ra_product_obj->set_rcm_prod_id_8($ra_product->rcm_prod_id_8);
						
						$this->get_dao()->insert($new_ra_product_obj);	
						
						$xml[] = '<ra_product>';
						$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
						$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
						$xml[] = '<rcm_prod_id_1>' . $ra_product->rcm_prod_id_1 . '</rcm_prod_id_1>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
						$xml[] = '</ra_product>';
					}
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<ra_product>';
					$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
					$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
					$xml[] = '<rcm_prod_id_1>' . $ra_product->rcm_prod_id_1 . '</rcm_prod_id_1>';		
					$xml[] = '<status>2</status>'; //sku not found
					$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
					$xml[] = '</ra_product>';
				}
				else
				{
					$xml[] = '<ra_product>';
					$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
					$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
					$xml[] = '<rcm_prod_id_1>' . $ra_product->rcm_prod_id_1 . '</rcm_prod_id_1>';	
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
					$xml[] = '</ra_product>';			
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<ra_product>';
				$xml[] = '<sku>' . $ra_product->sku . '</sku>';				
				$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';
				$xml[] = '<rcm_prod_id_1>' . $ra_product->rcm_prod_id_1 . '</rcm_prod_id_1>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
				$xml[] = '</ra_product>';
			}
		 }
		 
		$xml[] = '</ra_products>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}