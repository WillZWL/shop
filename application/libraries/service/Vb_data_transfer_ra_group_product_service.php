<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_ra_group_product_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Ra_group_product_dao.php');
		$this->ra_group_product_dao = new Ra_group_product_dao();
		
        include_once(APPPATH . "libraries/service/Sku_mapping_service.php");		
		$this->sku_mapping_service = new Sku_mapping_service();	
	}
	
	public function get_dao()
	{
		return $this->ra_group_product_dao;
	}
	
	public function set_dao(base_dao $dao)
	{
		$this->ra_group_product_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the ra_group_product table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		print $feed; exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_group_products task_id="' . $task_id . '">';
					
		$c = count($xml_vb->ra_group_product);
		foreach($xml_vb->ra_group_product as $ra_group_product)
		{
			$c--;
			try
			{					
				//Get the master sku to search the corresponding sku in atomv2 database
				$master_sku = $ra_group_product->master_sku;
							
				$master_sku = strtoupper($master_sku);
				$sku = $this->sku_mapping_service->get_local_sku($master_sku);
				
				$fail_reason = "";
				if ($master_sku == "" || $master_sku == null) $fail_reason .= "No master SKU mapped, ";
				if ($sku == "" || $sku == null) $fail_reason .= "SKU not specified, ";			
				
				if ($fail_reason == "")
				{	
					$is_delete =  $ra_group_product->is_delete;	
					
					//Get the external (VB) ra_group_id to search the corresponding row in atomv2 database
					if($ra_group_product_ext_atomv2 = $this->get_dao()->get(array("ra_group_id"=>$ra_group_product->ra_group_id, "sku"=>$sku)))
					{
						$id = $ra_group_product->ra_group_id;
					}
					
					//if id exists, update
					if ($id != "" && $id != null)
					{
						if ($is_delete)
						{											
							$d_where["ra_group_id"] =  $id;
							$d_where["sku"] =  $sku;
							$this->get_dao()->q_delete($d_where);	
							
							$xml[] = '<ra_group_product>';
							$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';				
							$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
							$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';	
							$xml[] = '<status>5</status>'; //updated
							$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
							$xml[] = '</ra_group_product>';
						}
						else
						{
							//Update the AtomV2 ra_group_product extend data 					
							$where = array("ra_group_id"=>$id, "sku"=>$sku);
							
							$new_ra_group_products_obj = array();
							
							$new_ra_group_products_obj["name"] = $ra_group_product->name;
							
							$this->get_dao()->q_update($where, $new_ra_group_products_obj);
							
							$xml[] = '<ra_group_product>';
							$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';				
							$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
							$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';	
							$xml[] = '<status>5</status>'; //updated
							$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
							$xml[] = '</ra_group_product>';
						}
					}
					//if not exists, insert
					else
					{
						$new_ra_group_products_obj = array();
						
						$new_ra_group_products_obj = $this->get_dao()->get();
						$new_ra_group_products_obj->set_ra_group_id($ra_group_product->ra_group_id);
						$new_ra_group_products_obj->set_sku($sku);
						$new_ra_group_products_obj->set_name($ra_group_product->name);
						
						$this->get_dao()->insert($new_ra_group_products_obj);	
						
						$xml[] = '<ra_group_product>';
						$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';				
						$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
						$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';	
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
						$xml[] = '</ra_group_product>';
					}
				}
				elseif ($sku == "" || $sku == null)
				{				
					//if the master_sku is not found in atomv2, we have to store that sku in an xml string to send it to VB
					$xml[] = '<ra_group_product>';
					$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';	
					$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';		
					$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';		
					$xml[] = '<status>2</status>'; //sku not found
					$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
					$xml[] = '</ra_group_product>';
				}
				else
				{
					$xml[] = '<ra_group_product>';
					$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';	
					$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';		
					$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';		
					$xml[] = '<status>3</status>'; //not updated
					$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
					$xml[] = '</ra_group_product>';			
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<ra_group_product>';
				$xml[] = '<id>' . $ra_group_product->ra_group_id . '</id>';				
				$xml[] = '<sku>' . $ra_group_product->sku . '</sku>';
				$xml[] = '<master_sku>' . $ra_group_product->master_sku . '</master_sku>';	
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_group_product->is_error . '</is_error>';
				$xml[] = '</ra_group_product>';
			}
		 }
		 
		$xml[] = '</ra_group_products>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}