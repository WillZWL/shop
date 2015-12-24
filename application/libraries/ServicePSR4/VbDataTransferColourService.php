<?php
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ColourDao;

class VbDataTransferColourService extends  VbDataTransferService
{

	public function __construct()
	{
		parent::__construct();
		$this->setDao(new ColourDao);
	}

	/**********************************************************************
	*	processVbData, get the VB data to save it in the colour table
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
		$xml[] = '<colours task_id="' . $task_id . '">';

		$c = count($xml_vb->colour);
		foreach($xml_vb->colour as $colour)
		{
			$c--;
			try
			{
				if($this->getDao()->get(array("id"=>$colour->id)))
				{
					//Update the AtomV2 colour data
					$where = array("id"=>$colour->id);

					$new_colour_obj = array();

					$new_colour_obj["name"] = $colour->name;
					$new_colour_obj["status"] = $colour->status;

					$this->getDao()->qUpdate($where, $new_colour_obj);

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->id . '</id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '<reason>update</reason>';
					$xml[] = '</colour>';
				}
				else
				{
					//insert colour
					$new_colour_obj = array();

					$new_colour_obj = $this->getDao()->get();
					$new_colour_obj->setId($colour->id);
					$new_colour_obj->setName($colour->name);
					$new_colour_obj->setStatus($colour->status);

					$this->getDao()->insert($new_colour_obj);

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->id . '</id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '<reason>insert</reason>';
					$xml[] = '</colour>';
				}
			}
			catch(Exception $e)
			{
				$xml[] = '<colour>';
				$xml[] = '<id>' . $colour->id . '</id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
				$xml[] = '<reason>' . $e->getMessage() . '</reason>';
				$xml[] = '</colour>';
			}
		 }

		$xml[] = '</colours>';


		$return_feed = implode("", $xml);

		return $return_feed;
	}
}