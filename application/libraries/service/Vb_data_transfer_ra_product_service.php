<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_ra_product_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Ra_product_dao.php');
		$this->ra_product_dao = new Ra_product_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();		
	}
	
	public function get_dao()
	{
		return $this->ra_product_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->ra_product_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the ra_product table
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
				
			//Get the master sku to search the corresponding sku in atomv2 database
			$master_sku = $ra_product->master_sku;
						
			$master_sku = strtoupper($master_sku);
			$sku = $this->sku_mapping_service->get_local_sku($master_sku);
			
			$fail_reason = "";
            if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
            if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";
			
			try
			{	if ($fail_reason == "")
				{		
					if($this->get_dao()->get(array("sku"=>$sku)))
					{
						//update					
						$where = array("sku"=>$sku);
						
						$new_ra_product_obj = array();
						
						$new_ra_product_obj["rcm_group_id_1"] = $ra_product->rcm_group_id_1;
						$new_ra_product_obj["bundle_use_1"] = $ra_product->bundle_use_1;
						$new_ra_product_obj["rcm_group_id_2"] = $ra_product->rcm_group_id_2;
						$new_ra_product_obj["bundle_use_2"] = $ra_product->bundle_use_2;
						$new_ra_product_obj["rcm_group_id_3"] = $ra_product->rcm_group_id_3;
						$new_ra_product_obj["bundle_use_3"] = $ra_product->bundle_use_3;
						$new_ra_product_obj["rcm_group_id_4"] = $ra_product->rcm_group_id_4;
						$new_ra_product_obj["bundle_use_4"] = $ra_product->bundle_use_4;
						$new_ra_product_obj["rcm_group_id_5"] = $ra_product->rcm_group_id_5;
						$new_ra_product_obj["bundle_use_5"] = $ra_product->bundle_use_5;
						$new_ra_product_obj["rcm_group_id_6"] = $ra_product->rcm_group_id_6;
						$new_ra_product_obj["bundle_use_6"] = $ra_product->bundle_use_6;
						$new_ra_product_obj["rcm_group_id_7"] = $ra_product->rcm_group_id_7;
						$new_ra_product_obj["bundle_use_7"] = $ra_product->bundle_use_7;
						$new_ra_product_obj["rcm_group_id_8"] = $ra_product->rcm_group_id_8;
						$new_ra_product_obj["bundle_use_8"] = $ra_product->bundle_use_8;
						$new_ra_product_obj["rcm_group_id_9"] = $ra_product->rcm_group_id_9;
						$new_ra_product_obj["bundle_use_9"] = $ra_product->bundle_use_9;
						$new_ra_product_obj["rcm_group_id_10"] = $ra_product->rcm_group_id_10;
						$new_ra_product_obj["bundle_use_10"] = $ra_product->bundle_use_10;
						$new_ra_product_obj["rcm_group_id_11"] = $ra_product->rcm_group_id_11;
						$new_ra_product_obj["bundle_use_11"] = $ra_product->bundle_use_11;
						$new_ra_product_obj["rcm_group_id_12"] = $ra_product->rcm_group_id_12;
						$new_ra_product_obj["bundle_use_12"] = $ra_product->bundle_use_12;
						$new_ra_product_obj["rcm_group_id_13"] = $ra_product->rcm_group_id_13;
						$new_ra_product_obj["bundle_use_13"] = $ra_product->bundle_use_13;
						$new_ra_product_obj["rcm_group_id_14"] = $ra_product->rcm_group_id_14;
						$new_ra_product_obj["bundle_use_14"] = $ra_product->bundle_use_14;
						$new_ra_product_obj["rcm_group_id_15"] = $ra_product->rcm_group_id_15;
						$new_ra_product_obj["bundle_use_15"] = $ra_product->bundle_use_15;
						$new_ra_product_obj["rcm_group_id_16"] = $ra_product->rcm_group_id_16;
						$new_ra_product_obj["bundle_use_16"] = $ra_product->bundle_use_16;
						$new_ra_product_obj["rcm_group_id_17"] = $ra_product->rcm_group_id_17;
						$new_ra_product_obj["bundle_use_17"] = $ra_product->bundle_use_17;
						$new_ra_product_obj["rcm_group_id_18"] = $ra_product->rcm_group_id_18;
						$new_ra_product_obj["bundle_use_18"] = $ra_product->bundle_use_18;
						$new_ra_product_obj["rcm_group_id_19"] = $ra_product->rcm_group_id_19;
						$new_ra_product_obj["bundle_use_19"] = $ra_product->bundle_use_19;
						$new_ra_product_obj["rcm_group_id_20"] = $ra_product->rcm_group_id_20;
						$new_ra_product_obj["bundle_use_20"] = $ra_product->bundle_use_20;		
						
						$this->get_dao()->q_update($where, $new_ra_product_obj);

						$xml[] = '<ra_product>';
						$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
						$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
						$xml[] = '</ra_product>';	
					}
					else
					{
						//insert									
						$new_ra_product_obj = $this->get_dao()->get();
						$new_ra_product_obj->set_sku($sku);			
						$new_ra_product_obj->set_rcm_group_id_1($ra_product->rcm_group_id_1);
						$new_ra_product_obj->set_bundle_use_1($ra_product->bundle_use_1);
						$new_ra_product_obj->set_rcm_group_id_2($ra_product->rcm_group_id_2);
						$new_ra_product_obj->set_bundle_use_2($ra_product->bundle_use_2);
						$new_ra_product_obj->set_rcm_group_id_3($ra_product->rcm_group_id_3);
						$new_ra_product_obj->set_bundle_use_3($ra_product->bundle_use_3);
						$new_ra_product_obj->set_rcm_group_id_4($ra_product->rcm_group_id_4);
						$new_ra_product_obj->set_bundle_use_4($ra_product->bundle_use_4);
						$new_ra_product_obj->set_rcm_group_id_5($ra_product->rcm_group_id_5);
						$new_ra_product_obj->set_bundle_use_5($ra_product->bundle_use_5);
						$new_ra_product_obj->set_rcm_group_id_6($ra_product->rcm_group_id_6);
						$new_ra_product_obj->set_bundle_use_6($ra_product->bundle_use_6);
						$new_ra_product_obj->set_rcm_group_id_7($ra_product->rcm_group_id_7);
						$new_ra_product_obj->set_bundle_use_7($ra_product->bundle_use_7);
						$new_ra_product_obj->set_rcm_group_id_8($ra_product->rcm_group_id_8);
						$new_ra_product_obj->set_bundle_use_8($ra_product->bundle_use_8);
						$new_ra_product_obj->set_rcm_group_id_9($ra_product->rcm_group_id_9);
						$new_ra_product_obj->set_bundle_use_9($ra_product->bundle_use_9);
						$new_ra_product_obj->set_rcm_group_id_10($ra_product->rcm_group_id_10);
						$new_ra_product_obj->set_bundle_use_10($ra_product->bundle_use_10);
						$new_ra_product_obj->set_rcm_group_id_11($ra_product->rcm_group_id_11);
						$new_ra_product_obj->set_bundle_use_11($ra_product->bundle_use_11);
						$new_ra_product_obj->set_rcm_group_id_12($ra_product->rcm_group_id_12);
						$new_ra_product_obj->set_bundle_use_12($ra_product->bundle_use_12);
						$new_ra_product_obj->set_rcm_group_id_13($ra_product->rcm_group_id_13);
						$new_ra_product_obj->set_bundle_use_13($ra_product->bundle_use_13);
						$new_ra_product_obj->set_rcm_group_id_14($ra_product->rcm_group_id_14);
						$new_ra_product_obj->set_bundle_use_14($ra_product->bundle_use_14);
						$new_ra_product_obj->set_rcm_group_id_15($ra_product->rcm_group_id_15);
						$new_ra_product_obj->set_bundle_use_15($ra_product->bundle_use_15);
						$new_ra_product_obj->set_rcm_group_id_16($ra_product->rcm_group_id_16);
						$new_ra_product_obj->set_bundle_use_16($ra_product->bundle_use_16);
						$new_ra_product_obj->set_rcm_group_id_17($ra_product->rcm_group_id_17);
						$new_ra_product_obj->set_bundle_use_17($ra_product->bundle_use_17);
						$new_ra_product_obj->set_rcm_group_id_18($ra_product->rcm_group_id_18);
						$new_ra_product_obj->set_bundle_use_18($ra_product->bundle_use_18);
						$new_ra_product_obj->set_rcm_group_id_19($ra_product->rcm_group_id_19);
						$new_ra_product_obj->set_bundle_use_19($ra_product->bundle_use_19);
						$new_ra_product_obj->set_rcm_group_id_20($ra_product->rcm_group_id_20);
						$new_ra_product_obj->set_bundle_use_20($ra_product->bundle_use_20);						
						
						$this->get_dao()->insert($new_ra_product_obj);

						$xml[] = '<ra_product>';
						$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
						$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
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
					$xml[] = '<status>2</status>'; //not found
					$xml[] = '<is_error>' . $ra_product->is_error . '</is_error>';
					$xml[] = '</ra_product>';
				}
				else
				{
					$xml[] = '<ra_product>';
					$xml[] = '<sku>' . $ra_product->sku . '</sku>';		
					$xml[] = '<master_sku>' . $ra_product->master_sku . '</master_sku>';		
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