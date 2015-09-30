<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_ra_prod_cat_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Ra_prod_cat_dao.php');
		$this->ra_prod_cat_dao = new Ra_prod_cat_dao();
	}
	
	public function get_dao()
	{
		return $this->ra_prod_cat_dao;
	}
	
	public function set_dao(base_dao $dao)
	{
		$this->ra_prod_cat_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the ra_prod_cat table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		//print $feed; exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_prod_cats task_id="' . $task_id . '">';
					
		$c = count($xml_vb->ra_prod_cat);
		foreach($xml_vb->ra_prod_cat as $ra_prod_cat)
		{
			$c--;	
			try
			{
				if($this->get_dao()->get(array("ss_cat_id"=>$ra_prod_cat->ss_cat_id)))
				{				
					//Update the AtomV2 ra_prod_cat data 					
					$where = array("ss_cat_id"=>$ra_prod_cat->ss_cat_id);
					
					$new_ra_prod_cat_obj = array();
					
					$new_ra_prod_cat_obj["rcm_ss_cat_id_1"] = $ra_prod_cat->rcm_ss_cat_id_1;					
					$new_ra_prod_cat_obj["rcm_ss_cat_id_2"] = $ra_prod_cat->rcm_ss_cat_id_2;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_3"] = $ra_prod_cat->rcm_ss_cat_id_3;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_4"] = $ra_prod_cat->rcm_ss_cat_id_4;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_5"] = $ra_prod_cat->rcm_ss_cat_id_5;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_6"] = $ra_prod_cat->rcm_ss_cat_id_6;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_7"] = $ra_prod_cat->rcm_ss_cat_id_7;
					$new_ra_prod_cat_obj["rcm_ss_cat_id_8"] = $ra_prod_cat->rcm_ss_cat_id_8;				
					$new_ra_prod_cat_obj["warranty_cat"] = $ra_prod_cat->warranty_cat;	
					$new_ra_prod_cat_obj["status"] = $ra_prod_cat->status;							
					
					$this->get_dao()->q_update($where, $new_ra_prod_cat_obj);	

					$xml[] = '<ra_prod_cat>';
					$xml[] = '<ss_cat_id>' . $ra_prod_cat->ss_cat_id . '</ss_cat_id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $ra_prod_cat->is_error . '</is_error>';
					$xml[] = '</ra_prod_cat>';			
				}
				else
				{
					//insert ra_prod_cat
					$new_ra_prod_cat_obj = array();
					
					$new_ra_prod_cat_obj = $this->get_dao()->get();
					$new_ra_prod_cat_obj->set_ss_cat_id($ra_prod_cat->ss_cat_id);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_1($ra_prod_cat->rcm_ss_cat_id_1);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_2($ra_prod_cat->rcm_ss_cat_id_2);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_3($ra_prod_cat->rcm_ss_cat_id_3);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_4($ra_prod_cat->rcm_ss_cat_id_4);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_5($ra_prod_cat->rcm_ss_cat_id_5);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_6($ra_prod_cat->rcm_ss_cat_id_6);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_7($ra_prod_cat->rcm_ss_cat_id_7);
					$new_ra_prod_cat_obj->set_rcm_ss_cat_id_8($ra_prod_cat->rcm_ss_cat_id_8);
					$new_ra_prod_cat_obj->set_warranty_cat($ra_prod_cat->warranty_cat);	
					$new_ra_prod_cat_obj->set_status($ra_prod_cat->status);	
					
					$this->get_dao()->insert($new_ra_prod_cat_obj);	

					$xml[] = '<ra_prod_cat>';
					$xml[] = '<ss_cat_id>' . $ra_prod_cat->ss_cat_id . '</ss_cat_id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $ra_prod_cat->is_error . '</is_error>';
					$xml[] = '</ra_prod_cat>';					
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<ra_prod_cat>';
				$xml[] = '<ss_cat_id>' . $ra_prod_cat->ss_cat_id . '</ss_cat_id>';				
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_prod_cat->is_error . '</is_error>';
				$xml[] = '</ra_prod_cat>';
			}
		 }
		 
		$xml[] = '</ra_prod_cats>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}