<?php 
namespace ESG\Panther\Service;

use ESG\Panther\Dao\ColourExtendDao;

class VbDataTransferColourExtendService extends VbDataTransferService
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
	public function getDao()
	{
		return $this->ColourExtendDao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the colour table
	***********************************************************************/
	public function processVbData ($feed)
	{		
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
				//Get the external (VB) colour id to search the corresponding id in atomv2 database
				$id = $colour->colour_id;
				$lang_id = "";
				
				if($colour_ext_atomv2 = $this->getDao()->get(array("colour_id"=>$id, "lang_id"=>$colour->lang_id)))
				{
					$lang_id = $colour_ext_atomv2->getLangId();
				}				
				//if extend content exists, update
				if ($lang_id != "" && $lang_id != null)
				{
					//Update the AtomV2 colour extend data 					
					$where = array("colour_id"=>$id, "lang_id"=>$lang_id);
					
					$new_colour_obj = array();
					
					$new_colour_obj["name"] = $colour->name;
					
					$this->getDao()->qUpdate($where, $new_colour_obj);
					
					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->colour_id . '</id>';				
					$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';
				}
				//if not exists, insert
				else
				{
					$new_colour_obj = array();
					
					$new_colour_obj = $this->getDao()->get();
					$new_colour_obj->setColourId($id);
					$new_colour_obj->setLangId($colour->lang_id);
					$new_colour_obj->setName($colour->name);
					
					$this->getDao()->insert($new_colour_obj);	
					
					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->colour_id . '</id>';				
					$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<colour>';
				$xml[] = '<id>' . $colour->colour_id . '</id>';				
				$xml[] = '<lang_id>' . $colour->lang_id . '</lang_id>';
				$xml[] = '<status>4</status>'; //error
				$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
				$xml[] = '</colour>';
			}
		 }
		 
		$xml[] = '</colours>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}