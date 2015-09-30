<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\RaGroupContentDao;
use ESG\Panther\Dao\RaGroupDao;

class VbDataTransferRaGroupContentService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function getDao()
	{
		return $this->RaGroupContentDao;
	}
		
	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_group_content table
	***********************************************************************/
	public function processVbData ($feed)
	{		
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
				if($group_atomv2 = $this->RaGroupDao->get(array("group_id"=>$ra_group->group_id)))
				{
					$id = $group_atomv2->getGroupId();				
				}
				else
				{
					$id = "";
				}
				
				if ($id != "" && $id != null)
				{
					//ra_group exists
					$lang_id = "";
					
					if($ra_group_ext_atomv2 = $this->getDao()->get(array("group_id"=>$id, "lang_id"=>$ra_group->lang_id)))
					{
						$lang_id .= $ra_group_ext_atomv2->getLangId();
					}				
					//if extend content exists, update
					if ($lang_id != "" && $lang_id != null)
					{
						//We dont update ra_group_content
						
						//return result
						$xml[] = '<ra_group>';
						$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
						$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
						$xml[] = '<status>2</status>'; //we dont update the display name of the ra_group (only insert) --> not updated
						$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
						$xml[] = '</ra_group>';		
					}
					//if not exists, insert
					else
					{
						//insert				
						$new_ra_group_obj = array();
						
						$new_ra_group_obj = $this->getDao()->get();
						$new_ra_group_obj->setGroupId($ra_group->group_id);
						$new_ra_group_obj->setGroupDisplayName($ra_group->group_display_name);
						$new_ra_group_obj->setLangId($ra_group->lang_id);
						$this->getDao()->insert($new_ra_group_obj);		

						$xml[] = '<ra_group>';
						$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
						$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
						$xml[] = '<status>5</status>'; //updated
						$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
						$xml[] = '</ra_group>';						
					}
				}
				elseif ($id == "" || $id == null)
				{
					//if the ext_id is not changed in atomv2, we store it in an xml string to send it to VB
					$xml[] = '<ra_group>';
					$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
					$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
					$xml[] = '<status>2</status>'; //ra_group not found
					$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
					$xml[] = '</ra_group>';
				}	
			}	
			catch(Exception $e)
			{
				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
				$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
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