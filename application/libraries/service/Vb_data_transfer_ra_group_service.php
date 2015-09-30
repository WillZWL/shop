<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_ra_group_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Ra_group_dao.php');
		$this->ra_group_dao = new Ra_group_dao();
	}
	
	public function get_dao()
	{
		return $this->ra_group_dao;
	}
	
	public function set_dao(base_dao $dao)
	{
		$this->ra_group_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the ra_group table
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
		$xml[] = '<ra_groups task_id="' . $task_id . '">';
					
		$c = count($xml_vb->ra_group);
		foreach($xml_vb->ra_group as $ra_group)
		{
			$c--;	
			try
			{
				if($this->get_dao()->get(array("group_id"=>$ra_group->group_id)))
				{				
					//Update the AtomV2 ra_group data 					
					$where = array("group_id"=>$ra_group->group_id);
					
					$new_ra_group_obj = array();
					
					$new_ra_group_obj["group_name"] = $ra_group->group_name;					
					$new_ra_group_obj["status"] = $ra_group->status;						
					$new_ra_group_obj["warranty"] = $ra_group->warranty;						
					$new_ra_group_obj["ignore_qty_bundle"] = $ra_group->ignore_qty_bundle;	
					
					$this->get_dao()->q_update($where, $new_ra_group_obj);	

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
					
					$new_ra_group_obj = $this->get_dao()->get();
					$new_ra_group_obj->set_group_id($ra_group->group_id);
					$new_ra_group_obj->set_group_name($ra_group->group_name);
					$new_ra_group_obj->set_status($ra_group->status);
					$new_ra_group_obj->set_warranty($ra_group->warranty);
					$new_ra_group_obj->set_ignore_qty_bundle($ra_group->ignore_qty_bundle);
					
					$this->get_dao()->insert($new_ra_group_obj);	

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