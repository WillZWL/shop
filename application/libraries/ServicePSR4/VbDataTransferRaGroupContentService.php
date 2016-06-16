<?php
namespace ESG\Panther\Service;

class VbDataTransferRaGroupContentService extends VbDataTransferService
{

	/**********************************************************************
	*	processVbData, get the VB data to save it in the ra_group_content table
	***********************************************************************/
	public function processVbData (&$feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		unset($feed);
		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<ra_groups task_id="' . $task_id . '">';

		$error_message = '';
		foreach($xml_vb->ra_group as $ra_group)
		{
			try
			{
				$group_cont_obj = $this->getDao('RaGroupContent')->get(['group_id'=>$ra_group->group_id]);

				$reason = 'insert_or_update';

				if($group_cont_obj) {
					//We dont update ra_group_content (only insert)
					$reason = 'update';
					$status = '2';
				}
				else
				{
					//insert ra_group
					$group_cont_obj = new \RaGroupContentVo(); //$this->getDao()->get();
					$group_cont_obj->setGroupId($ra_group->group_id);
					$group_cont_obj->setGroupDisplayName($ra_group->group_display_name);
					$group_cont_obj->setLangId($ra_group->lang_id);

					$this->getDao('RaGroupContent')->insert($group_cont_obj);

					$reason = 'insert';
					$status = '5';
				}

				//return result
				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
				$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
				$xml[] = '<status>'.$status.'</status>';
				$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
				$xml[] = '<reason>'.$reason.'</reason>';
				$xml[] = '</ra_group>';

			}
			catch(Exception $e)
			{
				$xml[] = '<ra_group>';
				$xml[] = '<group_id>' . $ra_group->group_id . '</group_id>';
				$xml[] = '<lang_id>' . $ra_group->lang_id . '</lang_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $ra_group->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</ra_group>';
				$error_message .= $ra_group->group_id .'-'. $ra_group->lang_id .'-'. $ra_group->is_error .'-'. $e->getMessage()."\r\n";
			}
		 }

		$xml[] = '</ra_groups>';
		$return_feed = implode("", $xml);

		if ($error_message) {
            mail('data_transfer@eservicesgroup.com', 'RaGroupContent Transfer Failed', "Error Message :".$error_message);
        }
        unset($xml);
        unset($xml_vb);
		return $return_feed;
	}
}