<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RaProdCatDao;

class VbDataTransferRaProdCatService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->RaProdCatDao;
	}
			
	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_prod_cat table
	***********************************************************************/
	public function processVbData ($feed)
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
				if($this->getDao()->get(array("ss_cat_id"=>$ra_prod_cat->ss_cat_id)))
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
					
					$this->get_dao()->qUpdate($where, $new_ra_prod_cat_obj);	

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
					$new_ra_prod_cat_obj->setSsCatId($ra_prod_cat->ss_cat_id);
					$new_ra_prod_cat_obj->setRcmSsCatId1($ra_prod_cat->rcm_ss_cat_id_1);
					$new_ra_prod_cat_obj->setRcmSsCatId2($ra_prod_cat->rcm_ss_cat_id_2);
					$new_ra_prod_cat_obj->setRcmSsCatId3($ra_prod_cat->rcm_ss_cat_id_3);
					$new_ra_prod_cat_obj->setRcmSsCatId4($ra_prod_cat->rcm_ss_cat_id_4);
					$new_ra_prod_cat_obj->setRcmSsCatId5($ra_prod_cat->rcm_ss_cat_id_5);
					$new_ra_prod_cat_obj->setRcmSsCatId6($ra_prod_cat->rcm_ss_cat_id_6);
					$new_ra_prod_cat_obj->setRcmSsCatId7($ra_prod_cat->rcm_ss_cat_id_7);
					$new_ra_prod_cat_obj->setRcmSsCatId8($ra_prod_cat->rcm_ss_cat_id_8);
					$new_ra_prod_cat_obj->setWarrantyCat($ra_prod_cat->warranty_cat);	
					$new_ra_prod_cat_obj->setStatus($ra_prod_cat->status);	
					
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