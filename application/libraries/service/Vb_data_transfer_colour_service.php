<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_colour_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Colour_dao.php');
		$this->colour_dao = new Colour_dao();
	}
	
	public function get_dao()
	{
		return $this->colour_dao;
	}
	
	public function get_map_dao()
	{
		return $this->colour_id_mapping_service;
	}

	public function set_dao(base_dao $dao)
	{
		$this->colour_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the colour table
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
		$xml[] = '<colours task_id="' . $task_id . '">';
					
		$c = count($xml_vb->colour);
		foreach($xml_vb->colour as $colour)
		{
			$c--;	
			try
			{
				if($this->get_dao()->get(array("id"=>$colour->id)))
				{				
					//Update the AtomV2 colour data 					
					$where = array("id"=>$colour->id);
					
					$new_colour_obj = array();
					
					$new_colour_obj["name"] = $colour->name;					
					$new_colour_obj["status"] = $colour->status;	
					
					$this->get_dao()->q_update($where, $new_colour_obj);	

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->id . '</id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';			
				}
				else
				{
					//insert colour
					$new_colour_obj = array();
					
					$new_colour_obj = $this->get_dao()->get();
					$new_colour_obj->set_id($colour->id);
					$new_colour_obj->set_name($colour->name);
					$new_colour_obj->set_status($colour->status);
					
					$this->get_dao()->insert($new_colour_obj);	

					$xml[] = '<colour>';
					$xml[] = '<id>' . $colour->id . '</id>';				
					$xml[] = '<status>5</status>'; //updated
					$xml[] = '<is_error>' . $colour->is_error . '</is_error>';
					$xml[] = '</colour>';					
				}
			}	
			catch(Exception $e)
			{
				$xml[] = '<colour>';
				$xml[] = '<id>' . $colour->id . '</id>';				
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