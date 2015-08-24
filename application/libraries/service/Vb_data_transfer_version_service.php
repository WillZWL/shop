<?php defined('BASEPATH') OR exit('No direct script access allowed');

include_once(APPPATH . "libraries/service/Vb_data_transfer_service.php");

class Vb_data_transfer_version_service extends Vb_data_transfer_service
{
	
	public function __construct($debug = 0)
	{
		parent::__construct($debug);
				
		include_once(APPPATH . 'libraries/dao/Version_dao.php');
		$this->version_dao = new Version_dao();
	}
	
	public function get_dao()
	{
		return $this->version_dao;
	}

	public function set_dao(base_dao $dao)
	{
		$this->version_dao = $dao;
	}
		
	/**********************************************************************
	*	process_vb_data, get the VB data to save it in the version table
	***********************************************************************/
	public function process_vb_data ($feed)
	{		
		print $feed;
		exit;
		//Read the data sent from VB
		$xml_vb = simplexml_load_string($feed);
		
		$task_id = $xml_vb->attributes()->task_id;
				
		//Create return xml string
		$xml = array();
		$xml[] = '<?xml version="1.0" encoding="UTF-8"?>';
		$xml[] = '<no_updated_versions task_id="' . $task_id . '">';
					
		$c = count($xml_vb->version);
		foreach($xml_vb->version as $version)
		{
			$c--;			
				
			$id = $version->id;
						
			if($this->get_dao()->get(array("id"=>$version->id)))
			{
				//update					
				$where = array("id"=>$id);
				
				$new_version_obj = array();				
				
				$new_version_obj["desc"] = $version->desc;					
				$new_version_obj["status"] = $version->status;	
				
				$this->get_dao()->q_update($where, $new_version_obj);
			}
			else
			{
				//insert
				$new_version_obj = array();
				
				$new_version_obj["id"] = $version->id;
				$new_version_obj["desc"] = $version->desc;						
				$new_version_obj["status"] = $version->status;	
				
				$this->get_dao()->q_insert($new_version_obj);
			}            
		 }
		 
		$xml[] = '</no_updated_versions>';
		
		
		$return_feed = implode("\n", $xml);	
			
		return $return_feed;
	}
}