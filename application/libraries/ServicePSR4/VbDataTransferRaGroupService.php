<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RaGroupDao;

class VbDataTransferRaGroupService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->RaGroupDao;
	}
		
	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_group table
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
		$xml[] = '<ra_groups task_id="' . $task_id . '">';
					
		$c = count($xml_vb->ra_group);
		foreach($xml_vb->ra_group as $ra_group)
		{
			$c--;	
			try
			{
				if($this->getDao()->get(array("group_id"=>$ra_group->group_id)))
				{				
					//Update the AtomV2 ra_group data 					
					$where = array("group_id"=>$ra_group->group_id);
					
					$new_ra_group_obj = array();
					
					$new_ra_group_obj["group_name"] = $ra_group->group_name;					
					$new_ra_group_obj["status"] = $ra_group->status;						
					$new_ra_group_obj["warranty"] = $ra_group->warranty;						
					$new_ra_group_obj["ignore_qty_bundle"] = $ra_group->ignore_qty_bundle;	
					
					$this->getDao()->qUpdate($where, $new_ra_group_obj);	

					$xml[] = '<ra_group>';
					$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
					$xml[] = '</ra_group>';			
				}
				else
				{
					//insert ra_group
					$new_ra_group_obj = array();
					
					$new_ra_group_obj = $this->getDao()->get();
					$new_ra_group_obj->setGroupId($ra_group->group_id);
					$new_ra_group_obj->setGroupName($ra_group->group_name);
					$new_ra_group_obj->setStatus($ra_group->status);
					$new_ra_group_obj->setWarranty($ra_group->warranty);
					$new_ra_group_obj->setIgnoreQtyBundle($ra_group->ignore_qty_bundle);
					
					$this->getDao()->insert($new_ra_group_obj);	

					$xml[] = '<ra_group>';
					$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
					$xml[] = '</ra_group>';					
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';				
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
				$xml[] = '</ra_group>';
			}
		 }
		 
		$xml[] = '</ra_groups>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}