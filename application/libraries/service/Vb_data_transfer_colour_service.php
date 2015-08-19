<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/Service/Vb_data_transfer_service.php");

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
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_colours task_id="' . $task_id . '">';
					
		$c = count($xml_vb->colour);
		foreach($xml_vb->colour as $colour)
		{
			$c--;			
			
			if($this->get_dao()->get(array("id"=>$colour->id)))
			{				
				//Update the AtomV2 colour data 					
				$where = array("id"=>$id);
				
				$new_colour_obj = array();
				
				$new_colour_obj["name"] = $colour->name;					
				$new_colour_obj["status"] = $colour->status;	
				
				$this->get_dao()->q_update($where, $new_colour_obj);				
			}
            else
			{
				//insert colour and mapping
				$new_colour_obj = array();
				
				$new_colour_obj["id"] = $colour->id;
				$new_colour_obj["name"] = $colour->name;					
				$new_colour_obj["status"] = $colour->status;	
				
				$this->get_dao()->q_insert($new_colour_obj);
				
			}
		 }
		 
		$xml[] = '</no_updated_colours>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}