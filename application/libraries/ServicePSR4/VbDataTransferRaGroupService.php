<?php
namespace ESG\Panther\Service;

//use ESG\Panther\Dao\RaGroupDao;

class VbDataTransferRaGroupService extends VbDataTransferService
{

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

		foreach($xml_vb->ra_group as $ra_group)
		{
			try
			{
				if($ra_group_obj =$this->getDao('RaGroup')->get(['group_id'=>$ra_group->group_id]))
				{
					//Update the AtomV2 ra_group data
					$ra_group_obj->setGroupName($ra_group->group_name);
					$ra_group_obj->setStatus($ra_group->status);
					$ra_group_obj->setWarranty($ra_group->warranty);
					$ra_group_obj->setIgnoreQtyBundle($ra_group->ignore_qty_bundle);

					$this->getDao('RaGroup')->update($ra_group_obj);

					$reason = 'update';
				}
				else
				{
					//insert ra_group
					$ra_group_obj = new \RaGroupVo(); //$this->getDao()->get();
					$ra_group_obj->setGroupId($ra_group->group_id);
					$ra_group_obj->setGroupName($ra_group->group_name);
					$ra_group_obj->setStatus($ra_group->status);
					$ra_group_obj->setWarranty($ra_group->warranty);
					$ra_group_obj->setIgnoreQtyBundle($ra_group->ignore_qty_bundle);

					$this->getDao('RaGroup')->insert($ra_group_obj);

					$reason = 'insert';
				}



				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
				$xml[] = '<status>5</status>'; //updated
				$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
				$xml[] = '<reason>'.$reason.'</reason>';
				$xml[] = '</ra_group>';
			}
			catch(Exception $e)
			{
				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</ra_group>';
			}
		 }

		$xml[] = '</ra_groups>';


		$return_feed = implode("\n", $xml);

		return $return_feed;
	}
}