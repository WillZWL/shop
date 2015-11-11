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
				if($this->get_dao()->get(array("id"=>$version->id)))
				{
					//update					
					$where = array("id"=>$id);
					
					$new_version_obj = array();				
					
					$new_version_obj["desc"] = $version->desc;					
					$new_version_obj["status"] = $version->status;	
					
					$this->get_dao()->q_update($where, $new_version_obj);
					
					$xml[] = '<version>';
					$xml[] = '<id>' . $version->id . '</id>';				
					$xml[] = '<status>5</status>';	//updated							
					$xml[] = '<is_error>' . $version->is_error . '</is_error>';
					$xml[] = '</version>';	
				}
				else
				{
					//insert
					$new_version_obj = array();
					
					$new_version_obj = $this->get_dao()->get();
					$new_version_obj->set_id($version->id);
					$new_version_obj->set_desc($version->desc);
					$new_version_obj->set_status($version->status);
					
					$this->get_dao()->insert($new_version_obj);	
					
					$xml[] = '<version>';
					$xml[] = '<id>' . $version->id . '</id>';				
					$xml[] = '<status>5</status>';	//updated		
					$xml[] = '<is_error>' . $version->is_error . '</is_error>';		
					$xml[] = '</version>';	
				}  
			}	
			catch(Exception $e)
			{
				$xml[] = '<version>';
				$xml[] = '<id>' . $version->id . '</id>';				
				$xml[] = '<status>4</status>';	//error				
				$xml[] = '<is_error>' . $version->is_error . '</is_error>';
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