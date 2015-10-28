<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\VersionDao;

class VbDataTransferVersionService extends VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new VersionDao);
	}


	/**********************************************************************
	*	processVbData, get the VB data to save it in the version table
	***********************************************************************/
	public function processVbData ($feed)
	{
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);

		$task_id = $xml_vb->attributes()->task_id;

		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<versions task_id="' . $task_id . '">';

		$c = count($xml_vb->version);
		foreach($xml_vb->version as $version)
		{
			$c--;

			$id = $version->id;
			try
			{
				if($this->getDao()->get(array("id"=>$version->id)))
				{
					//update
					$where = array("id"=>$id);

					$new_version_obj = array();

					$new_version_obj["desc"] = $version->desc;
					$new_version_obj["status"] = $version->status;

					$this->getDao()->qUpdate($where, $new_version_obj);

					$xml[] = '<version>';
					$xml[] = '<id>' . $version->id . '</id>';
					$xml[] = '<status>5</status>';	//updated
					$xml[] = '<is_error>' . $version->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</version>';
				}
				else
				{
					//insert
					$new_version_obj = array();

					$new_version_obj = $this->getDao()->get();
					$new_version_obj->setId($version->id);
					$new_version_obj->setDesc($version->desc);
					$new_version_obj->setStatus($version->status);

					$this->getDao()->insert($new_version_obj);

					$xml[] = '<version>';
					$xml[] = '<id>' . $version->id . '</id>';
					$xml[] = '<status>5</status>';	//updated
					$xml[] = '<is_error>' . $version->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</version>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<version>';
				$xml[] = '<id>' . $version->id . '</id>';
				$xml[] = '<status>4</status>';	//error
				$xml[] = '<is_error>' . $version->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</version>';
			}
		 }

		$xml[] = '</versions>';

		$return_feed = implode("\n", $xml);

		// print $return_feed;
		// exit;

		return $return_feed;
	}
}